<div class="modal fade text-start modal-primary" id="createModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">اضافة حساب جديد </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form form-horizontal" wire:submit.prevent='submit'>
                <div class="modal-body">
                    <div class="row">
                        <!-- اسم المندوب name  -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>اسم المندوب</label>
                                <input type="text" class="form-control" placeholder="ادخل اسم المندوب" wire:model="name">
                                @include('backEnd.error', ['property' => 'name'])

                            </div>
                        </div>



                        <!-- start_balance_status  حالة الرصيد اول المدة -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label> حالة الرصيد اول المدة</label>

                                <select wire:model="start_balance_status" wire:loading.attr="disabled"
                                    class="form-control" wire:target="start_balance_status"disabled>
                                    <option selected> حالة الرصيد اول المدة</option>
                                    <option value="credit">مدين</option>
                                    <option value="debit">دائن</option>
                                    <option value="nun">متزن</option>
                                </select>
                                <input type="text" wire:model="start_balance_status" hidden>
                                @include('backEnd.error', ['property' => 'start_balance_status'])
                            </div>
                        </div>


                        <!-- رصيد اول المدة start_balance  -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>رصيد اول المدة</label>
                                <input type="number" class="form-control" placeholder="ادخل رصيد اول المدة"
                                    wire:model="start_balance" wire:change="changeStartBalance($event.target.value)">
                                @include('backEnd.error', ['property' => 'start_balance'])

                            </div>
                        </div>


                         <!--  عنوان المندوب address  -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>عنوان المندوب</label>
                                <input type="text" class="form-control" placeholder="ادخل عنوان المندوب" wire:model="address">
                                @include('backEnd.error', ['property' => 'address'])

                            </div>
                        </div>


                         <!-- status حالة المندوب -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>حالة المندوب</label>

                                <select wire:model="status" wire:loading.attr="disabled" class="form-control" wire:target="status">
                                    <option selected>حالة المندوب</option>
                                    <option value="active">مفعل</option>
                                    <option value="un_active">غير مفعل</option>
                                </select>
                                @include('backEnd.error', ['property' => 'status'])
                            </div>
                        </div>


                         <!-- status  حالة  اجر المندوب -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label> حالة اجر المندوب</label>

                                <select wire:model="commission_type" wire:loading.attr="disabled" class="form-control" wire:target="commission_type">
                                    <option selected> حالة اجر المندوب</option>
                                    <option value="fixed">ثابت</option>
                                    <option value="not_fixed">متغبر</option>
                                </select>
                                @include('backEnd.error', ['property' => 'commission_type'])
                            </div>
                        </div>

                        <!-- الملاحظات notes  -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>الملاحظات</label>
                                <textarea class="form-control" wire:model="notes" placeholder="الملاحظات"></textarea>
                                @include('backEnd.error', ['property' => 'notes'])

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" type="button"
                        class="btn btn-success waves-effect waves-float waves-light">اضاقة</button>
                </div>
            </form>
        </div>
    </div>
</div>
