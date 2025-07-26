<?php

namespace App\Livewire\BackEnd\Accounts;

use App\Models\Account;
use Livewire\Component;
use App\Models\ActionHistory;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Accounts\SoftDelete;

class Restore extends Component
{

    protected $listeners = ['AccountRestore','refreshData' => '$refresh'];

    public $accountId;
    public $account;

    public function AccountRestore($id)
    {
        // dd('ds');
        $this->accountId       = $id;
        $this->account     = Account::withoutGlobalScope(ActiveScope::class)->where('id',$this->accountId)->with(['parent_account' => function($q)
        {
            $q->withoutGlobalScope(ActiveScope::class);

        }])->first();
        // show Create modal
        $this->dispatch('restoreModalToggle');
    }


    public function submit()
    {
        if (!auth()->user()->can('تفعيل الحساب'))
        {
            abort(403, 'ليس لديك صلاحية اعادة تفعيل الحساب');
        }

        DB::beginTransaction();
        try
        {
            if ($this->account)
            {
                 // إعادة تحميل العلاقة parent_account بدون Global Scope
                $this->account->load(['parent_account' => function ($q)
                {
                    $q->withoutGlobalScope(ActiveScope::class);
                }]);



                // تفعيل الحساب نفسه
                $this->account->status = 'active';
                $this->account->save();

                // لو الحساب فرعي، فعّل الأب كمان (لو موجود)
                if ($this->account->is_parent == '0' && $this->account->parent_account)
                {
                    // dd('sds');
                    $this->account->parent_account->status = 'active';
                    $this->account->parent_account->save();
                }

                // سجل العملية
                ActionHistory::create(
                [
                    'title'      => 'تفعيل الحساب',
                    'desc'       => "تفعيل الحساب {$this->account->name}",
                    'table_name' => 'Account',
                    'row_id'     => $this->account->id,
                    'created_by' => auth()->user()->id,
                ]);

                DB::commit();

                $this->dispatch('storesRestoreMS');
                $this->dispatch('restoreModalToggle');
                $this->dispatch('refreshData')->to(SoftDelete::class);

            } else {
                $this->dispatch('storeValidationMS');
                $this->dispatch('restoreModalToggle');
            }

        } catch (\Throwable $th)
        {
            DB::rollBack();
            $this->addError('general', 'حدث خطأ أثناء تفعيل الحساب');
        }
    }



    public function render()
    {
        return view('back-end.accounts.restore');
    }
}
