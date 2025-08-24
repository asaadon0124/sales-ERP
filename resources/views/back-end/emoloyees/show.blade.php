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
                            @if (isset($admin))

                                {{-- name  اسم المستخدم  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">اسم المستخدم</td>
                                    <td>{{ $admin->name }}</td>
                                </tr>


                                 {{-- name  ايميل المستخدم  --}}
                                 <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">ايميل المستخدم</td>
                                    <td>{{ $admin->email }}</td>
                                </tr>



                                 {{-- start_balance  رصيد اول المدة  --}}
                                 <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">رصيد اول المدة</td>
                                    <td>{{ $admin->start_balance }} جنيه</td>
                                </tr>


                                 {{-- start_balance  حالة رصيد اول المدة  --}}
                                 <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">حالة رصيد اول المدة</td>
                                    <td>{{ $admin->started_balance_status() }}</td>
                                </tr>



                                 {{-- current_balance  الرصيد الحالي  --}}
                                 <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">الرصيد الحالي</td>
                                    <td>{{ $admin->current_balance }} جنيه</td>
                                </tr>



                                 {{-- roles  ادوار الموظف  --}}
                                 <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">ادوار الموظف </td>
                                    <td>
                                         @foreach ($admin->getRoleNames() as $role)
                                            {{ $role  }}
                                        @endforeach
                                    </td>
                                </tr>



                                {{-- order_date تاريخ الاضافة  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">تاريخ الاضافة</td>
                                    <td>{{ $admin->created_at }}</td>
                                </tr>



                                {{-- company_code كود الشركة  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">كود الشركة</td>
                                    <td>{{ $admin->company_code }}</td>
                                </tr>

                            @else
                                <div class="alert alert-danger text-center mt-4">
                                    لا يوجد بيانات
                                </div>
                            @endif

                        </table>
                    </div>
                </div>



                    <h4 class="text-center mb-5 mt-5"> اضافة خزن للمستخدم {{ $admin->name }}</h4>


                    <div class="card-header d-flex justify-content-between">
                        @can('اضافة خزنة جديدة للموظف')
                             <button class="btn btn-primary"
                            wire:click.prevent="$dispatch('AdminTreasuriesCreate',{id: {{ $adminID }}})">اضافة خزنة
                            جديدة</button>
                        @endcan


                        <!-- <input type="text" wire:model.live="search" class="form-control w-25 ms-2" placeholder="بحث"> -->
                        {{-- <input type="text" wire:model.live="search" class="form-control w-25" placeholder="بحث"> --}}
                    </div>
                    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم الخزنة</th>
                                <th>لديها شيفت</th>
                                <th>تاريخ الاضافة</th>
                                <th>تاريخ اخر تحديث</th>
                                <th>الاجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $x = 1;
                            @endphp
                            @if (isset($data->treasuries))
                                @foreach ($data->treasuries as $iteem)
                                    <tr>
                                        <td>{{ $x++ }}</td>
                                        <td>{{ $iteem->name }}</td>
                                        <td>{{ $iteem->activeShift }}</td>
                                        <td>{{ $iteem->created_at }}</td>
                                        <td>{{ last_update($iteem) }}</td>

                                        <td>

                                            @can('تعديل خزنة الموظف')
                                                @if ($iteem->activeShift == 'لا')
                                                    <a class="btn btn-primary waves-effect waves-float waves-light" title="Edit" href="#" wire:click.prevent="$dispatch('AdminTreasuriesUpdate', {id: {{ $iteem->id }}})">
                                                        نعديل
                                                    </a>
                                                @endif
                                            @endcan


                                            @can('تفاصيل خزنة الموظف')
                                                <a class="btn btn-warning waves-effect waves-float waves-light" title="Show" wire:navigate href="{{ route('treasuries.show_admin_treasury',$iteem->id) }}" wire:click.prevent="$dispatch('adminsShow', {id: {{ $iteem->id }}})">
                                                    المزيد
                                                </a>
                                            @endcan


                                            @can('حذف خزنة الموظف')
                                            @if ($iteem->activeShift == 'لا')
                                                 <a class="btn btn-danger waves-effect waves-float waves-light" title="Edit" href="#" wire:click.prevent="$dispatch('AdminTreasuriesDetailesDelete', {id: {{ $iteem->id }}})">
                                                    حذف
                                                </a>
                                            @endif

                                            @endcan


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
