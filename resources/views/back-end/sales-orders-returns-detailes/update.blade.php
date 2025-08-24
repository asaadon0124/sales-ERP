<div class="modal fade text-start modal-primary" id="updateModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">تعديل الصنف {{ $this->item?->item?->name }} </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form form-horizontal" wire:submit.prevent='submit'>
                <div class="modal-body">
                    <div class="row">

                        {{-- sales_item_type_detailes نوع الفاتورة جملة ولا نص جملة ولا قطاعي  --}}
                        <div class="col-sm-4 mb-4">
                            <div class="form-group">
                                <label>نوع الفاتورة</label>

                                <select wire:model="sales_item_type_detailes" wire:loading.attr="disabled"
                                    class="form-control" wire:target="sales_item_type_detailes"
                                    wire:change="sales_item_type_detailes_changed($event.target.value)">
                                    <option value="">نوع الفاتورة</option>
                                    <option value="0">قطاعي</option>
                                    <option value="1"> نصف جملة</option>
                                    <option value="2"> جملة</option>
                                </select>
                                @include('backEnd.error', ['property' => 'sales_item_type_detailes'])
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


                        <!-- اسم الصنف name  -->
                        <div class="col-sm-4 mb-4">
                            <div class="form-group">
                                <label>اسم الصنف</label>
                                <select class="form-control" style="width: 100%;" wire:model="item_code"
                                    wire:change="item_select($event.target.value)">
                                    <option value=""> اختار اسم الصنف</option>
                                    @if (isset($items))
                                        @foreach ($items as $item)
                                            <option value="{{ $item->item_code }}"
                                                @if ($item->item_code == $item_code) selected @endif>
                                                {{ $item->item_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @include('backEnd.error', ['property' => 'item_code'])

                            </div>
                        </div>



                        <!-- وحدة الصنف item_units_id -->
                        <div class="col-sm-4 mb-4 {{ $item_code != '' ? '' : 'd-none' }}">
                            <div class="form-group">
                                <label>وحدة الصنف</label>
                                <select class="form-control" style="width: 100%;" wire:model="item_units_id"
                                    wire:change="check_item_unit_type($event.target.value)">
                                    <option value="">اختار وحدة الصنف</option>

                                    @if (isset($item_selected_detailes))
                                        @if ($item_selected_detailes->itemUnit)
                                            <option value="{{ $item_selected_detailes->itemUnit->id }}" @if ($item_selected_detailes->itemUnit->id  == $item_units_id) selected @endif>
                                                {{ $item_selected_detailes->itemUnit->name }} (وحدة رئيسية)
                                            </option>
                                        @endif

                                        @if ($item_selected_detailes->itemUnitChild)
                                            <option value="{{ $item_selected_detailes->itemUnitChild->id }}" @if ($item_selected_detailes->itemUnitChild->id  == $item_units_id) selected @endif>
                                                {{ $item_selected_detailes->itemUnitChild->name }} (وحدة فرعية)
                                            </option>
                                        @endif
                                    @endif
                                </select>

                                @include('backEnd.error', ['property' => 'item_units_id'])
                            </div>
                        </div>




                        {{-- بيانات المخازن store_id --}}
                        <div class="col-sm-4 mb-4 {{ $item_code != '' ? '' : 'd-none' }}">
                            <div class="form-group">
                                <label>اسم المخزن المستلم للفاتورة</label>

                                <select class="form-control" wire:model="store_id"
                                    wire:change="change_store($event.target.value)">
                                    <option value="">اسم المخزن المستلم للفاتورة</option>
                                    @if (!empty($stores))
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}" @if ($store->id == $store_id) selected @endif> {{ $store->name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                @include('backEnd.error', ['property' => 'store_id'])
                            </div>
                        </div>


                        {{-- بيانات المخزون داخل كل مخزن  --}}
                        <div class="col-sm-4 mb-4 {{ $store_id != '' ? '' : 'd-none' }}">
                            <div class="form-group">
                                <label>اسم المخزن المستلم للفاتورة</label>
                                @if (!empty($total_qty_sold) && !empty($store_id))
                                    <input type="text" class="form-control"
                                        value="{{ $total_qty_sold }}  {{ $check_itemUnit_type->name }}" readonly>
                                @endif

                            </div>
                        </div>



                        <!--  الكمية المستلمة  qty  -->
                        <div class="col-sm-4 mb-4 {{ $check_itemUnit_type != '' ? '' : 'd-none' }}">
                            <div class="form-group">
                                <label>الكمية المستلمة</label>
                                <input type="number" class="form-control" placeholder="ادخل الكمية المستلمة"
                                    wire:model="qty" wire:change="updateQty($event.target.value)"
                                    max="{{ $total_qty_sold }}" step="0.01">
                            </div>
                            @include('backEnd.error', ['property' => 'qty'])
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
                        <div class="col-sm-4 mb-4 {{ $check_itemUnit_type != '' ? '' : 'd-none' }}">
                            <div class="form-group">
                                <label>الاجمالي</label>
                                <input type="number" readonly class="form-control" placeholder="ادخل الاجمالي"
                                    wire:model="total">
                                @include('backEnd.error', ['property' => 'total'])

                            </div>
                        </div>




                        {{-- order_date تاريخ الانتاج  --}}
                        <div class="col-sm-6 mb-4 {{ $is_item_type_date_required ? '' : 'd-none' }}">
                            <div class="form-group">
                                <label>تاريخ الانتاج</label>
                                <input type="date" class="form-control" placeholder="ادخل تاريخ الانتاج"
                                    wire:model="production_date">
                                @include('backEnd.error', ['property' => 'production_date'])

                            </div>
                        </div>


                        {{-- order_date تاريخ الانتهاء  --}}
                        <div class="col-sm-6 mb-4 {{ $is_item_type_date_required ? '' : 'd-none' }}">
                            <div class="form-group">
                                <label>تاريخ الانتهاء</label>
                                <input type="date" class="form-control" placeholder="ادخل تاريخ الانتهاء"
                                    wire:model="expire_date">
                                @include('backEnd.error', ['property' => 'expire_date'])

                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" type="button"
                        class="btn btn-info waves-effect waves-float waves-light">تعديل</button>
                </div>
            </form>
        </div>
    </div>
</div>
