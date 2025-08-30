<div class="table-responsive">
    <div class="card-header ">




        @if ($active_shift_count > 0)
            @can('اضافة تحصيل جديد')
                <h4 class="text-center mt-5 mb-4" style="background-color: #199ee1;padding:5px;color:#fff">اضافة تحصيل جديد
                </h4>
            @endcan


            <form class="form form-horizontal" wire:submit.prevent='submit' style="background-color: #7a7a7a;color:#fff">
                <div class="modal-body">
                    <div class="row">


                        {{-- اسم الخزنة treasury_id  --}}
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>اسم الخزنة</label>
                                <select wire:model="treasury_id" wire:loading.attr="disabled" class="form-control"
                                    wire:target="treasury_id" style="background-color: #fff;">
                                    @if (isset($treasry))
                                        <option value="{{ $treasry->id }}" selected>{{ $treasry->name }}</option>
                                    @endif
                                </select>

                                @include('backEnd.error', ['property' => 'treasury_id'])

                            </div>
                        </div>


                        {{-- تاريخ الحركة  move_date  --}}
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>تاريخ الحركة </label>
                                <input type="date" class="form-control" wire:model="move_date">
                                @include('backEnd.error', ['property' => 'move_date'])
                            </div>
                        </div>


                        {{-- نوع الفاتورة المحصل لاجلها invoice_type_accounts  --}}
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>نوع الفاتورة المحصل لاجلها </label>

                                <select wire:model="invoice_type_accounts"
                                    id="invoice_type_accounts" class="form-control"
                                    wire:target="invoice_type_accounts" style="background-color: #fff;">
                                    <option value="">اخري</option>
                                    <option value="purchases">مشتريات</option>
                                    <option value="sales">مبيعات</option>

                                </select>

                                @include('backEnd.error', ['property' => 'account_id'])
                            </div>
                        </div>



                        {{-- اسم الحساب المحصل منه account_id  --}}
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>اسم الحساب المحصل منه </label>

                                <select wire:model="account_id" wire:change="accountId($event.target.value)"
                                    id="account_id" wire:loading.attr="disabled" class="form-control select2"
                                    wire:target="account_id" style="background-color: #fff;">
                                    <option selected>اسم الحساب المحصل منه </option>
                                    @if (isset($accounts))
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->account_number }}">{{ $account->name }} <span
                                                    style="color: rgb(20, 111, 196)">({{ $account->accountType->name ?? '-' }})</span>
                                            </option>
                                        @endforeach
                                    @endif
                                </select>

                                @include('backEnd.error', ['property' => 'account_id'])
                            </div>
                        </div>


                           {{-- رصيد الحساب المحصل منه account_balance  --}}
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>رصيد الحساب المحصل منه </label>

                                <input type="number" class="form-control" wire:model="account_balance" readonly>

                                @include('backEnd.error', ['property' => 'account_id'])
                            </div>
                        </div>

                        {{-- نوع الحركة moveType_id  --}}
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>نوع الحركة </label>

                                <select wire:model="moveType_id" id="move_type" wire:loading.attr="disabled"
                                    class="form-control" wire:target="moveType_id"
                                    style="background-color: #fff;">
                                    <option selected>نوع الحركة </option>
                                    @if (isset($moveTypes))
                                        @foreach ($moveTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                @include('backEnd.error', ['property' => 'moveType_id'])
                            </div>
                        </div>


                        {{-- قيمة المبلغ المحصل  account_id  --}}
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>قيمة المبلغ المحصل </label>
                                <input type="number" class="form-control" placeholder=" ادخل قيمة المبلغ المحصل "
                                    wire:model="cash_amount">
                                @include('backEnd.error', ['property' => 'cash_amount'])
                            </div>
                        </div>



                        {{-- اجمالي المبلغ بالخزينة  treasury_balance  --}}
                        <div class="col-sm-6 mb-4" wire:poll.5s>
                            <div class="form-group">
                                <label>اجمالي البالغ في الخزينة </label>
                                <input type="number" class="form-control" wire:model="treasury_balance" readonly>
                            </div>
                        </div>



                        {{--    notes البيان  --}}
                        <div class="col-sm-12 mb-4">
                            <div class="form-group">
                                <label>البيان </label>
                                <textarea cols="30" rows="5" class="form-control" wire:model="notes" placeholder="تحصيل مبلغ نظير "> </textarea>
                                @include('backEnd.error', ['property' => 'notes'])

                            </div>
                        </div>



                    </div>
                </div>
                <div class="modal-footer">
                    @can('اضافة تحصيل جديد')
                        <button type="submit" type="button"
                            class="btn btn-success waves-effect waves-float waves-light">تحصيل</button>
                    @endcan

                </div>
            </form>
        @else
            <h4 class="text-center mt-5 mb-4" style="background-color: #0f3d53;padding:5px;color:#fff">لا يوجد شيفتات
                مفتوحة لهذا المستخدم </h4>
        @endif



        <h4 class="text-center mt-5 mb-4" style="background-color: #199ee1;padding:5px;color:#fff">ايصالات تحصيل النقدية
        </h4>
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control w-25"
            placeholder="بحث باسم المرسل او رقم الحركة">
    </div>



    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
        <thead>
            <tr>
                <th>كود الحركة</th>
                <th>اسم الخزنة</th>
                <th>نوع الحركة</th>
                <th>المبلغ</th>
                <th>البيان</th>
                <th> المرسل</th>
                <th>المستلم</th>
                <th>اخر تحديث</th>
                <th>الاجراءات</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($data))
                @php
                    $x = 1;
                @endphp
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->auto_serial }}</td>
                        <td>{{ $item->treasurie->name }}</td>
                        <td>{{ optional($item->move_type)->inScrean() }}</td>
                        <td>{{ $item->cash_amount }} جنيه</td>
                        <td>{{ $item->notes }}</td>
                        <td>{{ $item->account->name }}</td>
                        <td>{{ auth()->user()->name }}</td>
                        <td> {{ last_update($item) }}</td>

                        <td>
                            <div class="d-flex align-items-center">
                                @can('تعديل حركة تحصيل ')
                                    <a class="btn btn-primary waves-effect waves-float waves-light" title="Edit"
                                        href="#"
                                        wire:click.prevent="$dispatch('storesUpdate', {id: {{ $item->id }}})">
                                        نعديل
                                    </a>
                                @endcan

                                @can('حذف حركة تحصيل')
                                    {{-- <a class="btn btn-danger waves-effect waves-float waves-light mr-3" href="#"
                                        data-id="{{ $item->id }}"
                                        wire:click.prevent="$dispatch('treasuriesTransactionDelete', {id: {{ $item->id }}})"
                                        ti`le="Delete">
                                        حذف
                                    </a> --}}
                                @endcan



                                <!-- {{-- <a class="btn btn-warning waves-effect waves-float waves-light" title="Show" href="#" wire:click.prevent="$dispatch('treasuriesShow', {id: {{ $item->id }}})">
                                    المزيد
                                </a> --}} -->

                                @can('تفاصيل حركة تحصيل')
                                    <a class="btn btn-warning waves-effect waves-float waves-light" title="Show"
                                        wire:navigate href="{{ route('treasuries.show', $item->id) }}"
                                        wire:click.prevent="$dispatch('treasuriesShow', {id: {{ $item->id }}})">
                                        المزيد
                                    </a>
                                @endcan


                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <td colspan="6">
                    <div class="text-danger text-center">لا يوجد بيانات</div>
                </td>
            @endif
        </tbody>
    </table>
    <div class=" mt-2">
        {{ $data->links() }}
    </div>
</div>
