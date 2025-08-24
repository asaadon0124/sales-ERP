<div>
    <div class="modal fade text-start modal-primary" id="aproveModal" tabindex="-1" aria-hidden="true"
        style="display: none;" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel110">مراجعة الحساب </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form form-horizontal" wire:submit.prevent='submit'>
                    <div class="modal-body">
                        <div class="row">

                            {{--  اسم الخزنة  --}}
                            <div class="col-sm-4 mb-4">
                                <div class="form-group">
                                    <label>اسم الخزنة تحت المراجعة</label>
                                    <input type="text" class="form-control" value="{{ optional($treasury)->name }}"
                                        readonly>
                                </div>
                            </div>

                            @if (!empty($Active_shift))
                                <div class="col-sm-4 mb-4">
                                    <div class="form-group">
                                        <label> نوع الخزنة المستلمة </label>
                                        <input type="text"
                                            value="{{ $is_same_treasury ? 'نفس الخزنة' : 'خزنة أخرى' }}"
                                            class="form-control" readonly>
                                        @include('backEnd.error', ['property' => 'recive_type'])
                                    </div>
                                </div>

                                @if (!$is_same_treasury)
                                    {{-- اسم الخزنة المستلمة --}}
                                    <div class="col-sm-4 mb-4">
                                        <div class="form-group">
                                            <label> اسم الخزنة المستلمة </label>
                                            <input type="text" value="{{ $Active_shift->treasury->name }}"
                                                class="form-control" readonly>
                                            @include('backEnd.error', [
                                                'property' => 'delevered_to_treasury_id',
                                            ])
                                        </div>
                                    </div>
                                @endif
                            @endif







                            {{-- رصيد الشيفت المغلق --}}
                            <div class="col-sm-6 mb-4">
                                <div class="form-group">
                                    <label>رصيد الشيفت المنتهي</label>

                                    <input type="text" class="form-control"
                                        value="{{ number_format($shift_balance, 2) }}" readonly>
                                </div>
                            </div>



                               {{--  رصيد الشيفت  الحالي--}}
                            <div class="col-sm-6 mb-4">
                                <div class="form-group">
                                    <label>رصيد الشيفت الحالي</label>

                                    <input type="text" class="form-control"
                                        value="{{ number_format($current_shift_balance, 2) }}" readonly>
                                </div>
                            </div>


                            {{-- اسم حركة النقدية  --}}
                            {{-- <div class="col-sm-4 mb-4">
                                <div class="form-group">
                                    <label>اسم حركة النقدية </label>

                                    <select wire:model="moveType_id" wire:loading.attr="disabled" class="form-control"
                                        wire:target="moveType_id">
                                        <option selected>اسم حركة النقدية </option>
                                        @if (!empty($moveTypes))
                                            @foreach ($moveTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @include('backEnd.error', ['property' => 'moveType_id'])
                                </div>
                            </div> --}}









                            {{-- حالة التحصيل المستلم --}}
                            <div class="col-sm-4 mb-4">
                                <div class="form-group">
                                    <label>حالة التحصيل المستلم</label>
                                    <input type="text" class="form-control"
                                        value="{{ $this->getCashStatusLabel($cash_status) }}" readonly>
                                </div>
                            </div>



                            {{--  التحصيل المستلم من المستخدم  --}}
                            <div class="col-sm-4 mb-4">
                                <div class="form-group">
                                    <label> التحصيل المستلم من المستخدم</label>
                                    <input type="number" class="form-control"
                                        placeholder="ادخل قيمة التحصيل المستلم فعلا من المستخدم"
                                        wire:model="cash_actually_delivered" wire:change="paid($event.target.value)">
                                    @include('backEnd.error', ['property' => 'cash_actually_delivered'])
                                </div>
                            </div>


                            {{--  قيمة العجز او الزيادة في التحصيل  --}}
                            <div class="col-sm-4 mb-4">
                                <div class="form-group">
                                    <label> قيمة العجز او الزيادة في التحصيل </label>
                                    <input type="number" class="form-control"
                                        placeholder="ادخل قيمة  العجز او الزيادة في التحصيل "
                                        wire:model="cash_status_value"
                                        wire:change="paid_difference($event.target.value)">
                                    @include('backEnd.error', ['property' => 'cash_status_value'])
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

</div>
