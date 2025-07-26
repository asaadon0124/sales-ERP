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
                                @if (isset($name))

                                    {{-- STORE NAME  --}}
                                    <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">اسم المخزن</td>
                                        <td>{{ $name }}</td>
                                    </tr>

                                     {{-- company CODE  --}}
                                     <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">كود الشركة</td>
                                        <td>{{ $company_code }}</td>
                                    </tr>

                                     {{-- STORE STATUS  --}}
                                     <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">حالة المخزن </td>
                                        <td>{{ $status }}</td>
                                    </tr>



                                      {{-- STORE ADRESS  --}}
                                    <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">عنوان المخزن</td>
                                        <td>{{ $address }}</td>
                                    </tr>



                                     {{-- STORE PHONE  --}}
                                     <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">تليفون المخزن</td>
                                        <td>{{ $phone }}</td>
                                    </tr>









                                @else
                                    <div class="alert alert-danger text-center mt-4">
                                        لا يوجد بيانات
                                    </div>
                                @endif

                        </table>
                    </div>
                </div>

                <h4 class="text-center mb-5 mt-5">الاصناف المتاحية في المخزن  {{$name }}</h4>

                @if (!empty($data))
                    <div class="card-header d-flex justify-content-between">



                        <!-- <input type="text" wire:model.live="search" class="form-control w-25 ms-2" placeholder="بحث"> -->
                        <input type="text" wire:model.live="search" class="form-control w-25"
                            placeholder="بحث باسم الصنف">
                    </div>
                    <div>
                        <table class="table table-bordered table-striped dataTable" style="margin: 10px">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الصنف</th>
                                    <th>نوع الصنف</th>
                                    <th>كمية الصنف بالوحدة الرئيسية</th>
                                    <th>كمية الصنف بالوحدة الفرعية</th>
                                    <th>تاريخ الانتهاء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $x = 1;
                                @endphp
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $x++ }}</td>
                                        <td>{{ $item->item->name }}</td>
                                        <td>{{ getItemType($item->item_type) }}</td>
                                        <td>{{ $item->total_qty - $item->deduction }} {{ $item->item->itemUnit->name}}</td>
                                        <td>{{ ($item->total_qty - $item->deduction) * $item->item->qty_sub_item_unit }} {{ $item->item->itemUnitChild->name}}</td>
                                        <td>{{ $item->expire_date??'_' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class=" mt-2">
                            {{-- {{ $data->links() }} --}}
                        </div>
                    </div>
                @endif


            </div>
        </div>
    </div>
</div>
