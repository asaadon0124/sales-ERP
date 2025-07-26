<div class="modal fade text-start modal-primary" id="createModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>

    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">اضافة فاتورة مبيعات جديدة </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- <pre>{{ json_encode($items_detailes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre> --}}
           <form class="form form-horizontal" wire:submit.prevent='submit'>
                <div class="modal-body">
                    {{-- ال form الخاصة بالفاتورة  --}}
                    <div class="row">
                        {{-- order_date تاريخ الفاتورة  --}}
                        <div class="col-sm-4 mb-4">
                            <div class="form-group">
                                <label>تاريخ الفاتورة</label>
                                <input type="date" class="form-control" placeholder="ادخل تاريخ الفاتورة"
                                    wire:model="order_date">
                                @include('backEnd.error', ['property' => 'order_date'])

                            </div>
                        </div>


                        {{-- فئة الفاتورة matrial_types_id --}}
                        <div class="col-sm-4 mb-4">
                            <div class="form-group">
                                <label>فئة الفاتورة </label>
                                <select wire:model="matrial_types_id" wire:loading.attr="disabled"
                                    class="form-control select2" wire:target="matrial_types_id">
                                    <option selected>فئة الفاتورة </option>
                                    @if (isset($matrial_types))
                                        @foreach ($matrial_types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @include('backEnd.error', ['property' => 'matrial_types_id'])
                            </div>
                        </div>





                        {{-- is_fixed_customer هل العميل ثابت ولا طياري مرة واحدة   --}}
                        {{-- <div class="col-sm-4 mb-4">
                            <div class="form-group">
                                <label>نوع العميل</label>

                                <select wire:model="is_fixed_customer" wire:loading.attr="disabled" class="form-control"
                                    wire:target="is_fixed_customer">
                                    <option selected>نوع العميل</option>
                                    <option value="0">ثابت</option>
                                    <option value="1"> طياري</option>
                                </select>
                                @include('backEnd.error', ['property' => 'is_fixed_customer'])
                            </div>
                        </div> --}}



                        {{-- اسم العميل customer_code --}}
                        <div class="col-sm-3 mb-4">
                            <div class="form-group">
                                <label>اسم العميل</label>
                                <select wire:change="customerChanged($event.target.value)" wire:model="customer_code"
                                    wire:loading.attr="disabled" class="form-control select2"
                                    wire:target="customer_code">
                                    <option selected>اسم العميل</option>
                                    @if (isset($customers))
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->customer_code }}">{{ $customer->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @include('backEnd.error', ['property' => 'customer_code'])
                            </div>
                        </div>



                         {{-- رصيد العميل customer_balance --}}
                          @if (!empty($customer_balance))
                            <div class="col-sm-3 mb-4">
                                <div class="form-group">
                                    <label>رصيد العميل</label>
                                        <input type="text" wire:model="customer_balance"
                                        wire:loading.attr="disabled" class="form-control"
                                        wire:target="customer_balance" readonly>

                                    @include('backEnd.error', ['property' => 'customer_balance'])
                                </div>
                            </div>
                         @endif


                        {{-- اسم المندوب servant_code --}}
                        <div class="col-sm-3 mb-4">
                            <div class="form-group">
                                <label>اسم المندوب</label>
                                <select wire:change="servantChanged($event.target.value)" wire:model="servant_code"
                                    wire:loading.attr="disabled" class="form-control select2"
                                    wire:target="servant_code">
                                    <option selected>اسم المندوب</option>
                                    @if (isset($customers))
                                        @foreach ($servants as $servant)
                                            <option value="{{ $servant->servant_code }}">{{ $servant->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @include('backEnd.error', ['property' => 'servant_code'])
                            </div>
                        </div>


                        {{-- رصيد المندوب servant_balance --}}
                          @if (!empty($servant_balance))
                            <div class="col-sm-3 mb-4">
                                <div class="form-group">
                                    <label>رصيد المندوب</label>
                                        <input type="text" wire:model="servant_balance"
                                        wire:loading.attr="disabled" class="form-control"
                                        wire:target="servant_balance" readonly>

                                    @include('backEnd.error', ['property' => 'servant_balance'])
                                </div>
                            </div>
                         @endif


                         {{-- الملاحظات notes --}}
                        <div class="col-sm-12 mb-4">
                            <div class="form-group">
                                <label>الملاحظات </label>
                                <textarea name="notes" wire:model="notes" class="form-control" id="" cols="10" rows="2"></textarea>
                                @include('backEnd.error', ['property' => 'notes'])
                            </div>
                        </div>
                    </div>

                     <!-- بيانات المخازن خط عريض وواضح -->
                    <hr style="height: 4px; background-color: #333; border: none;">
                    <h4>بيانات المخازن</h4> <br>

                    <div class="row">
                        {{-- items_type هل الاصناف داخل الفتورة ثابتة ولا متغيرة في طريقة البيع بالجملة ولا قطاعي     --}}
                        <div class="col-sm-4 mb-4">
                            <div class="form-group">
                                <label>نوع الاصناف داخل الفاتورة </label>

                                <select wire:model="items_type" wire:loading.attr="disabled" class="form-control"
                                    wire:target="items_type" wire:change="change_items_type($event.target.value)">
                                    <option selected>نوع الاصناف داخل الفاتورة </option>
                                    <option value="0">ثابت</option>
                                    <option value="1"> متغير</option>
                                </select>
                                @include('backEnd.error', ['property' => 'items_type'])
                            </div>
                        </div>


                         {{-- sales_item_type نوع الفاتورة جملة ولا نص جملة ولا قطاعي  --}}
                        <div class="col-sm-4 mb-4 {{ $items_type != '' && $items_type == 0 ? '' : 'd-none' }}">
                            <div class="form-group">
                                <label>نوع الفاتورة</label>

                                <select wire:model="sales_item_type" wire:loading.attr="disabled" class="form-control"
                                    wire:target="sales_item_type"
                                    wire:change="change_sales_item_type($event.target.value)">
                                    <option value="">نوع الفاتورة</option>
                                    <option value="0">قطاعي</option>
                                    <option value="1"> نصف جملة</option>
                                    <option value="2"> جملة</option>
                                </select>
                                @include('backEnd.error', ['property' => 'sales_item_type'])
                            </div>
                        </div>
                    </div>


                    <!-- خط عريض وواضح  الحسابات-->
                    <hr style="height: 4px; background-color: #333; border: none;">
                    <h4>الحسابات</h4> <br>

                    <div class="row">
                         @if (!empty($items_detailes))

                           {{--  بيانات الخزنة  --}}
                            <div class="col-sm-3 mb-4">
                                <div class="form-group">
                                    <label>بيانات الخزنة  </label>
                                    @if (isset($treasury_balance))
                                        <input type="text" class="form-control" wire:model="treasury_balance" readonly>
                                    @endif
                                    @include('backEnd.error', ['property' => 'treasury_balance'])
                                </div>
                            </div>

                             {{-- total_cost_before_all اجمالي الفاتورة قبل الخصم و الضرايب --}}
                            <div class="col-sm-3 mb-4">
                                <div class="form-group">
                                    <label>اجمالي الفاتورة قبل الخصم و الضرايب </label>
                                    <input type="number" class="form-control" wire:model="total_cost_before_all"
                                        readonly>
                                </div>
                            </div>


                             {{--  اجمالي الفاتورة قبل الخصم  tax_percent --}}
                                <div class="col-sm-3 mb-4">
                                    <label> اجمالي الفاتورة قبل الخصم </label>
                                    <input type="number" class="form-control" readonly
                                        value="{{ number_format($total_before_discount, 2, '.', '') }}">
                                </div>


                                  {{-- الاجمالي بعد الخصم و الضريبة total_cost  --}}
                                <div class="col-sm-3 mb-4">
                                    <label>الاجمالي بعد الخصم و الضريبة </label>
                                    <input type="number" readonly class="form-control" wire:model="total_cost"
                                        step="0.01">
                                    @include('backEnd.error', ['property' => 'total_cost'])
                                </div>


                             {{-- invoice_type نوع الفاتورة كاش او اجل  --}}
                            <div class="col-sm-3 mb-4">
                                <div class="form-group">
                                    <label>نوع الفاتورة</label>

                                    <select wire:model="invoice_type" wire:loading.attr="disabled" class="form-control"
                                        wire:target="invoice_type" wire:change="change_invoice_type($event.target.value)">
                                        <option value="">نوع الفاتورة</option>
                                        <option value="0">كاش</option>
                                        <option value="1"> اجل</option>
                                    </select>
                                    @include('backEnd.error', ['property' => 'invoice_type'])
                                </div>
                            </div>

                            @if ($this->total_cost_before_all != '' || $this->total_cost_before_all != null)

                             {{-- نوع الخصم discount_type  --}}
                                <div class="col-sm-3 mb-4">
                                    <label>نوع الخصم</label>
                                    <select wire:model.debounce.300ms="discount_type" wire:loading.attr="disabled" class="form-control"
                                        wire:target="discount_type"
                                        wire:change="change_discount_type($event.target.value)">
                                        <option value="">اختار نوع الخصم</option>
                                        <option value="0">قيمة</option>
                                        <option value="1">نسبة</option>
                                    </select>
                                    @include('backEnd.error', ['property' => 'discount_type'])
                                </div>

                                {{-- نسبة الضريبة  tax_percent --}}
                                <div class="col-sm-3 mb-4">
                                    <label> نسبة الضريبة </label>
                                    <input type="number" class="form-control" wire:model.debounce.300ms="tax_percent"
                                        wire:change="change_tax_percent($event.target.value)">
                                    @include('backEnd.error', ['property' => 'tax_percent'])
                                </div>

                                 {{-- قيمة الضريبة  tax_value --}}
                                <div class="col-sm-3 mb-4">
                                    <label> قيمة الضريبة </label>
                                    <input type="number" readonly class="form-control" wire:model.debounce.300ms="tax_value">
                                    @include('backEnd.error', ['property' => 'tax_value'])
                                </div>




                                 {{-- نسبة الخصم discount_percent  --}}
                                <div class="col-sm-3 mb-4 {{ $discount_type != '' && $discount_type == 1 ? '' : 'd-none' }}"
                                    wire:change="change_discount_percent($event.target.value)">
                                    <label>نسبة الخصم </label>
                                    <input type="number" class="form-control" wire:model.debounce.300ms="discount_percent"
                                        step="0.01">
                                    @include('backEnd.error', ['property' => 'discount_percent'])
                                </div>



                                {{-- قيمة الخصم discount_amount  --}}
                                <div class="col-sm-4 mb-4 {{ $discount_type != '' && $discount_type == 0 ? '' : 'd-none' }}"
                                    wire:change="change_discount_amount($event.target.value)">
                                    <label>قيمة الخصم </label>
                                    <input type="number" class="form-control" wire:model.debounce.300ms="discount_amount"
                                        step="0.01">
                                    @include('backEnd.error', ['property' => 'discount_amount'])
                                </div>





                                @if ($invoice_type == '0')
                                    {{-- المبلغ المدفوع الان للمورد paid  --}}
                                    <div class="col-sm-6 mb-4">
                                        <label>المبلغ المدفوع الان للمورد </label>
                                        <input type="number" class="form-control" wire:model="paid" readonly step="0.01">
                                        @include('backEnd.error', ['property' => 'paid'])
                                    </div>
                                @else
                                    {{-- المبلغ المدفوع الان للمورد paid  --}}
                                    <div class="col-sm-6 mb-4">
                                        <label>المبلغ المدفوع الان للمورد </label>
                                        <input type="number" class="form-control" wire:model="paid" step="0.01"
                                            wire:change="change_paid($event.target.value)">
                                        @include('backEnd.error', ['property' => 'paid'])
                                    </div>

                                    {{-- المبلغ المتبقي  للمورد unpaid  --}}
                                    <div class="col-sm-6 mb-4">
                                        <label>المبلغ المتبقي للمورد</label>
                                        <input type="number" class="form-control" wire:model="unpaid" step="0.01"
                                            wire:change="change_unpaid($event.target.value)">
                                        @include('backEnd.error', ['property' => 'unpaid'])
                                    </div>
                                @endif

                            @endif
                         @endif


                    </div>


                      <!-- خط عريض وواضح -->
                    <hr style="height: 4px; background-color: #333; border: none;">

                     {{-- بداية الفورم الخاصة بتفاصيل الفاتورة  --}}
                        <div class="row {{ $order_date != '' && $items_type != '' ? '' : 'd-none' }}">

                            {{-- sales_item_type_detailes نوع الفاتورة جملة ولا نص جملة ولا قطاعي  --}}
                            <div class="col-sm-6 mb-4 {{ $items_type != '' && $items_type == 1 ? '' : 'd-none' }}">
                                <div class="form-group">
                                    <label>نوع الفاتورة</label>

                                    <select wire:model="sales_item_type_detailes" wire:loading.attr="disabled"
                                        class="form-control" wire:target="sales_item_type_detailes" wire:change="sales_item_type_detailes_changed($event.target.value)">
                                        <option value="">نوع الفاتورة</option>
                                        <option value="0">قطاعي</option>
                                        <option value="1"> نصف جملة</option>
                                        <option value="2"> جملة</option>
                                    </select>
                                    @include('backEnd.error', ['property' => 'sales_item_type_detailes'])
                                </div>
                            </div>

                             <!-- اسم الصنف name  -->
                            <div class="col-sm-4 mb-4">
                                <div class="form-group">
                                    <label>اسم الصنف</label>
                                    <select class="form-control select2" style="width: 100%;" wire:model="item_code"
                                        wire:change="item_select($event.target.value)">
                                        <option selected value=""> اختار اسم الصنف</option>
                                        @if (isset($items))
                                            @foreach ($items as $item)
                                                <option value="{{ $item->item_code }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @include('backEnd.error', ['property' => 'item_code'])

                                </div>
                            </div>


                             <!-- وحدة الصنف item_units_id  -->
                            <div class="col-sm-4 mb-4 {{ $item_code != '' ? '' : 'd-none' }}">
                                <div class="form-group">
                                    <label>وحدة الصنف</label>
                                    <select class="form-control" style="width: 100%;" wire:model="item_units_id"
                                        wire:change="check_item_unit_type($event.target.value)">
                                        <option value=""> اختار وحدة الصنف</option>
                                        @if (isset($item_selected_detailes->item_unit_id) && $item_selected_detailes->item_unit_id != '' && !empty($item_selected_detailes->itemUnit->name))
                                            <option value="{{ $item_selected_detailes->item_unit_id }}">
                                                {{ $item_selected_detailes->itemUnit->name }} (وحدة رئيسية)</option>
                                        @endif

                                        @if (isset($item_selected_detailes->sub_item_unit_id) && $item_selected_detailes->sub_item_unit_id != '' && $item_selected_detailes->itemUnitChild->name)
                                            <option value="{{ $item_selected_detailes->sub_item_unit_id }}">
                                                {{ $item_selected_detailes->itemUnitChild->name }} (وحدة فرعية)</option>
                                        @endif
                                    </select>
                                    @include('backEnd.error', ['property' => 'item_unit_id'])

                                </div>
                            </div>


                             {{-- بيانات المخازن store_id --}}
                            <div class="col-sm-4 mb-4 {{ $item_code != '' ? '' : 'd-none' }}">
                                <div class="form-group">
                                    <label>اسم المخزن المستلم للفاتورة</label>

                                    <select class="form-control select2" wire:model="store_value"
                                        wire:change="change_store($event.target.value)">
                                        <option value="">اسم المخزن المستلم للفاتورة</option>
                                        @if (!empty($stores) && is_iterable($stores))
                                            @foreach ($stores as $store)
                                                <option value="{{ $store->store->id }}|{{ $store->id }}">
                                                    {{ $store->store->name }} {{ $store->expire_date }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @include('backEnd.error', ['property' => 'store_id'])
                                </div>
                            </div>


                            {{-- بيانات المخزون داخل كل مخزن  --}}
                            <div class="col-sm-12 mb-4 {{ $item_code != '' ? '' : 'd-none' }}">
                                <div class="form-group">
                                    <label>اسم المخزن المستلم للفاتورة</label>
                                    @if (!empty($select_store->store->name) && !empty($check_itemUnit_type->name) && !empty($store_qty))
                                        <input type="text" class="form-control"
                                            value="({{ $select_store->store->name }}) {{ $store_qty }}  {{ $check_itemUnit_type->name }}"
                                            readonly>
                                    @endif

                                </div>
                            </div>



                              <!--  الكمية المستلمة  qty  -->
                            <div class="col-sm-4 mb-4 {{ $check_itemUnit_type != '' ? '' : 'd-none' }}">
                                <div class="form-group">
                                    <label>الكمية المستلمة</label>
                                    <input type="number" class="form-control" placeholder="ادخل الكمية المستلمة"
                                        wire:model="qty" wire:change="updateQty($event.target.value)"
                                        max="{{ $store_qty }}"
                                        step="0.01"
                                        {{-- oninput="this.value = (this.value > {{ $store_qty }}) ? {{ $store_qty }} : this.value;"> --}}
                                    @include('backEnd.error', ['property' => 'qty'])

                                </div>
                            </div>


                            {{-- is_bouns هل الصنف بونص او لا  --}}
                            <div class="col-sm-4 mb-4">
                                <div class="form-group">
                                    <label>هل الصنف بونص او لا</label>

                                    <select wire:model="is_bouns" wire:loading.attr="disabled" class="form-control"
                                        wire:target="is_bouns">
                                        <option selected>هل الصنف بونص او لا</option>
                                        <option value="yes">نعم</option>
                                        <option value="no">لا</option>
                                    </select>
                                    @include('backEnd.error', ['property' => 'is_bouns'])
                                </div>
                            </div>


                            <!--  سعر الوحدة  unit_price  -->
                            <div class="col-sm-4 mb-4 {{ $check_itemUnit_type != '' ? '' : 'd-none' }}">
                                <div class="form-group">
                                    <label>سعر الوحدة</label>
                                    <input type="number" class="form-control" placeholder="ادخل سعر الوحدة"
                                        wire:model="unit_price" wire:change="updateUnit_price($event.target.value)"
                                        @if ($item_is_change == '0') readonly @endif>
                                    @include('backEnd.error', ['property' => 'unit_price'])

                                </div>
                            </div>


                             <!--  الاجمالي  total  -->
                            <div class="col-sm-6 mb-4 {{ $check_itemUnit_type != '' ? '' : 'd-none' }}">
                                <div class="form-group">
                                    <label>الاجمالي</label>
                                    <input type="number" readonly class="form-control" placeholder="ادخل الاجمالي"
                                        wire:model="total">
                                    @include('backEnd.error', ['property' => 'total'])

                                </div>
                            </div>

                        </div>
                     {{-- نهاية الفورم الخاصة بتفاصيل الفاتورة  --}}


                    <button type="button" wire:click="add_item" class="btn btn-primary waves-effect waves-float waves-light {{ $order_date != '' && $items_type != '' ? '' : 'd-none' }}">
                        إضافة
                    </button>

                    <button type="button" wire:click="remove_session" class="btn btn-warning waves-effect waves-float waves-light {{ $order_date != '' && $items_type != '' ? '' : 'd-none' }}">
                        حذف البيانات
                    </button>

                    <div class="modal-footer {{ $order_date != '' && $items_type != '' && !empty($items_detailes) ? '' : 'd-none' }}">
                        <button type="submit" type="button" class="btn btn-success waves-effect waves-float waves-light"  wire:mouseenter="saveData">انشاء الفاتورة</button>
                    </div>

                </div>
           </form>



            {{-- ********************************************************************************************************* --}}

            <table class="table table-bordered table-striped dataTable" style="margin: 10px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المخزن</th>
                        <th>نوع البيع</th>
                        <th>اسم الصنف</th>
                        <th>وحدة البيع</th>
                        <th>سعر الصنف</th>
                        <th>الكمية</th>
                        <th>الاجمالي</th>
                        <th>الاجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $x = 1;
                    @endphp
                    @if (isset($items_detailes))
                        @foreach ($items_detailes as $index => $iteem)
                            <tr>
                                <td>{{ $x++ }}</td>
                                <td>{{ $iteem['store_name'] }}</td>
                                <td>
                                    @if ($iteem['sales_item_type_detailes'] == '0')
                                        قطاعي
                                    @elseif ($iteem['sales_item_type_detailes'] == '1')
                                        نصف جملة
                                    @else
                                        جملة
                                    @endif
                                </td>
                                <td>{{ $iteem['item_name'] }}</td>
                                <td>{{ $iteem['itemUnit_name'] }}</td>
                                <td>{{ $iteem['unit_price'] }}</td>
                                <td>{{ $iteem['qty'] }}</td>
                                <td>{{ $iteem['total'] }}</td>
                                <td>

                                    <a class="btn btn-danger waves-effect waves-float waves-light" title="Edit"
                                        href="#" wire:click="removeItem({{ $index }})">
                                        حذف
                                    </a>
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
