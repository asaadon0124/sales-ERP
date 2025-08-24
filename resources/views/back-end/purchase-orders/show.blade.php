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
                                    <td style="width: 30%;background-color:#d6cacae6;">رقم الفاتورة اليدوي</td>
                                    <td>{{ $order->auto_serial }}</td>
                                </tr>


                                {{-- order_number رقم الفاتورة اليدوي المسجل علي الفاتورة  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">حالة الشركة</td>
                                    <td>{{ $order->order_number }}</td>
                                </tr>

                                {{-- order_date تاريخ الفاتورة  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">تاريخ الفاتورة</td>
                                    <td>{{ $order->order_date }}</td>
                                </tr>



                                {{-- supplier_code اسم المورد  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">اسم المورد</td>
                                    <td>{{ $order->supplier->name }}</td>
                                </tr>



                                {{-- supplier_code كود المورد  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">كود المورد</td>
                                    <td>{{ $order->supplier->supplier_code }}</td>
                                </tr>




                                {{-- store_id اسم المخزن المستلم  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">اسم المخزن المستلم</td>
                                    <td>{{ $order->store->name }}</td>
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
                                    <td>{{ $order->total_cost_before_all }}</td>
                                </tr>


                                 {{-- discount_type نوع الخصم  --}}
                                 <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">نوع الخصم</td>
                                    <td>{{ $order->DiscountType() }}</td>
                                </tr>



                                {{-- discount_type نوع الخصم  --}}
                                @if ($order->discount_type == 0)
                                    <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">قيمة الخصم</td>
                                        <td>{{ $order->discount_amount }}</td>
                                    </tr>
                                @else
                                    <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">قيمة نسبة الخصم</td>
                                        <td>{{ $order->discount_percent }}</td>
                                    </tr>
                                @endif


                                {{-- discount_type نسبة الضريبة  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">نسبة الضريبة</td>
                                    <td>{{ $order->tax_percent }}</td>
                                </tr>



                                 {{-- discount_type قيمة الضريبة  --}}
                                 <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">قيمة الضريبة</td>
                                    <td>{{ $order->tax_value }}</td>
                                </tr>


                                {{-- total_cost الاجمالي بعد الخصم و الضرائب  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">الاجمالي بعد الخصم و الضرائب</td>
                                    <td>{{ $order->total_cost }}</td>
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



                    <h4 class="text-center mb-5 mt-5">اصناف فاتورة المشتريات رقم {{ $order->auto_serial }}</h4>


                    <div class="card-header d-flex justify-content-between">
                        @if ($order->created_by == auth()->user()->id && $order->approve == 0)
                             <button class="btn btn-primary"
                            wire:click.prevent="$dispatch('orderDetailesCreate',{id: {{ $orderId }}})">اضافة صنف
                            جديد</button>
                        @endif


                        <input type="text" wire:model.live="search" class="form-control w-25" placeholder="بحث">
                         <a href="{{ route('purchaseOrders.index') }}" class="btn btn-danger">رجوع</a>
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
                                            @if ($order->created_by == auth()->user()->id && $order->approve == 0)
                                                <a class="btn btn-primary waves-effect waves-float waves-light" title="Edit" href="#" wire:click.prevent="$dispatch('purchaseOrderDetailesUpdate', {id: {{ $iteem->id }}})">
                                                    نعديل
                                                </a>
                                            @endif

                                            <a class="btn btn-info waves-effect waves-float waves-light" title="Edit" href="#" wire:click.prevent="$dispatch('purchaseOrderDetailesShow', {id: {{ $iteem->id }}})">
                                                عرض
                                            </a>

                                           @if ($order->created_by == auth()->user()->id && $order->approve == 0)
                                                <a class="btn btn-danger waves-effect waves-float waves-light" title="Edit" href="#" wire:click.prevent="$dispatch('purchaseOrderDetailesDelete', {id: {{ $iteem->id }}})">
                                                    حذف
                                                </a>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class=" mt-2">
                        {{-- {{ $data->links() }} --}}
                    </div>
            </div>



        </div>
    </div>
</div>
</div>
