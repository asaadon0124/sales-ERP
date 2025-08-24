<div class="modal fade text-start modal-primary" id="showModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">عرض تفاصيل العميل {{ $name }} </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

                <div class="modal-body">
                    <div class="row">

                        <!-- اسم العميل name  -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">اسم العميل</label>
                                @if (isset($customer->name))
                                    <span style="float: left">{{ $customer->name }}</span>
                                @endif

                            </div>
                        </div>

                           <!-- عنوان العميل address  -->
                           <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">عنوان العميل</label>
                                @if (isset($customer->address))
                                    <span style="float: left">{{ $customer->address }}</span>
                                @endif

                            </div>
                        </div>



                         <!-- account type id كود العميل -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">كود العميل</label>
                                @if (isset($customer->customer_code))
                                    <span style="float: left">{{ $customer->customer_code }}</span>
                                @endif

                            </div>
                        </div>



                          <!--  اسم الحساب الاب للعميل -->
                          <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">اسم الحساب الاب للعميل</label>
                                @if (isset($customer->customer_account->parent_account->name))
                                    <span style="float: left">{{ $customer->customer_account->parent_account->name }}</span>
                                @endif

                            </div>
                        </div>


                         <!-- رقم الحساب الاب للعميل -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">رقم الحساب الاب للعميل</label>
                                @if (isset($customer->customer_account->parent_account->account_number))
                                    <span style="float: left">{{ $customer->customer_account->parent_account->account_number }}</span>
                                @endif

                            </div>
                        </div>


                         <!--  حالة رصيد اول المدة -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">حالة رصيد اول المدة</label>
                                @if (isset($customer))
                                    <span style="float: left">{{ $customer->started_balance_status() }}</span>
                                @endif

                            </div>
                        </div>


                         <!--   رصيد اول المدة -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary"> رصيد اول المدة</label>
                                @if (isset($customer->start_balance))
                                    <span style="float: left">{{ $customer->start_balance }}</span>
                                @endif

                            </div>
                        </div>


                         <!--   الرصيد الحالي -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary"> الرصيد الحالي</label>
                                @if (isset($customer->current_balance))
                                    <span style="float: left">{{ $customer->current_balance }}</span>
                                @endif

                            </div>
                        </div>

                          <!--   حالة العميل -->
                          <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary"> حالة العميل</label>
                                @if (isset($customer->status))
                                    <span style="float: left">{{ getStatus($customer->status) }}</span>
                                @endif

                            </div>
                        </div>



                         <!--   كود الشركة -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary"> كود الشركة</label>
                                @if (isset($customer->company_code))
                                    <span style="float: left">{{ $customer->company_code }}</span>
                                @endif

                            </div>
                        </div>

                         <!--   انشاء بواسطة -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary"> انشاء بواسطة</label>
                                @if (isset($customer->adminCreate->name))
                                    <span style="float: left">{{ $customer->adminCreate->name }}</span>
                                @endif

                            </div>
                        </div>



                          <!--   اخر تحديث بواسطة -->
                          <div class="col-sm-12 mb-4">
                            <div class="form-group">
                                <label class="text-primary"> اخر تحديث بواسطة</label>
                                @if (isset($customer->adminCreate->name))
                                    <span style="float: left">
                                        {{ last_update($customer) }}
                                        بواسطة  {{ $customer->adminCreate->name }}
                                    </span>
                                @endif

                            </div>
                        </div>





                           <!--   الملاحظات -->
                           <div class="col-sm-12 mb-4">
                                <div class="form-group">
                                    <label class="text-primary"> الملاحظات</label>
                                    @if (isset($customer->notes))
                                        <span style="float: left">{{ $customer->notes }}</span>
                                    @endif

                                </div>
                            </div>


















                    </div>
                </div>

        </div>
    </div>
</div>
