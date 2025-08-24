<div>
    <div class="card">
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">

                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div id="example1_filter" class="dataTables_filter">

                            {{-- <a href="{{ route('adminSittings.edit',$sitting->id) }}" class="btn btn-info mb-5">تعديل</a> --}}
                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-sm-12">
                        <table id="example1" class="table table-bordered table-striped dataTable" role="grid"
                            aria-describedby="example1_info">
                            @if (isset($order))
                                {{-- auto_serial  الكود الالي للفاتورة --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">رقم الفاتورة </td>
                                    <td>{{ $order->auto_serial }}</td>
                                </tr>


                                {{-- items_type نوع البيع داخل الفاتورة ثابت ولا متغير  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">نوع البيع داخل الفاتورة</td>
                                    <td>{{ $order->items_type() }}</td>
                                </tr>


                                 {{-- items_type نوع البيع داخل الفاتورة جملة ولا قطاعي ولا نص جملة  --}}
                                @if ($order->items_type == '0')
                                    <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">نوع البيع داخل الفاتورة</td>
                                        <td>{{ $order->sales_item_type() }}</td>
                                    </tr>
                                @endif

                                {{-- order_date تاريخ الفاتورة  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">تاريخ الفاتورة</td>
                                    <td>{{ $order->order_date }}</td>
                                </tr>



                                {{-- supplier_code اسم العميل  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">اسم العميل</td>
                                    <td>{{ $order->customer->name }}</td>
                                </tr>



                                {{-- supplier_code كود العميل  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">كود العميل</td>
                                    <td>{{ $order->customer->customer_code }}</td>
                                </tr>




                                {{-- store_id رصيد حساب العميل  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">رصيد حساب العميل</td>
                                    @if ($order->customer->current_balance > 0)
                                        <td style="color: rgb(53, 44, 222)">
                                            له {{ number_format(abs($order->customer->current_balance), 2) }} جنيه
                                        </td>
                                    @elseif($order->customer->current_balance < 0)

                                         <td style="color: rgb(220, 25, 25)">
                                            عليه {{ number_format(abs($order->customer->current_balance), 2) }} جنيه
                                        </td>
                                    @else
                                        <td style="color: rgb(43, 204, 43)">
                                            {{ $order->customer->current_balance }}
                                        </td>
                                    @endif
                                </tr>






                                {{-- servant name اسم المندوب  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">اسم المندوب</td>
                                    <td>{{ $order->servant->name }}</td>
                                </tr>



                                {{-- servant_code كود المندوب  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">كود المندوب</td>
                                    <td>{{ $order->servant->servant_code }}</td>
                                </tr>




                                {{-- servant current balance رصيد حساب المندوب  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">رصيد حساب المندوب</td>
                                    @if ($order->servant->current_balance > 0)
                                        <td style="color: rgb(53, 44, 222)">
                                            له {{ number_format(abs($order->servant->current_balance), 2) }} جنيه
                                        </td>
                                    @elseif($order->servant->current_balance < 0)

                                         <td style="color: rgb(220, 25, 25)">
                                            عليه {{ number_format(abs($order->servant->current_balance), 2) }} جنيه
                                        </td>
                                    @else
                                        <td style="color: rgb(43, 204, 43)">
                                            {{ $order->servant->current_balance }}
                                        </td>
                                    @endif
                                </tr>




                                {{-- InvoiceType نوع الفاتورة  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">نوع الفاتورة</td>
                                    <td>{{ $order->invoiceType() }}</td>
                                </tr>




                                {{-- total_cost_before_all اجمالي الفاتورة قبل الخصم و الضرائب  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">اجمالي الفاتورة قبل الخصم و
                                        الضرائب</td>
                                    <td>{{ number_format($order->total_cost_before_all) }} جنيه</td>
                                </tr>


                                 {{-- discount_type نوع الخصم  --}}
                                 <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">نوع الخصم</td>
                                    <td>{{ $order->DiscountType() }}</td>
                                </tr>



                                {{-- discount_value قيمة الخصم  --}}
                                @if ($order->discount_type == 0)
                                    <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">قيمة الخصم</td>
                                        <td>{{ number_format($order->discount_amount) }} جنيه</td>
                                    </tr>
                                @else
                                    <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">قيمة نسبة الخصم</td>
                                        <td>{{ $order->discount_percent }} %</td>
                                    </tr>
                                @endif


                                {{-- discount_type نسبة الضريبة  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">نسبة الضريبة</td>
                                    <td>{{ $order->tax_percent }} %</td>
                                </tr>



                                 {{-- discount_type قيمة الضريبة  --}}
                                 <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">قيمة الضريبة</td>
                                    <td>{{ number_format($order->tax_value) }} جنيه</td>
                                </tr>


                                {{-- total_cost الاجمالي بعد الخصم و الضرائب  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">الاجمالي بعد الخصم و الضرائب</td>
                                    <td>{{ number_format($order->total_cost) }} جنيه</td>
                                </tr>



                                {{-- paid المبلغ المدفوع  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">المبلغ المدفوع</td>
                                    <td>{{ number_format($order->paid) }} جنيه</td>
                                </tr>

                                {{-- paid المبلغ المتبقي  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">المبلغ المتبقي</td>
                                    <td>{{ number_format($order->unpaid) }} جنيه</td>
                                </tr>



                                {{-- approve حالة الفاتورة  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">حالة الفاتورة</td>
                                    <td>{{ $order->approval() }}</td>
                                </tr>



                                {{-- notes الملاحظات  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">الملاحظات</td>
                                    <td>{{ $order->notes }}</td>
                                </tr>




                                {{-- COMPANY CODE  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">كود الشركة</td>
                                    <td>{{ $order->company_code }}</td>
                                </tr>


                                {{-- LAST UPDATE DATE  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;"> تاريخ اخر تحديث</td>
                                    <td>
                                        {{ last_update($order) }} بواسطة {{ $order->adminUpdate->name }}
                                    </td>
                                </tr>
                            @else
                                <div class="alert alert-danger text-center mt-4">
                                    لا يوجد بيانات
                                </div>
                            @endif

                        </table>
                    </div>
                </div>



                    <h4 class="text-center mb-5 mt-5">اصناف فاتورة المبيعات رقم {{ $order->auto_serial }}</h4>


                    <div class="card-header d-flex justify-content-between">
                            @can('اضافة صنف جديد لفاتورة المبيعات')
                                {{-- لو العميل او المندوب تم حذفهم لا يمكن اضافة صنف جديد للفاتورة --}}
                                {{-- @if (!empty($order->customer)  && !empty($order->servant))
                                     <button class="btn btn-primary"
                                    wire:click.prevent="$dispatch('orderDetailesCreate',{id: {{ $orderId }}})">اضافة صنف
                                    جديد</button>
                                @endif --}}

                            @endcan




                        <!-- <input type="text" wire:model.live="search" class="form-control w-25 ms-2" placeholder="بحث"> -->
                        <input type="text" wire:model.live="search" class="form-control w-25" placeholder="بحث">
                    </div>

                    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم الصنف</th>
                                <th>وحدة الصنف</th>
                                <th>سعر الصنف</th>
                                <th>الكمية</th>
                                <th>الاجمالي</th>
                                <th>تاريخ الانتهاء</th>
                                <th>الاجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $x = 1;
                            @endphp
                            @if (isset($data))
                                @foreach ($data as $iteem)
                                    <tr>
                                        <td>{{ $x++ }}</td>
                                        <td>{{ $iteem->item->name }}</td>
                                        <td>{{ $iteem->item_unit->name }}</td>
                                        <td>{{ $iteem->unit_price }}</td>
                                        <td>{{ $iteem->qty }}</td>
                                        <td>{{ $iteem->total }}</td>
                                        <td>{{ $iteem->expire_date }}</td>
                                        <td>
                                            {{-- @if ($order->created_by == auth()->user()->id && $order->approve == 0)
                                                <a class="btn btn-primary waves-effect waves-float waves-light" title="Edit" href="#" wire:click.prevent="$dispatch('purchaseOrderDetailesUpdate', {id: {{ $iteem->id }}})">
                                                    نعديل
                                                </a>
                                            @endif --}}

                                            <a class="btn btn-info waves-effect waves-float waves-light" title="Edit" href="#" wire:click.prevent="$dispatch('purchaseOrderDetailesShow', {id: {{ $iteem->id }}})">
                                                عرض
                                            </a>

                                            {{-- لو العميل او المندوب تم حذفهم لا يمكن اضافة صنف جديد للفاتورة --}}
                                            {{-- @if (!empty($order->customer)  && !empty($order->servant))
                                                <a class="btn btn-danger waves-effect waves-float waves-light" title="Delete" href="#" wire:click.prevent="$dispatch('salesOrderDetailesDelete', {id: {{ $iteem->id }}})">
                                                    حذف
                                                </a>
                                            @endif --}}
                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class=" mt-2">
                    @can('حذف صنف من فاتورة المبيعات')
                        <p style="color: rgb(153, 93, 51); font-weight: bold"><span style="color: red">ملحوظة</span> عند الحذف  سيتم حذف الصنف فقط و و لا يتم التاثير علي حسابات العملاء او المناديب <br> و عند حذف كل الاصناف داخل الفاتورة سيتم حذف الفاتورة و ايصال التحصيل الهاص بها فقط  </p>
                    @endcan
                    {{-- {{ $data->links() }} --}}
    </div>
            </div>



        </div>
    </div>
</div>
</div>
