<div class="modal fade text-start modal-primary" id="createModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">اضافة مستخدم جديد </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form form-horizontal" wire:submit.prevent='submit'>
                <div class="modal-body">
                    <div class="row">
                        {{-- اسم المستخدم NAME  --}}
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>اسم المستخدم</label>
                                <input type="text" class="form-control" placeholder="ادخل اسم المستخدم" wire:model="name">
                                @include('backEnd.error', ['property' => 'name'])

                            </div>
                        </div>


                            {{-- EMAIL ايميل المستخدم --}}
                            <div class="col-sm-6 mb-4">
                                <div class="form-group">
                                    <label>ايميل المستخدم</label>
                                    <input type="email" class="form-control" placeholder="ادخل ايميل المستخدم" wire:model="email">
                                    @include('backEnd.error', ['property' => 'email'])

                                </div>
                            </div>


                             {{-- PASSWORD كلمة سر  المستخدم --}}
                            <div class="col-sm-6 mb-4">
                                <div class="form-group">
                                    <label>كلمة السر للمستخدم</label>
                                    <input type="password" class="form-control" placeholder="ادخل كلمة السر للمستخدم" wire:model="password">
                                    @include('backEnd.error', ['property' => 'password'])

                                </div>
                            </div>

                        <!-- start_balance_status  حالة الرصيد اول المدة -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label> حالة الرصيد اول المدة</label>

                                <select wire:model="start_balance_status" wire:loading.attr="disabled" class="form-control" wire:target="start_balance_status"disabled>
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
                                <input type="number" class="form-control" placeholder="ادخل رصيد اول المدة" wire:model="start_balance" wire:change="changeStartBalance($event.target.value)">
                                @include('backEnd.error', ['property' => 'start_balance'])

                            </div>
                        </div>



                            {{-- حالة المستخدم STATUS  --}}
                             <div class="col-sm-6 mb-4">
                                <div class="form-group">
                                        <label for="">حالة المستخدم</label>
                                        <select wire:model="status" class="form-control">
                                            <option value="">اختار حالة المستخدم</option>
                                            <option value="active">مفعل</option>
                                            <option value="un_active">غير مفعل </option>
                                        </select>
                                    @include('backEnd.error', ['property' => 'status'])

                                </div>
                            </div>



                            {{-- ROLES ادوار المستخدم  --}}
                            <div class="col-sm-12 mb-4">
                                <div class="form-group">
                                    @if (!empty($roles))

                                        <select wire:model="user_roles" class="form-control" multiple>
                                            <option value="">اختار دور المستخدم</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif

                                    @include('backEnd.error', ['property' => 'user_roles'])

                            </div>
                        </div>
                    </div>
                </div>
               <div class="modal-footer">
                    <button type="submit"
                        class="btn btn-success waves-effect waves-float waves-light">اضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>
