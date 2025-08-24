<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            @if (isset($item->photo))
                                <img class="profile-user-img img-fluid"
                                    src="{{ asset('/assets/backEnd/images/' . $item->photo) }}"
                                    alt="User profile picture">
                            @else
                                <img class="profile-user-img img-fluid"
                                    src="{{ asset('/assets/backEnd/images/defult_item.png') }}"
                                    alt="User profile picture">
                            @endif

                        </div>

                        <h3 class="profile-username text-center">{{ $item->name }}</h3>

                        <p class="text-muted text-center">{{ $item->barcode }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>كود الصنف </b> <a class="float-right">{{ $item->item_code }}</a>
                            </li>

                            <li class="list-group-item">
                                <b>حالة الصنف</b>
                                <a class="float-right" style="color: {{ $item->status == 'active' ? '#00f' : 'red' }}">
                                    {{ getStatus($item->status) }}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b> لديه اصناف فرعية</b>
                                <a class="float-right" style="color: {{ $item->retail_unit == 1 ? '#00f' : 'red' }}">
                                    {{ getItemRetailUnit($item->retail_unit) }}
                                </a>
                            </li>

                            <li class="list-group-item">
                                <b>رقم الشركة</b> <a class="float-right">{{ $item->company_code }}</a>
                            </li>
                        </ul>


                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->


                <!-- /.card -->
            </div>



            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">تفاصيل
                                    الصنف</a></li>
                            <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">القسم
                                    الرئيسي</a></li>
                            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">القسم الفرعي</a>
                            </li>
                        </ul>

                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">

                            <div class="tab-pane active" id="activity">
                                <!-- Item Type نوع الصنف -->
                                <div class="post">
                                    <div class="user-block">
                                        <span class="username">
                                            <h5 class="text-primary">نوع الصنف</h5>
                                        </span>
                                    </div>
                                    <p>
                                        {{ getItemType($item->item_type) }}
                                    </p>
                                </div>
                                <!-- Item Type نوع الصنف  -->

                                <!-- Item_category_id  فئة الصنف او القسم الرئيسي للصنف-->
                                <div class="post">
                                    <div class="user-block">
                                        <span class="username">
                                            <h5 class="text-primary">فئة الصنف او القسم الرئيسي للصنف</h5>
                                        </span>
                                    </div>
                                    <p>
                                        {{ $item->itemCategory->name }}
                                    </p>
                                </div>
                                <!-- Item_category_id  فئة الصنف او القسم الرئيسي -->

                                <!-- Item Type هل سعر الصنف قابل للتغير-->
                                <div class="post">
                                    <div class="user-block">
                                        <span class="username">
                                            <h5 class="text-primary">هل سعر الصنف قابل للتغير </h5>
                                        </span>
                                    </div>
                                    <p>
                                        {{ getItemChangePrice($item->is_change) }}
                                    </p>
                                </div>
                                <!-- Item Type هل سعر الصنف قابل للتغير -->
                            </div>

                            <div class="tab-pane" id="timeline">

                                <!-- item_unit_id وحدة الصنف الاساسية-->
                                <div class="post">
                                    <div class="user-block">
                                        <span class="username">
                                            <h5 class="text-primary">وحدة الصنف الاساسية</h5>
                                        </span>
                                    </div>
                                    <p>
                                        {{ $item->itemUnit->name }}
                                    </p>
                                </div>

                                <div class="post">
                                    <div class="user-block">
                                        <span class="username">
                                            <h5 class="text-primary">سعر التكلفة لوحدة الصنف الاساسية</h5>
                                        </span>
                                    </div>
                                    <p>
                                        {{ $item->item_cost_price }}
                                    </p>
                                </div>


                                <div class="post">
                                    <div class="user-block">
                                        <span class="username">
                                            <h5 class="text-primary">اجمالي كمية الصنف في المخازن</h5>
                                        </span>
                                    </div>
                                    <p>
                                        {{ $item->total_qty_for_parent }} {{ $item->itemUnit->name }}
                                        <br>و
                                        {{ $item->sub_item_qty }} {{ $item->itemUnitChild->name }}
                                    </p>
                                </div>
                            </div>

                            <div class="tab-pane" id="settings">
                                @if (isset($item->itemUnitChild->name))
                                    <!-- item_unit_id وحدة الصنف الفرعية-->
                                    <div class="post">
                                        <div class="user-block">
                                            <span class="username">
                                                <h5 class="text-primary">وحدة الصنف الفرعية</h5>
                                            </span>
                                        </div>
                                        <p>
                                            {{ $item->itemUnitChild->name }}
                                        </p>
                                    </div>
                                    <!-- item_unit_id وحدة الصنف الفرعية -->


                                    <!-- qty_sub_item_unit الكمية داخل كل وحدة من الوحدة الاساسية -->
                                    <div class="post">
                                        <div class="user-block">
                                            <span class="username">
                                                <h5 class="text-primary">الكمية داخل كل {{ $item->itemUnit->name }}
                                                </h5>
                                            </span>
                                        </div>
                                        <p>
                                            {{ $item->qty_sub_item_unit }} {{ $item->itemUnitChild->name }}
                                        </p>
                                    </div>
                                    <!-- qty_sub_item_unit الكمية داخل كل وحدة من   -->


                                    <!-- qty_sub_item_unit سعر التكلفة للوحدة الفرعية داخل كل وحدة من الوحدة الاساسية -->
                                    <div class="post">
                                        <div class="user-block">
                                            <span class="username">
                                                <h5 class="text-primary">سعر التكلفة للوحدة الفرعية داخل كل
                                                    {{ $item->itemUnit->name }} </h5>
                                            </span>
                                        </div>
                                        <p>
                                             {{ $item->sub_item_cost_price }}
                                        </p>
                                    </div>
                                    <!-- qty_sub_item_unit سعر التكلفة للوحدة الفرعية داخل كل وحدة من   -->



                                    <div class="post">
                                        <div class="user-block">
                                            <span class="username">
                                                <h5 class="text-primary">اجمالي كمية الصنف في المخازن</h5>
                                            </span>
                                        </div>
                                        <p>
                                            {{ $item->total_qty_for_sub_items }} {{ $item->itemUnitChild->name }}
                                        </p>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
</div>
