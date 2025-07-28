<div class="modal fade text-start modal-primary" id="createModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">عرض الفاتورة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>

            <div class="invoice p-4" id="print-area">
                {{-- عنوان الفاتورة --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <h4>
                            @if ($admin_sittings)
                                <h4>
                                    <img class="img-responsive mb-1"
                                        src="{{ asset('/assets/backEnd/images/' . $admin_sittings->photo) }}"
                                        style="height: 50px; width: 50px;border-radius:50%">
                                    {{ $admin_sittings->system_name }}
                                </h4>
                            @endif
                            @if (!empty($order))
                                <small class="float-end">التاريخ: {{ $order->created_at }}</small>
                            @endif
                        </h4>
                    </div>
                </div>

                @if ($order)
                    {{-- بيانات من وإلى --}}
                    <div class="row invoice-info mb-4">
                        <div class="col-sm-4">
                            <strong>من</strong>
                            <address>
                                {{ $order->customer != '' ? $order->customer->name : $order->supplier->name}}<br>
                                {{ $order->customer != '' ? $order->customer->address : $order->supplier->address}}<br>
                                رقم الحساب: {{ $order->customer != '' ? $order->customer->account_number : $order->supplier->account_number}}<br>
                                الهاتف: {{ $order->customer != '' ? $order->customer->address : $order->supplier->address}}
                            </address>
                        </div>

                        <div class="col-sm-4">
                            <strong>إلى</strong>
                            <address>
                                العميل: {{ $admin_sittings->system_name }}<br>
                                {{ $admin_sittings->address }}<br>
                                الهاتف: {{ $admin_sittings->phone }}<br>
                                كود الشركة: {{ $admin_sittings->company_code }}
                            </address>
                        </div>

                        <div class="col-sm-4">
                            <b>رقم الفاتورة:</b> {{ $order->auto_serial }}<br>
                            <b>رقم الطلب:</b> {{ $order->order_number ? $order->order_number : '' }}<br>
                            <b>نوع الفاتورة :</b> {{ $order->InvoiceType() }}<br>
                            {{-- <b>الحساب:</b> 968-34567 --}}
                        </div>
                    </div>

                    {{-- جدول الأصناف --}}
                    <div class="row mb-3">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-info text-white">
                                    <tr>
                                        <th>اسم الصنف</th>
                                        <th>فئة الصنف</th>
                                        <th>وحدة الصنف</th>
                                        <th>الكمية</th>
                                        <th>السعر</th>
                                        <th>الاجمالي</th>
                                        <th>تاريخ الانتهاء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->order_detailes as $item)
                                        <tr>
                                            <td>{{$item->item->name}}</td>
                                            <td>{{$item->item->itemCategory->name}}</td>
                                            <td>{{$item->item_unit->name}}</td>
                                            <td>{{$item->qty}}</td>
                                            <td>{{$item->unit_price}}</td>
                                            <td>{{$item->total}}</td>
                                            <td>{{$item->expire_date}}</td>
                                        </tr>
                                    @endforeach

                                    <!-- أضف مزيد من الصفوف هنا حسب الحاجة -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- الإجماليات --}}
                    <div class="row">
                        <div class="col-md-6">
                            <p class="lead">ملاحظات:</p>
                            <p class="text-muted">
                                تم تسليم الطلب بنجاح، شكراً لتعاملكم معنا.
                            </p>
                        </div>

                        <div class="col-md-6">
                            <p class="lead">المبلغ المستحق</p>
                            <table class="table">
                                <tr>
                                    <th>اجمالي الفاتورة قبل الخصم و الضريبة :</th>
                                    <td>{{ number_format($order->total_cost_before_all) }} جنيه</td>
                                </tr>
                                <tr>
                                    <th>الضريبة ({{ $order->tax_percent }}%):</th>
                                    <td>{{ number_format($order->tax_value) }} جنيه</td>
                                </tr>
                                <tr>
                                    <th>اجمالي الفاتورة بعد الضريبة :</th>
                                    <td>{{ number_format($order->total_before_discount) }} جنيه</td>
                                </tr>

                                <tr>
                                    <th>الخصم ({{ $order->discount_percent }}%):</th>
                                    <td>{{ number_format($order->discount_amount) }} جنيه</td>
                                </tr>
                                <tr>
                                    <th>الإجمالي:</th>
                                    <td><strong>{{ number_format($order->total_cost) }} جنيه</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- الأزرار --}}
                    <div class="row mt-3 no-print">
                        <div class="col-12 text-end">
                            <button class="btn btn-secondary me-2" onclick="window.print()"><i class="fas fa-print"></i> طباعة</button>
                            @if ($invoiceType)
                                <a href="{{ route('invoice.download', ['id' => $order->auto_serial, 'type' => $invoiceType]) }}" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-download"></i> تحميل PDF
                                </a>
                            @endif

                            {{-- <button class="btn btn-primary"><i class="fas fa-download"></i> تحميل PDF</button> --}}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
