<?php

namespace App\Livewire\BackEnd\Accounts;

use App\Models\Account;
use Livewire\Component;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Accounts\Data;

class Delete extends Component
{

    protected $listeners = ['accountsDelete','refreshData' => '$refresh'];

    public $accountId;
    public $account;
    // public $stor_batch_qty;

    public function accountsDelete($id)
    {
        $this->accountId   = $id;
        $this->account     = Account::select('id', 'name', 'status', 'is_parent', 'current_balance')->where('id',$this->accountId)->with('chiled_accounts')->first();

        $this->dispatch('deleteModalToggle');
    }



    public function submit()
    {
        if (!auth()->user()->can('حذف الحساب'))
        {
            abort(403, 'ليس لديك صلاحية حذف الحساب');
        }

        if (!$this->account)
        {
            $this->dispatch('deleteModalToggle');
            return;
        }

        DB::beginTransaction();

        try
        {
            // لو الحساب أب وعنده حسابات فرعية
            if ($this->account->is_parent == '1' && $this->account->chiled_accounts->count() > 0)
            {
                $total_balance = $this->account->current_balance + $this->account->chiled_accounts->sum('current_balance');

                if (bccomp($total_balance, 0, 2) !== 0)
                {
                    $this->addError('current_balance', 'لا يمكن تعديل الحساب لأن مجموع الرصيد الحالي له ولجميع الحسابات الفرعية لا يساوي صفر.');
                    DB::rollBack();
                    return;
                }

                $this->account->status = 'un_active';
                $this->account->save();

                foreach ($this->account->chiled_accounts as $chiledAccount)
                {
                    $chiledAccount->status = 'un_active';
                    $chiledAccount->save();
                }
            } else
            {
                // الحساب مش أب أو ملوش أبناء
                $this->account->status = 'un_active';
                $this->account->save();
            }

            // سجل في تاريخ العمليات
            ActionHistory::create(
            [
                'title'      => 'حذف الحساب',
                'desc'       => "حذف الحساب {$this->account->name}",
                'table_name' => 'Account',
                'row_id'     => $this->account->id,
                'created_by' => auth()->user()->id,
            ]);

            DB::commit();

            $this->dispatch('storesDeleteMS');
        } catch (\Exception $e)
        {
            DB::rollBack();
            $this->addError('general', 'حدث خطأ أثناء حذف الحساب');
        }

        $this->dispatch('deleteModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }







    public function render()
    {
        return view('back-end.accounts.delete');
    }
}
