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
                        <!-- اسم الحساب name  -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>اسم الحساب</label>
                                <input type="text" class="form-control" placeholder="ادخل اسم الحساب" wire:model="name">
                                @include('backEnd.error', ['property' => 'name'])

                            </div>
                        </div>



                        <!-- account type id نوع الحساب -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>نوع الحساب</label>

                                <select wire:model="account_type_id" wire:loading.attr="disabled" class="form-control" wire:target="account_type_id">
                                    <option selected>نوع الحساب</option>

                                    @foreach($acountsTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @include('backEnd.error', ['property' => 'account_type_id'])
                            </div>
                        </div>

                        <!-- is_parent هل الحساب اب -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>هل الحساب اب</label>

                                <select wire:model="is_parent" wire:loading.attr="disabled" class="form-control" wire:change="submit_is_parent($event.target.value)" wire:target="is_parent">
                                    <option selected>هل الحساب اب</option>
                                    <option value="1">نعم</option>
                                    <option value="0">لا</option>
                                </select>
                                @include('backEnd.error', ['property' => 'is_parent'])
                            </div>
                        </div>

                         <!-- parent_account_number  الحسابات الاب -->
                         <div class="col-sm-6 mb-4 {{ $is_parent == 0 ? '' : 'd-none' }}" >
                            <div class="form-group">
                                <label> الحسابات الاب</label>
                                <select wire:model="parent_account_number" wire:loading.attr="disabled" class="form-control" wire:target="parent_account_number">
                                    <option selected> اختار الحساب الاب</option>
                                    @if (isset($get_parent_accounts))
                                        @foreach ($get_parent_accounts as $parent_account)
                                            <option value="{{ $parent_account->account_number }}">{{ $parent_account->name }}</option>
                                        @endforeach
                                    @endif

                                </select>
                                @include('backEnd.error', ['property' => 'parent_account_number'])
                            </div>
                        </div>


                        <!-- start_balance_status  حالة الرصيد اول المدة -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label> حالة الرصيد اول المدة</label>

                                <select wire:model="start_balance_status" wire:loading.attr="disabled" class="form-control" wire:target="start_balance_status"readonly>
                                    <option selected> حالة الرصيد اول المدة</option>
                                    <option value="credit">مدين</option>
                                    <option value="debit">دائن</option>
                                    <option value="nun">متزن</option>
                                </select>
                                @include('backEnd.error', ['property' => 'start_balance_status'])
                            </div>
                        </div>


                        <!-- رصيد اول المدة start_balance  -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>رصيد اول المدة</label>
                                <input type="number" class="form-control" placeholder="ادخل رصيد اول المدة" wire:model="start_balance" wire:change="changeStartBalance($event.target.value)">
                                @include('backEnd.error', ['property' => 'start_balance'])

                            </div>
                        </div>


                         <!-- status حالة الحساب -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>حالة الحساب</label>

                                <select wire:model="status" wire:loading.attr="disabled" class="form-control" wire:target="status">
                                    <option selected>حالة الحساب</option>
                                    <option value="active">مفعل</option>
                                    <option value="un_active">غير مفعل</option>
                                </select>
                                @include('backEnd.error', ['property' => 'status'])
                            </div>
                        </div>

                        <!-- رصيد اول المدة start_balance  -->
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
