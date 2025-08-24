<div>
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title" style="direction: rtl;">تعديل صنف {{ $item->name }} </h3>
        </div>
        <form class="form form-horizontal" wire:submit.prevent='submit'>
            <div class="card-body">
                <div class="row">
                    <!-- اسم الصنف -->
                    <div class="col-sm-6  mb-4">
                        <div class="form-group">
                            <label>اسم الصنف:</label>
                            <input type="text" class="form-control" placeholder="ادخل اسم الصنف" wire:model="name">
                            @include('backEnd.error', ['property' => 'name'])
                        </div>
                    </div>

                    <!-- باركود الصنف -->
                    <div class="col-sm-6  mb-4">
                        <div class="form-group">
                            <label>باركود الصنف :</label>
                            <input type="text" class="form-control" placeholder="ادخل باركود الصنف" wire:model="barcode">
                            @include('backEnd.error', ['property' => 'barcode'])
                        </div>
                    </div>

                    <!-- نوع الصنف -->
                    <div class="col-md-6  mb-4">
                        <div class="form-group">
                            <label>نوع الصنف</label>
                            <select class="form-control" style="width: 100%;" wire:model="item_type">
                                <option value="">اختار نوع الصنف</option>
                                <option value="0">مخزني</option>
                                <option value="1">استهلاكي</option>
                                <option value="2">عهدة</option>
                            </select>
                            @include('backEnd.error', ['property' => 'item_type'])
                        </div>
                    </div>

                    <!-- فئة الصنف -->
                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label>فئة الصنف</label>
                            <select class="form-control select2" style="width: 100%;" wire:model="item_category_id">
                                <option value="">اختار فئة الصنف</option>
                                @foreach($itemCategories as $data)
                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                @endforeach
                            </select>
                            @include('backEnd.error', ['property' => 'item_category_id'])
                        </div>
                    </div>


                    <!-- الوحدة الأساسية للصنف -->
                    <div class="col-md-6 mb-4">
                        <div class="form-group" wire:ignore>
                            <label>الوحدة الأساسية للصنف</label>
                            <select id="unit-select" class="form-control select2" style="width: 100%;" wire:model="item_unit_id">
                                <option>اختار الوحدة الأساسية للصنف</option>
                                @foreach($itemsUnitsParent as $data)
                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @include('backEnd.error', ['property' => 'item_unit_id'])

                    </div>



                      <!--  سعر البيع الجملة للوحدة الاساسية -->
                      <div class="col-sm-6  mb-4 {{ $item_unit_id != '' ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label>سعر البيع الجملة للوحدة الاساسية  :</label>
                            <input type="number" class="form-control" placeholder=" ادخل سعر البيع الجملة للوحدة الاساسية  " wire:model="item_wholesale_price">
                            @include('backEnd.error', ['property' => 'item_wholesale_price'])
                        </div>
                    </div>



                    <!--  سعر البيع نصف جملة للوحدة الاساسية -->
                    <div class="col-sm-6  mb-4 {{ $item_unit_id != '' ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label>سعر البيع نصف جملة للوحدة الاساسية  :</label>
                            <input type="number" class="form-control" placeholder=" ادخل سعر البيع نصف جملة للوحدة الاساسية  " wire:model="item_Half_wholesale_price">
                            @include('backEnd.error', ['property' => 'item_Half_wholesale_price'])
                        </div>
                    </div>


                     <!--  سعر البيع التجزئة للوحدة الاساسية -->
                     <div class="col-sm-6  mb-4 {{ $item_unit_id != '' ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label>سعر البيع التجزئة للوحدة الاساسية  :</label>
                            <input type="number" class="form-control" placeholder=" ادخل سعر البيع التجزئة للوحدة الاساسية  " wire:model="item_retail_price">
                            @include('backEnd.error', ['property' => 'item_retail_price'])
                        </div>
                    </div>


                      <!--  سعر التكلفة للوحدة الاساسية -->
                      <div class="col-sm-6  mb-4 {{ $item_unit_id != '' ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label>سعر التكلفة للوحدة الاساسية  :</label>
                            <input type="number" class="form-control" placeholder=" ادخل سعر التكلفة للوحدة الاساسية  " wire:model="item_cost_price">
                            @include('backEnd.error', ['property' => 'item_cost_price'])
                        </div>
                    </div>


                    <!-- حقل: هل للصنف وحدة تجزئة؟ -->
                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label>هل للصنف وحدة تجزئة</label>
                            <select class="form-control" wire:change="submit_retail_unit($event.target.value)" wire:model="retail_unit">
                                <option value="">هل للصنف وحدة تجزئة</option>
                                <option value="1">نعم</option>
                                <option value="0">لا</option>
                            </select>

                        </div>
                        @include('backEnd.error', ['property' => 'retail_unit'])
                    </div>
                    <!-- {{ $item_unit_id }} -->


                    <!-- حقل: الوحدة الفرعية للصنف -->
                    <div class="col-md-6 mb-4 {{ $retail_unit == 1 ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label>الوحدة الفرعية للصنف</label>
                            <select class="form-control" wire:model="sub_item_unit_id">
                                <option value="">اختار الوحدة الفرعية للصنف</option>
                                @foreach($itemsUnitsChild as $data)
                                <option value="{{ $data->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @include('backEnd.error', ['property' => 'sub_item_unit_id'])
                        </div>
                    </div>



                    <!--  عدد الوحدات الفرعية للوحدة الاساسية -->
                    <div class="col-sm-6  mb-4 {{ $retail_unit == 1 ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label>عدد الوحدات الفرعية للوحة الاساسية :</label>
                            <input type="number" class="form-control" placeholder="ادخل عدد الوحدات الفرعية للوحة الاساسية " wire:model="qty_sub_item_unit">
                            @include('backEnd.error', ['property' => 'qty_sub_item_unit'])
                        </div>
                    </div>


                      <!--  سعر البيع الجملة للوحدة الفرعية -->
                      <div class="col-sm-6  mb-4 {{ $retail_unit == 1 ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label>سعر البيع الجملة للوحدة الفرعية  :</label>
                            <input type="number" class="form-control" placeholder=" ادخل سعر البيع الجملة للوحدة الفرعية  " wire:model="sub_item_wholesale_price">
                            @include('backEnd.error', ['property' => 'sub_item_wholesale_price'])
                        </div>
                    </div>



                    <!--  سعر البيع نصف جملة للوحدة الفرعية -->
                    <div class="col-sm-6  mb-4 {{ $retail_unit == 1 ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label>سعر البيع نصف جملة للوحدة الفرعية  :</label>
                            <input type="number" class="form-control" placeholder=" ادخل سعر البيع نصف جملة للوحدة الفرعية  " wire:model="sub_item_Half_wholesale_price">
                            @include('backEnd.error', ['property' => 'sub_item_Half_wholesale_price'])
                        </div>
                    </div>


                     <!--  سعر البيع التجزئة للوحدة الفرعية -->
                     <div class="col-sm-6  mb-4 {{ $retail_unit == 1 ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label>سعر البيع التجزئة للوحدة الفرعية  :</label>
                            <input type="number" class="form-control" placeholder=" ادخل سعر البيع التجزئة للوحدة الفرعية  " wire:model="sub_item_retail_price">
                            @include('backEnd.error', ['property' => 'sub_item_retail_price'])
                        </div>
                    </div>


                      <!--  سعر التكلفة للوحدة الفرعية -->
                      <div class="col-sm-6  mb-4 {{ $retail_unit == 1 ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label>سعر التكلفة للوحدة الفرعية  :</label>
                            <input type="number" class="form-control" placeholder=" ادخل سعر التكلفة للوحدة الفرعية  " wire:model="sub_item_cost_price">
                            @include('backEnd.error', ['property' => 'sub_item_cost_price'])
                        </div>
                    </div>




                     <!-- حقل: هل سعر الصنف في فاتورة المبيعات قابل للتعديل  -->
                     <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label>هل سعر الصنف في فاتورة المبيعات قابل للتعديل </label>
                            <select class="form-control" wire:model="is_change">
                                <option value=""> هل سعر الصنف في فاتورة المبيعات قابل للتعديل </option>
                                <option value="1">نعم</option>
                                <option value="0">لا</option>
                            </select>
                            @include('backEnd.error', ['property' => 'is_change'])
                        </div>
                    </div>



                    <!-- حالة التفعيل -->
                    {{-- <div class="col-sm-6  mb-4">
                        <div class="form-group">
                            <label>حالة التفعيل :</label>
                            <select class="form-control" wire:model="status">
                                <option value="">اختار حالة الصنف</option>
                                <option value="active">مفعل</option>
                                <option value="un_active">غير مفعل</option>
                            </select>
                            @include('backEnd.error', ['property' => 'status'])
                        </div>
                    </div> --}}


                    <!--  صورة الصنف-->
                    <div class="col-sm-12  mb-4">
                        <div class="form-group">
                            <label>صورة الصنف :</label>
                            <input type="file" class="form-control" wire:model="image">
                            @include('backEnd.error', ['property' => 'image'])
                            <!-- {{ $item->photo }} -->
                             <!-- SHOW IMAGE  -->
                             <div wire:loadig wire:target="image" wire:key="image" class="mt-4">
                                @if($image)
                                    <img style="height: 100px; width: 100px;" src="{{ $image->temporaryUrl() }}" alt="">
                                @else
                                    <img class="img-responsive mb-1" src="{{ asset('/assets/backEnd/images/' . $item->photo)}}" style="height: 300px; width: 300px">
                                @endif
                            </div>
                        </div>
                    </div>



                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">تعديل</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
