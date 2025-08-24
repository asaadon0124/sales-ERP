<div class="table-responsive" wire:ignore.self>

    <div class="card-header bg-light border-bottom">
        <form class="form form-horizontal {{ !empty($data) && !empty($data[0]->name) ? 'd-none' : '' }}"
            wire:submit.prevent='submit'>
            <div class="modal-body">
                <div class="row align-items-end">


                    {{-- نوع الجرد --}}
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="font-weight-bold">نوع الجرد</label>
                            <select wire:model="rebort_type" wire:loading.attr="disabled" class="form-control"
                                wire:target="rebort_type" wire:change="rebortTypeChange($event.target.value)">
                                <option value="">اختار نوع الجرد</option>
                                <option value="0">نوع الصنف</option>
                                <option value="1">فئة الصنف</option>
                                <option value="2">اسم الصنف</option>

                            </select>
                            @include('backEnd.error', ['property' => 'rebort_type'])
                        </div>
                    </div>


                    {{-- نوع الصنف --}}
                    <div class="col-md-3 mb-3 {{ $rebort_type == '0' ? '' : 'd-none' }}">
                        <div class="form-group ">
                            <label class="font-weight-bold">نوع الصنف</label>
                            <select wire:model="item_type" wire:loading.attr="disabled" class="form-control"
                                wire:target="item_type">
                                <option value="">اختار نوع الصنف</option>
                                <option value="0">مخزني</option>
                                <option value="1">استهلاكي</option>
                                <option value="2">عهدة</option>

                            </select>
                            @include('backEnd.error', ['property' => 'item_type'])
                        </div>
                    </div>


                    {{-- فئة الصنف --}}
                    <div class="col-md-3 mb-3 {{ $rebort_type == '1' ? '' : 'd-none' }}">
                        <div class="form-group ">
                            <label class="font-weight-bold">فئة الصنف</label>
                            <select wire:model="item_category_id" wire:loading.attr="disabled" class="form-control"
                                wire:target="item_category_id">
                                <option value="">اختار فئة الصنف</option>
                                @if (!empty($item_categories))
                                    @foreach ($item_categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @include('backEnd.error', ['property' => 'item_category_id'])
                        </div>
                    </div>


                    {{-- اسم الصنف --}}
                    <div class="col-md-3 mb-3 {{ $rebort_type == '2' ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label class="font-weight-bold">اسم الصنف</label>
                            <select wire:model="item_name" wire:loading.attr="disabled"
                                class="form-control"wire:change="ItemNameChange($event.target.value)"
                                wire:target="item_name">
                                <option value="">اختار اسم الصنف</option>
                                @if (!empty($items))
                                    @foreach ($items as $item)
                                        <option value="{{ $item->item_code }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @include('backEnd.error', ['property' => 'item_name'])
                        </div>
                    </div>


                    {{-- اسم المخزن --}}
                    <div class="col-md-3 mb-3 {{ $rebort_type == '2' ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label class="font-weight-bold">اسم المخزن</label>
                            <select wire:model="store_name" wire:loading.attr="disabled" class="form-control"
                                wire:target="store_name">
                                <option value="">كل المخازن</option>
                                @if (!empty($stores))
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @include('backEnd.error', ['property' => 'store_name'])
                        </div>
                    </div>



                    {{-- تاريخ البداية --}}
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="font-weight-bold">تاريخ البداية</label>
                            <input type="date" class="form-control" wire:model="start_date">
                            @include('backEnd.error', ['property' => 'start_date'])
                        </div>
                    </div>



                    {{-- تاريخ النهاية --}}
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="font-weight-bold">تاريخ النهاية</label>
                            <input type="date" class="form-control" wire:model="end_date">
                            @include('backEnd.error', ['property' => 'end_date'])
                        </div>
                    </div>



                    {{-- ترتيب الاصناف --}}
                    <div class="col-md-3 mb-3 {{ $rebort_type == '0' || $rebort_type == '1' ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label class="font-weight-bold">ترتيب الاصناف حسب</label>
                            <select wire:model="item_sort" wire:loading.attr="disabled" class="form-control"
                                wire:target="item_sort">
                                <option value="">اختار ترتيب الاصناف حسب</option>
                                <option value="0">الكميات الاقل</option>
                                <option value="1">الكميات الاكثر </option>
                                <option value="2">تاريخ الانتهاء الاقرب</option>
                                <option value="3">تاريخ الانتهاء الابعد</option>
                                <option value="4">الاكثر مبيعا</option>

                            </select>
                            @include('backEnd.error', ['property' => 'item_sort'])
                        </div>
                    </div>


                    {{-- زر البحث --}}
                    <div class="col-md-2 d-flex align-items-end mb-4">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fa fa-search mr-1"></i> بحث
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>



    <!-- Nav pills -->
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab"
                aria-controls="pills-home" aria-selected="true">جرد الصنف</a>
        </li>
        <li class="nav-item ml-4">
              <button class="btn btn-secondary me-2" onclick="window.print()"><i class="fas fa-print"></i> طباعة</button>
        </li>
    </ul>


    <!-- Tab content -->
    <div class="tab-content" id="print-area">

        {{-- جرد الصنف --}}
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            @if (!empty($data) && !empty($data[0]->name))
                <div class="text-center mb-3">
                    @if ($rebort_type == '0')
                        <h5 class="text-primary">جرد حسب نوع الصنف: <strong>{{ $item_type }}</strong></h5>
                    @elseif ($rebort_type == '1')
                        <h5 class="text-primary">جرد حسب فئة الصنف: <strong>{{ $data[0]->name }}</strong></h5>
                    @else
                        <h5 class="text-primary">جرد الصنف: <strong>{{ $data[0]->name }}</strong></h5>
                    @endif
                    @if ($start_date != '')
                        <p class="text-muted">الفترة من <span class="text-info">{{ $start_date }}</span> إلى <span
                                class="text-info">{{ $end_date }}</span></p>
                    @else
                        <p class="text-muted">(كل الحركات الصنف )</p>
                    @endif
                </div>
            @endif

            <table class="table table-bordered table-striped">
                <thead class="bg-info">
                    <tr>
                        <th># </th>
                        <th>نوع الحركة</th>
                        <th>اسم الحركة</th>
                        <th>رقم الفاتورة</th>
                        <th>اسم المورد</th>
                        <th>اسم العميل</th>
                        <th>اسم المخزن</th>
                        <th class="{{ $rebort_type == '2' ? 'd-none' : '' }}">اسم الصنف</th>
                        <th>رصيد قبل</th>
                        <th>الكمية</th>
                        <th>رصيد بعد</th>
                        <th>اجمالي الكميات</th>
                        <th>تاريخ</th>
                        <th>بواسطة</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($data))
                        @php
                            $grouped = $data->groupBy('item.item_code');
                        @endphp
                        @foreach ($grouped as $item_code => $movements)
                            @foreach ($movements as $movement)
                                <tr>
                                    <td>{{ $movement->id }}</td>
                                    <td>{{ $movement->itemMovementType->name }}</td>
                                    <td>{{ $movement->itemMovementCategory->name }}</td>
                                    <td style="cursor: pointer"
                                        wire:click.prevent="$dispatch('ShowInvoice',
                                            {
                                                id: {{ $movement->purchase_order_id != '' ? $movement->purchase_order_id : $movement->sales_order_id }},
                                                invoiceType: '{{ $movement->purchase_order_id != '' ? 'purchase' : 'sales' }}'
                                            })">
                                        {{ $movement->purchase_order_id != '' ? $movement->purchase_order_id . ' مشتريات' : $movement->sales_order_id . ' مبيعات' }}
                                    </td>
                                    {{-- <td style="cursor: pointer" wire:click.prevent="$dispatch('ShowInvoice', {id: {{ $movement->purchase_order_id != '' ? $movement->purchase_order_id : $movement->sales_order_id}}})">{{ $movement->purchase_order_id != ''   ? $movement->purchase_order_id  . 'مشتريات': $movement->sales_order_id  . 'مبيعات'}}</td> --}}
                                    <td>{{ $movement->purchase_order_id != '' ? $movement->purchaseOrder->supplier->name : '-' }}
                                    </td>
                                    <td>{{ $movement->sales_order_id != '' ? $movement->salesOrder->customer->name : '-' }}
                                    </td>
                                    {{-- @if (!empty($movement->item_batch->store->name)) --}}
                                        <td>{{ $movement->item_batch->store->name }}</td>
                                    {{-- @else
                                    <td>لا يوجد بيانات</td>
                                    @endif --}}
                                    <td class="{{ $rebort_type == '2' ? 'd-none' : '' }}">{{ $movement->item->name }}
                                    </td>
                                    <td>{{ $movement->qty_before_movement }} {{ $movement->item->itemUnit->name }}
                                    </td>
                                    @php
                                        $diff = $movement->qty_after_movement - $movement->qty_before_movement;
                                    @endphp

                                    <td class="{{ $diff > 0 ? 'text-success' : ($diff < 0 ? 'text-danger' : '') }}">
                                        {{ $diff }} {{ $movement->item->itemUnit->name }}
                                    </td>
                                    @if ($movement->qty_after_movement > 0)
                                        <td style="background-color: rgb(91, 206, 232); color:#fff">{{ $movement->qty_after_movement }} {{ $movement->item->itemUnit->name }}</td>
                                    @elseif($movement->qty_after_movement == 0)
                                        <td style="background-color: rgb(213, 232, 91); color:#392525">{{ $movement->qty_after_movement }} {{ $movement->item->itemUnit->name }}</td>
                                    @else
                                        <td style="background-color: rgb(248, 76, 70); color:#fff">{{ $movement->qty_after_movement }} {{ $movement->item->itemUnit->name }}</td>
                                    @endif

                                    <td>{{ $movement->total_sales_qty * -1}}</td>
                                    <td>{{ $movement->date}} </td>
                                    <td>{{ $movement->adminCreate->name }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @else
                        <tr>
                            <td colspan="12" class="text-center text-danger">لا يوجد بيانات</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class=" mt-2">
                @if (!empty($data))
                    {{ $data->links() }}
                @endif
            </div>
        </div>



    </div>





    <div class=" mt-2">
        {{-- {{ $data->links() }} --}}
    </div>
</div>
