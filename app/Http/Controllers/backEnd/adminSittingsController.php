<?php

namespace App\Http\Controllers\backEnd;

use App\Models\AdminSitting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\backEnd\AdminSittingRequest;
use App\Models\Account;
use App\Models\ActionHistory;

class adminSittingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:عرض الاعدادات'],['only' => 'index']);
        $this->middleware(['permission:تعديل الاعدادات'])->only(['edit', 'update']);

    }

    public function index()
    {
        $data['sitting'] = AdminSitting::where('company_code',auth()->user()->company_code)->first();
        return view('backEnd.adminSittings.index',$data);
    }

    public function edit($id)
    {
        $sitting                = AdminSitting::where('company_code',auth()->user()->company_code)->first();
        $get_parent_accounts    = Account::parent_accounts()->where('status', 'active')->get();
        $get_parent_Suppliers   = Account::parent_accounts()->where('status', 'active')->get();
        $get_parent_Servants    = Account::parent_accounts()->where('status', 'active')->get();
        $get_parent_employees    = Account::parent_accounts()->where('status', 'active')->get();
        return view('backEnd.adminSittings.edit',compact('sitting','get_parent_accounts','get_parent_Suppliers','get_parent_Servants','get_parent_employees'));
    }

    public function update(AdminSittingRequest $request,$id)
    {
        // return $request;
        $sitting = AdminSitting::where('company_code',auth()->user()->company_code)->first();
        try
        {
            DB::beginTransaction();
            $sitting->update(
            [
                'system_name'                       => $request->system_name,
                'phone'                             => $request->phone,
                'customer_parent_account_number'    => $request->customer_parent_account_number,
                'supplier_parent_account_number'    => $request->supplier_parent_account_number,
                'servant_parent_account_number'     => $request->servant_parent_account_number,
                'employee_parent_account_number'    => $request->employee_parent_account_number,
                'address'                           => $request->address,
                'updated_by'                        => auth()->user()->id,
            ]);

             // 2 - CHECK PHOTO *****************
             if ($request->hasFile('photo'))
            {
                if (!empty($sitting))
                {
                    Storage::disk('public')->delete('/assets/backEnd/images/',$sitting->photo);
                }
                $data =  $request->photo->store('adminStiitngs','public');
                $sitting->update(['photo' => $data]);

                // 3 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'تعديل الاعدادات';
                    $actionHistory->desc        = 'تعديل الاعدادات العامة الخاصة بالشركة و الحسابات';
                    $actionHistory->table_name  = 'AdminSitting';
                    $actionHistory->row_id      = $sitting->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();

            }

            DB::commit();
            toastr()->success('تم تعديل الاعدادات بنجاح');
            return redirect()->route('adminSittings.index')->with(['success' => 'تم الحفظ بنجاح']);

        } catch (\Throwable $th)
        {
            DB::rollBack();
            // return $th;
            toastr()->error('هناك خطا ما برجاء المحاولة لاحقا');
            return redirect()->route('adminSittings.index');
        }

    }
}
