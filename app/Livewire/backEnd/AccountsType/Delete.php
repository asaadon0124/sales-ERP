<?php

namespace App\Livewire\BackEnd\AccountsType;

use Livewire\Component;
use App\Models\AccountType;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;

class Delete extends Component
{

    protected $listeners = ['accountsTypesDelete', 'refreshData' => '$refresh'];

    public $accountTypeId;
    public $accountType;

    public function accountsTypesDelete($id)
    {
        $this->accountTypeId        = $id;
        $this->accountType          = AccountType::with('accounts')->where('id', $this->accountTypeId)->first();

        // show Create modal
        $this->dispatch('deleteModalToggle');
    }


    public function submit()
    {
        if (!auth()->user()->can('حذف نوع الحساب')) {
            abort(403, 'ليس لديك صلاحية حذف نوع الحساب');
        }


// dd(\App\Models\Account::where('account_type_id', $this->accountTypeId)->count());
        // dd($this->accountType->accounts);





        //  dd($this->accountType->accounts);

        if ($this->accountType)        // لو فيها اصناف و كميات الاصناف دي  == 0
        {

            if ($this->accountType->accounts->count() > 0)       // لو فيها حسابات
            {
                $allZero = $this->accountType->accounts->every(function ($account)
                {
                    return ($account->start_balance + $account->current_balance) == 0;
                });


                if ($allZero)   // لو كل الحسابات الي تحت النوع ده ارصدتها  == 0
                {
                    // 1. تحديث حالة نوع الحساب
                    $this->accountType->update(['status' => 'un_active']);

                    // 2. تحديث حالة كل الحسابات التابعة له
                    foreach ($this->accountType->accounts as $account)
                    {
                        $account->update(['status' => 'un_active']);


                         // 2. إلغاء تفعيل الكائن المرتبط بالحساب (عميل، مورد، موظف، ...)

                        if ($account->account_customer)
                        {
                            $account->account_customer->update(['status' => 'un_active']);
                        }

                        if ($account->account_customer2)
                        {
                            $account->account_customer2->update(['status' => 'un_active']);
                        }

                        if ($account->account_servant)
                        {
                            $account->account_servant->update(['status' => 'un_active']);
                        }

                        if ($account->account_employee)
                        {
                            $account->account_employee->update(['status' => 'un_active']);
                        }
                    }

                    // 3 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = "حذف نوع الحساب  ";
                    $actionHistory->desc        = "حذف نوع الحساب  {$this->accountType->name}";
                    $actionHistory->table_name  = 'accountType';
                    $actionHistory->row_id      = $this->accountType->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();
                }else
                {
                    $this->dispatch('accountsTypesValidationMS');
                    $this->dispatch('deleteModalToggle');
                }
            }elseif($this->accountType->accounts->count() == 0) // لو مفيهاش حسابات
            {
                DB::beginTransaction();
                    // 1 - UPDATE ACCOUNT TYPE TABLE
                    $this->accountType->status = 'un_active';
                    $this->accountType->save();

                    // 2 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = "حذف نوع الحساب  ";
                    $actionHistory->desc        = "حذف نوع الحساب  {$this->accountType->name}";
                    $actionHistory->table_name  = 'accountType';
                    $actionHistory->row_id      = $this->accountType->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();
                DB::commit();
                DB::rollBack();
                // Dispatch events
                $this->dispatch('accountsTypesDeleteMS');
                $this->dispatch('deleteModalToggle');
            }else
            {
                $this->dispatch('accountsTypesValidationMS');
                $this->dispatch('deleteModalToggle');
            }



            // if (
            //     $this->accountType->items->count() > 0 &&
            //     $this->accountType->items->every(function ($item)
            //     {
            //         return $item->item_batches->sum('qty') == 0;
            //     })
            // )
            // {
            //     dd('كل كميات الاصناف == 0');
            // } elseif ($this->accountType->items->count() == 0)      // لو مفيهاش اصناف
            // {

            // } else {

            // }


            // DB::rollBack();
            // // Dispatch events
            // $this->dispatch('itemCategoriesDeleteMS');
            // $this->dispatch('deleteModalToggle');
        } else
        {
            // dd($this->active_shift);
            $this->dispatch('accountsTypesValidationMS');
            $this->dispatch('deleteModalToggle');
        }

        // if ($this->accountType)
        // {
        // } else
        // {
        //     // dd($this->active_shift);
        //     $this->dispatch('accountTypesValidationMS');
        //     $this->dispatch('deleteModalToggle');
        // }
        $this->dispatch('refreshData')->to(Data::class);
    }




    public function render()
    {
        return view('back-end.accounts-type.delete');
    }
}
