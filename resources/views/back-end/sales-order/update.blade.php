<div class="modal fade text-start modal-primary" id="updateModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">تعديل الفاتورة</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent='submit' class="form-horizontal">
                <div class="modal-body">
                    <div class="row g-3">
                        {{-- تاريخ الفاتورة --}}
                        <div class="col-md-3">
                            <label>تاريخ الفاتورة</label>
                            <input type="date" class="form-control" wire:model="order_date">
                            @include('backEnd.error', ['property' => 'order_date'])
                        </div>

                        {{-- فئة الفاتورة --}}
                        <div class="col-md-3">
                            <label>فئة الفاتورة</label>
                            <select wire:model="matrial_types_id" class="form-control">
                                <option value="">فئة الفاتورة</option>
                                @foreach ($matrial_types ?? [] as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @include('backEnd.error', ['property' => 'matrial_types_id'])
                        </div>

                        {{-- اسم العميل --}}
                        <div class="col-md-3">
                            <label>اسم العميل</label>


                            <select wire:change="customerChanged($event.target.value)" wire:model="customer_code" class="form-control">
                                <option value="">اسم العميل</option>
                                @foreach ($customers ?? [] as $customer)
                                    <option value="{{ $customer->customer_code }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            @include('backEnd.error', ['property' => 'customer_code'])
                        </div>

                        {{-- رصيد العميل --}}

                        <div class="col-md-3">
                            <label>رصيد العميل</label>
                            <input type="text" class="form-control" wire:model="customer_balance" readonly>
                        </div>


                        {{-- اسم المندوب --}}
                        <div class="col-md-3">
                            <label>اسم المندوب</label>
                            <select wire:change="servantChanged($event.target.value)" wire:model="servant_code" class="form-control">
                                <option selected>اسم المندوب</option>
                                @foreach ($servants ?? [] as $servant)
                                    <option value="{{ $servant->servant_code }}">{{ $servant->name }}</option>
                                @endforeach
                            </select>
                            @include('backEnd.error', ['property' => 'servant_code'])
                        </div>

                        {{-- رصيد المندوب --}}

                        <div class="col-md-3">
                            <label>رصيد المندوب</label>
                            <input type="text" class="form-control" wire:model="servant_balance" readonly>
                        </div>


                        {{-- الملاحظات --}}
                        <div class="col-12">
                            <label>الملاحظات</label>
                            <textarea wire:model="notes" class="form-control" rows="2"></textarea>
                            @include('backEnd.error', ['property' => 'notes'])
                        </div>
                    </div>

                    <hr class="my-4 border-top border-3 border-dark">
                    <h5 class="text-primary mb-3">تفاصيل الحسابات</h5>

                    <div class="row g-3">
                        {{-- بيانات الخزنة --}}
                        <div class="col-md-3">
                            <label>بيانات الخزنة</label>
                            <input type="text" class="form-control" wire:model="treasury_balance" readonly>
                        </div>

                        {{-- اجمالي قبل الخصم والضريبة --}}
                        <div class="col-md-3">
                            <label>إجمالي قبل الخصم والضريبة</label>
                            <input type="number" class="form-control" wire:model="total_cost_before_all" readonly>
                        </div>

                        {{-- اجمالي قبل الخصم --}}
                        <div class="col-md-3">
                            <label>إجمالي قبل الخصم</label>
                            <input type="number" class="form-control" value="{{ number_format($total_before_discount, 2, '.', '') }}" readonly>
                        </div>

                        {{-- الاجمالي النهائي --}}
                        <div class="col-md-3">
                            <label>الإجمالي بعد الخصم والضريبة</label>
                            <input type="number" class="form-control" wire:model="total_cost" readonly>
                        </div>

                        {{-- نوع الفاتورة --}}
                        <div class="col-md-3">
                            <label>نوع الفاتورة</label>
                            <select wire:model="invoice_type" class="form-control" wire:change="change_invoice_type($event.target.value)">
                                <option value="">نوع الفاتورة</option>
                                <option value="0">كاش</option>
                                <option value="1">آجل</option>
                            </select>
                        </div>

                        @if ($total_cost_before_all !== '' && $total_cost_before_all !== null)
                            {{-- نوع الخصم --}}
                            <div class="col-md-3">
                                <label>نوع الخصم</label>
                                <select wire:model="discount_type" class="form-control" wire:change="change_discount_type($event.target.value)">
                                    <option value="">اختر نوع الخصم</option>
                                    <option value="0">قيمة</option>
                                    <option value="1">نسبة</option>
                                </select>
                            </div>

                            {{-- نسبة الضريبة --}}
                            <div class="col-md-3">
                                <label>نسبة الضريبة</label>
                                <input type="number" class="form-control" wire:model="tax_percent" wire:change="change_tax_percent($event.target.value)">
                            </div>

                            {{-- قيمة الضريبة --}}
                            <div class="col-md-3">
                                <label>قيمة الضريبة</label>
                                <input type="number" class="form-control" wire:model="tax_value" readonly>
                            </div>

                            {{-- نسبة الخصم --}}
                            @if ($discount_type === '1')
                            <div class="col-md-3">
                                <label>نسبة الخصم</label>
                                <input type="number" class="form-control" wire:model="discount_percent" step="0.01" wire:change="change_discount_percent($event.target.value)">
                            </div>
                            @endif

                            {{-- قيمة الخصم --}}
                            @if ($discount_type === '0')
                            <div class="col-md-3">
                                <label>قيمة الخصم</label>
                                <input type="number" class="form-control" wire:model="discount_amount" step="0.01" wire:change="change_discount_amount($event.target.value)">
                            </div>
                            @endif

                            {{-- المبلغ المدفوع / المتبقي --}}
                            @if ($invoice_type === '0')
                                <div class="col-md-6">
                                    <label>المبلغ المدفوع الآن للمورد</label>
                                    <input type="number" class="form-control" wire:model="paid" readonly>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <label>المبلغ المدفوع الآن للمورد</label>
                                    <input type="number" class="form-control" wire:model="paid" wire:change="change_paid($event.target.value)">
                                </div>
                                <div class="col-md-6">
                                    <label>المبلغ المتبقي للمورد</label>
                                    <input type="number" class="form-control" wire:model="unpaid" wire:change="change_unpaid($event.target.value)">
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect">
                        تعديل
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        إغلاق
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
