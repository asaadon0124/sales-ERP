<div class="table-responsive">
    <div class="card-header ">
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control w-25"
            placeholder="بحث">

            @can('اضافة شيفت')
                 @if (!$shift_created)
                    @if (isset($check_admin_active_Treasury_Shift) && $check_admin_active_Treasury_Shift->count() != 0)
                        <button class="btn btn-primary" wire:click.prevent="$dispatch('shiftsCreate')">فتح شيفت جديد</button>
                    @endif
                @endif
            @endcan


    </div>
    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
        <thead>
            <tr>
                <th>كود الشيفت</th>
                <th>اسم الخزنة</th>
                <th>اسم المستخدم </th>
                <th>حالة الشيفت </th>
                <th>حالة المراجعة </th>
                <th>رصيد الشيفت </th>
                <th>رصيد بداية الشيفت </th>
                <th>تاريخ بداية الشيفت</th>
                <th>انشاء بواسطة</th>
                <th>اخر تحديث</th>
                <th>الاجراءات</th>
            </tr>
        </thead>
        <tbody>
            @if ($data->count() > 0)
                @php
                    $x = 1;
                @endphp
                @foreach ($data as $item)
                    <tr>

                        <td>{{ $item->auto_serial }}</td>
                        <td>{{ $item->treasury->name ?? 'غير متاح' }}</td>
                        <td>{{ $item->admin->name }}</td>
                        @if ($item->shift_status == 'active')
                            <td style="background-color: rgb(47, 167, 227);color:#fff" class="text-center">{{ getStatus($item->shift_status)  }}</td>
                        @else
                            <td style="background-color: rgb(220, 48, 48);color:#fff" class="text-center">{{  getStatus($item->shift_status)  }}</td>
                        @endif


                        @if ($item->is_delevered_review == 'yes')
                            <td style="background-color: rgb(47, 167, 227);color:#fff" class="text-center">{{ $item->isReview()  }}</td>
                        @else
                            <td style="background-color: rgb(220, 48, 48);color:#fff" class="text-center">{{  $item->isReview()  }}</td>
                        @endif

                        <td>{{ shifts_balances($item->auto_serial) }} جنيه </td>
                        <td>{{ shifts_start_balances($item->auto_serial) }} جنيه </td>
                        <td>{{ $item->start_date }}</td>
                        <td>{{ $item->adminCreate->name ?? '**' }}</td>
                        <td>
                            {{ last_update($item) }}
                        </td>


                        <td>

                            <div class="d-flex align-items-center">
                                @can('انهاء الشيفت')
                                    @if ($item->shift_status == 'active')
                                        <a class="btn btn-primary waves-effect waves-float waves-light" title="Edit" href="#" wire:click.prevent="$dispatch('shiftsUpdate', {id: {{ $item->auto_serial }}})">
                                            انهاء الشيفت
                                        </a>
                                    @endif
                                @endcan

                                @can('مراجعة الشيفت')
                                    @if ($item->shift_status == 'un_active' && $item->is_delevered_review == 'no' && Active_shift())
                                        <a class="btn btn-success waves-effect waves-float waves-light mr-3" href="#"
                                            data-id="{{ $item->id }}"
                                            wire:click.prevent="$dispatch('shiftAprove', {id: {{ $item->auto_serial }}})"
                                            ti`le="Aprove">
                                            مراجعة الشيفت
                                        </a>
                                    @endif
                                @endcan



                                <!-- {{-- <a class="btn btn-warning waves-effect waves-float waves-light" title="Show" href="#" wire:click.prevent="$dispatch('treasuriesShow', {id: {{ $item->id }}})">
                                    المزيد
                                </a> --}} -->

                                <a class="btn btn-warning waves-effect waves-float waves-light" title="Show" wire:navigate href="{{ route('shifts.show',$item->auto_serial) }}" wire:click.prevent="$dispatch('shiftsShow', {id: {{ $item->auto_serial }}})">
                                    المزيد
                                </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <td colspan="6">
                    <tr>
                    <div class="text-danger text-center">لا يوجد بيانات</div>
                </tr>
                </td>
            @endif
        </tbody>
    </table>
    @can('مراجعة الشيفت')
        <p style="color: red"> لمراجعة الشيفت يجب ان يكون الشيفت منتهي و هناك شيفت مفتوح للمستخدم الدي سيراجع الشيفت</p>
    @endcan
    <div class=" mt-2">
        {{ $data->links() }}
    </div>
</div>
