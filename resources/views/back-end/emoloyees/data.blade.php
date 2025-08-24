<div class="table-responsive">
    <div class="card-header ">
        <input type="text" wire:model.live="search" class="form-control w-25"
            placeholder="بحث">

        @can('اضافة موظف جديد')
            <button class="btn btn-primary" wire:click.prevent="$dispatch('employeeCreate')">اضافة</button>
        @endcan


    </div>
    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
        <thead>
            <tr>
                {{-- <th>#</th> --}}
                <th>اسم المستخدم</th>
                <th>ايميل المستخدم</th>
                <th>حالة اول المدة</th>
                <th>رصيد اول المدة</th>
                <th>الرصيد الحالي</th>
                <th>تاريخ الاضافة</th>
                <th>الاجراءات</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($data))
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->started_balance_status() }}</td>
                        <td>
                            @if ($item->start_balance > 0)
                                (له){{ $item->start_balance }} جنيه
                            @else
                                (عليه){{ $item->start_balance }} جنيه
                            @endif
                        </td>
                        <td>
                            @if ($item->current_balance > 0)
                                (له){{ $item->current_balance }} جنيه
                            @else
                                (عليه){{ $item->current_balance }} جنيه
                            @endif
                        </td>

                        <td>{{ $item->created_at }}</td>

                        <td>
                            <div class="d-flex align-items-center">

                                @if ($item->id != auth()->user()->id)
                                    @can('تعديل الموظف')
                                        <a class="btn btn-primary waves-effect waves-float waves-light ml-4" title="Edit" href="#" wire:click.prevent="$dispatch('adminsUpdate', {id: {{ $item->id }}})">
                                            تعديل
                                        </a>
                                    @endcan

                                    @can('حذف الموظف')
                                        <a class="btn btn-danger waves-effect waves-float waves-light mr-3" href="#"
                                        data-id="{{ $item->id }}"
                                        wire:click.prevent="$dispatch('adminDelete', {id: {{ $item->id }}})"
                                        ti`le="Delete">
                                        حذف
                                    </a>
                                    @endcan
                                @endif

                                @can('تفاصيل الموظف')
                                    <a class="btn btn-warning waves-effect waves-float waves-light" title="Show" wire:navigate href="{{ route('emoloyees.show',$item->id) }}" wire:click.prevent="$dispatch('adminsShow', {id: {{ $item->id }}})">
                                        المزيد
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <td colspan="6">
                    <div class="text-danger text-center">لا يوجد بيانات</div>
                </td>
            @endif
        </tbody>
    </table>
    <div class=" mt-2">
           @can('حذف الموظف')
                <p style="color: rgb(153, 93, 51); font-weight: bold"><span style="color: red">ملحوظة</span> عند الحذف سيتم حذف الموظف اذا كان رصيد حسابه الحالي   =  0 </p>
            @endcan
        {{ $data->links() }}
    </div>
</div>
