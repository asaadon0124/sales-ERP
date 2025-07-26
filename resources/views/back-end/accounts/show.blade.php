<div class="modal fade text-start modal-primary" id="showModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">عرض تفاصيل الحساب {{ $name }} </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form form-horizontal" wire:submit.prevent='submit'>
                <div class="modal-body">
                    <div class="row">

                        <!-- اسم الحساب name  -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">اسم الحساب</label>
                                @if (isset($account->name))
                                    <span style="float: left">{{ $account->name }}</span>
                                @endif

                            </div>
                        </div>

                         <!-- account type id نوع الحساب -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">نوع الحساب</label>
                                @if (isset($account->name))
                                    <span style="float: left">{{ $account->accountType->name }}</span>
                                @endif

                            </div>
                        </div>


                         <!--  is_parent هل الحساب اب-->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">هل الحساب اب</label>
                                @if (isset($account->name))
                                    <span style="float: left">{{ $account->isParent() }}</span>
                                @endif

                            </div>
                        </div>


                         <!--  is_parent اسم الحساب اب-->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">اسم الحساب اب</label>
                                @if (isset($account->accountParent->name))
                                    <span style="float: left">{{ $account->accountParent->name }}</span>
                                @endif

                            </div>
                        </div>


                          <!-- start_balance_status  حالة الرصيد اول المدة -->
                          <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary">حالة الرصيد اول المدة </label>
                                @if (isset($account->start_balance_status))
                                    <span style="float: left">{{ $account->started_balance_status() }}</span>
                                @endif

                            </div>
                        </div>

                         <!-- start_balance_status   الرصيد اول المدة -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label class="text-primary"> الرصيد اول المدة </label>
                                @if (isset($account->start_balance))
                                    <span style="float: left">{{ $account->start_balance }}</span>
                                @endif

                            </div>
                        </div>


                          <!-- start_balance_status   الرصيد الحالي -->
                          <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                
                                <label class="text-primary"> الرصيد الحالي </label>
                                @if (isset($account->current_balance))
                                    <span style="float: left">{{ $account->current_balance }}</span>
                                @endif
                            </div>
                        </div>



                         <!-- notes   الملاحظات -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                
                                <label class="text-primary"> الملاحظات </label>
                                @if (isset($account->notes))
                                    <span style="float: left">{{ $account->notes }}</span>
                                @endif
                            </div>
                        </div>



                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" type="button"
                        class="btn btn-info waves-effect waves-float waves-light">تعديل</button>
                </div>
            </form>
        </div>
    </div>
</div>
