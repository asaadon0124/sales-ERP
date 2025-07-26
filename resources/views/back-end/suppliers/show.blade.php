<div class="modal fade text-start modal-primary" id="showModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">عرض تفاصيل المورد {{ $name }} </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
           
                <div class="modal-body">
                    <div class="row">

                        <!-- اسم المورد name  -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">اسم المورد</label>
                                @if (isset($supplier->name))
                                    <span style="float: left">{{ $supplier->name }}</span>
                                @endif

                            </div>
                        </div>


                         <!-- اسم القسم الخاص بالمورد supplier category id  -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">اسم القسم الخاص بالمورد</label>
                                @if (isset($supplier->supplier_Category_id))
                                    <span style="float: left">{{ $supplier->supplierCategory->name }}</span>
                                @endif

                            </div>
                        </div>


                           <!-- عنوان المورد address  -->
                           <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">عنوان المورد</label>
                                @if (isset($supplier->address))
                                    <span style="float: left">{{ $supplier->address }}</span>
                                @endif

                            </div>
                        </div>



                         <!-- account type id كود المورد -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">كود المورد</label>
                                @if (isset($supplier->supplier_code))
                                    <span style="float: left">{{ $supplier->supplier_code }}</span>
                                @endif

                            </div>
                        </div>



                          <!--  اسم الحساب الاب للمورد -->
                          <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">اسم الحساب الاب للمورد</label>
                                @if (isset($supplier->supplier_account->parent_account->name))
                                    <span style="float: left">{{ $supplier->supplier_account->parent_account->name }}</span>
                                @endif

                            </div>
                        </div>


                         <!-- رقم الحساب الاب للمورد -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">رقم الحساب الاب للمورد</label>
                                @if (isset($supplier->supplier_account->parent_account->account_number))
                                    <span style="float: left">{{ $supplier->supplier_account->parent_account->account_number }}</span>
                                @endif

                            </div>
                        </div>


                         <!--  حالة رصيد اول المدة -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">حالة رصيد اول المدة</label>
                                @if (isset($supplier))
                                    <span style="float: left">{{ $supplier->started_balance_status() }}</span>
                                @endif

                            </div>
                        </div>


                         <!--   رصيد اول المدة -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary"> رصيد اول المدة</label>
                                @if (isset($supplier->start_balance))
                                    <span style="float: left">{{ $supplier->start_balance }}</span>
                                @endif

                            </div>
                        </div>


                         <!--   الرصيد الحالي -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary"> الرصيد الحالي</label>
                                @if (isset($supplier->current_balance))
                                    <span style="float: left">{{ $supplier->current_balance }}</span>
                                @endif

                            </div>
                        </div>


                          <!--   حالة المورد -->
                          <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary"> حالة المورد</label>
                                @if (isset($supplier->status))
                                    <span style="float: left">{{ getStatus($supplier->status) }}</span>
                                @endif

                            </div>
                        </div>


                           <!--   الملاحظات -->
                           <div class="col-sm-12 mb-4">
                            <div class="form-group">
                                <label class="text-primary"> الملاحظات</label>
                                @if (isset($supplier->notes))
                                    <span style="float: left">{{ $supplier->notes }}</span>
                                @endif

                            </div>
                        </div>

                         <!--   كود الشركة -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary"> كود الشركة</label>
                                @if (isset($supplier->company_code))
                                    <span style="float: left">{{ $supplier->company_code }}</span>
                                @endif

                            </div>
                        </div>

                         <!--   انشاء بواسطة -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary"> انشاء بواسطة</label>
                                @if (isset($supplier->adminCreate->name))
                                    <span style="float: left">{{ $supplier->adminCreate->name }}</span>
                                @endif

                            </div>
                        </div>



                          <!--   اخر تحديث بواسطة -->
                          <div class="col-sm-12 mb-4">
                            <div class="form-group">
                                <label class="text-primary"> اخر تحديث بواسطة</label>
                                @if (isset($supplier->adminCreate->name))
                                    <span style="float: left">
                                        {{ last_update($supplier) }}
                                        بواسطة  {{ $supplier->adminCreate->name }}
                                    </span>
                                @endif

                            </div>
                        </div>
                       



                    </div>
                </div>
                
        </div>
    </div>
</div>
