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

                                    {{-- COMPANY NAME  --}}
                                    <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">اسم الخزنة</td>
                                        <td>{{ $name }}</td>
                                    </tr>

                                     {{-- COMPANY CODE  --}}
                                     <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">كود الشركة</td>
                                        <td>{{ $company_code }}</td>
                                    </tr>

                                     {{-- COMPANY STATUS  --}}
                                     <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">حالة الشركة</td>
                                        <td>{{ $this->status() }}</td>
                                    </tr>



                                      {{-- COMPANY IS_MASTER  --}}
                                    <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">نوع الخزنة</td>
                                        <td>{{ $this->isMaster() }}</td>
                                    </tr>



                                     {{-- LAST RECEPT PAY  --}}
                                     <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">اخر ايصال دفع</td>
                                        <td>{{ $last_recept_pay }}</td>
                                    </tr>


                                    {{-- LAST RECEPT RECIVE  --}}
                                    <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">اخر ايصال تحصيل</td>
                                        <td>{{ $last_recept_recive }}</td>
                                    </tr>



                                    {{-- TREASURY CURRENT BALANCE  --}}
                                    <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">الرصيد الحالي للخزنة</td>
                                        <td>{{ Treasries_balances($itemId) }} جنيه</td>
                                    </tr>


                                    {{-- LAST RECEPT RECIVE  --}}
                                    <tr role="row">
                                        <td style="width: 30%;background-color:#d6cacae6;">هل لديها شيفت حاليا</td>
                                        @if (!empty(treasures_with_Active_shifts($itemId)))
                                            <td style="background-color: rgb(88, 209, 88);text-align:center;color:#fff">نعم يوجد</td>
                                        @else
                                            <td style="background-color: #f00;text-align:center;color:#fff">لا يوجد</td>
                                        @endif
                                    </tr>



                                @else
                                    <div class="alert alert-danger text-center mt-4">
                                        لا يوجد بيانات
                                    </div>
                                @endif

                        </table>
                    </div>
                </div>

                <h4 class="text-center mb-5 mt-5">الخزن التي يمكنها تسليم تحصيلها الي الخزنة {{$name }}</h4>

                @if (!empty($data->treasuriesDetailes))
                    <div class="card-header d-flex justify-content-between">
                        @can('اضافة خزنة فرعية للخزنة الرئيسية')
                            @if ($is_master == 'master')
                                <button class="btn btn-primary" wire:click.prevent="$dispatch('treasuriesCreateٍSub',{id: {{ $itemId }}})">اضافة خزنة</button>
                            @endif
                        @endcan


                        <!-- <input type="text" wire:model.live="search" class="form-control w-25 ms-2" placeholder="بحث"> -->
                        <input type="text" wire:model.live="search" class="form-control w-25"
                            placeholder="{{ __('dashboard.search-here') }}">
                    </div>
                    <div>
                        <table class="table table-bordered table-striped dataTable" style="margin: 10px">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>حالة الخزنة</th>
                                    <th>هل  ؤئيسية</th>
                                    <th>اخر ايصال صرف</th>
                                    <th>اخر ايصال استلام</th>
                                    <th>لديها شيفت</th>
                                    <th>رصيد الخزنة</th>
                                    <th>كود الشركة</th>
                                    <th>الاجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $x = 1;
                                @endphp
                                @if (!empty($data->treasuriesDetailes))
                                    @foreach ($data->treasuriesDetailes as $item)
                                    <tr>
                                            <td>{{ $x++ }}</td>

                                            <td>{{ $item->treasurie->name }}</td>
                                            <td>{{ getStatus($item->treasurie->status) }}</td>
                                            <td>{{ getMaster($item->treasurie->is_master) }}</td>
                                            <td>{{ $item->treasurie->last_recept_pay }}</td>
                                            <td>{{ $item->treasurie->last_recept_recive }}</td>

                                            @if (!empty(treasures_with_Active_shifts($item->treasurie->id)))
                                                <td style="background-color: rgb(88, 209, 88);text-align:center;color:#fff">نعم يوجد</td>
                                            @else
                                                <td style="background-color: #f00;text-align:center;color:#fff">لا يوجد</td>
                                            @endif


                                            <td>{{ Treasries_balances($item->treasurie->id) }}</td>
                                            <td>{{ $item->treasurie->company_code }}</td>
                                            <td>
                                                {{-- {{ treasures_with_Active_shifts($item->sub_treasuries_id) }}
                                                {{$item->sub_treasuries_id}} --}}
                                                @if (empty(treasures_with_Active_shifts($item->sub_treasuries_id)))
                                                    @can('حذف خزنة فرعية للخزنة الرئيسية')
                                                        <button class="btn btn-danger" wire:click.prevent="$dispatch('SubTreasuriesDelete',{id: {{ $item->id }}})">حذف</button>
                                                    @endcan
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
                @endif


            </div>
        </div>
    </div>
</div>
