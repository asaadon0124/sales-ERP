<div class="modal fade text-start modal-primary" id="updateModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">Update {{ $itemID }}  </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form form-horizontal" wire:submit.prevent='submit'>
                <div class="modal-body">
                    <div class="row">

                          <!-- اسم الصنف name  -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>اسم الصنف</label>
                                <select class="form-control select2" style="width: 100%;" wire:model="item_code"
                                     wire:change="item_select($event.target.value)">
                                    <option selected value=""> اختار اسم الصنف</option>
                                    @if (isset($items))
                                        @foreach ($items as $data)
                                            <option value="{{ $data->item_code }}" {{ $data->item_code == $item_code ? 'selected' : '' }}>{{ $data->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @include('backEnd.error', ['property' => 'item_code'])

                            </div>
                        </div>


                        <!-- وحدة الصنف item_units_id  -->
                        <div class="col-sm-6 mb-4 {{ $item_code != '' ? '' : 'd-none' }}">
                            <div class="form-group">
                                <label>وحدة الصنف</label>
                                <select class="form-control" style="width: 100%;" wire:model="item_units_id"
                                    wire:change="check_item_unit_type($event.target.value)">
                                    <option selected> اختار وحدة الصنف</option>
                                    @if (isset($item_selected_detailes->item_unit_id) && $item_selected_detailes->item_unit_id != '')
                                        <option value="{{ $item_selected_detailes->item_unit_id }}" {{ $item_selected_detailes->item_unit_id == $item_units_id ? 'selected' : '' }}>
                                            {{ $item_selected_detailes->itemUnit->name }} (وحدة رئيسية)</option>
                                    @endif

                                    @if (isset($item_selected_detailes->sub_item_unit_id) && $item_selected_detailes->sub_item_unit_id != '')
                                        <option value="{{ $item_selected_detailes->sub_item_unit_id }}" {{ $item_selected_detailes->sub_item_unit_id == $item_units_id ? 'selected' : '' }}>
                                            {{ $item_selected_detailes->itemUnitChild->name }} (وحدة فرعية)</option>
                                    @endif
                                </select>
                                @include('backEnd.error', ['property' => 'item_units_id'])

                            </div>
                        </div>


                        {{-- بيانات المخازن batch_id --}}
                        <div class="col-sm-6 mb-4 {{ $item_code != '' ? '' : 'd-none' }}">
                            <div class="form-group">
                                <label>اسم المخزن المستلم للفاتورة</label>

                                <select class="form-control select2" wire:model="batch_id"
                                    wire:change="change_store($event.target.value)">
                                    <option value="">اسم المخزن المستلم للفاتورة</option>
                                    @if (!empty($item_batchs))
                                        @foreach ($item_batchs as $batch)
                                            <option value="{{ $batch->auto_serial }}" {{ $batch->auto_serial == $batch_id ? 'selected' : '' }} >
                                                {{ $batch->store->name }}  تاريخ الانتهاء {{ $batch->expire_date }}
                                            </option>

                                        @endforeach
                                    @endif
                                </select>
                                @include('backEnd.error', ['property' => 'batch_id'])
                            </div>
                        </div>



                          {{-- بيانات المخزون داخل كل مخزن  --}}
                            <div class="col-sm-6 mb-4 {{ $item_code != '' && $item_units_id != '' ? '' : 'd-none' }}">
                                <div class="form-group">
                                    @if (!empty($item_batch->store->name) && !empty($item_units_id) && !empty($store_qty))
                                        <label>اسم المخزن المستلم للفاتورة</label>
                                        <input type="text" class="form-control"
                                            value="({{ $item_batch->store->name }}) {{ $store_qty }}  {{ $check_itemUnit_type->name }}"
                                            readonly>
                                    @endif

                                </div>
                            </div>

                        <!--  الكمية المستلمة  qty  -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>الكمية المستلمة</label>
                                <input type="number" class="form-control" placeholder="ادخل الكمية المستلمة"
                                    wire:model="qty" wire:change="updateQty($event.target.value)">
                                @include('backEnd.error', ['property' => 'qty'])

                            </div>
                        </div>



                        <!--  سعر الوحدة  unit_price  -->
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>سعر الوحدة</label>
                                <input type="number" class="form-control" placeholder="ادخل سعر الوحدة"
                                    wire:model="unit_price" wire:change="updateUnit_price($event.target.value)">
                                @include('backEnd.error', ['property' => 'unit_price'])

                            </div>
                        </div>



                        <!--  الاجمالي  total  -->
                        <div class="col-sm-12 mb-4">
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
