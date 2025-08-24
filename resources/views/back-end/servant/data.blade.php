<div class="table-responsive">
    <div class="card-header ">
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control w-25" placeholder="بحث">

        @can('اضافة مندوب جديد')
            <button class="btn btn-primary" wire:click.prevent="$dispatch('servantCreate')">اضافة</button>
        @endcan
    </div>
    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
        <thead>
            <tr>
                <th>#</th>
                <th>اسم المندوب</th>
                <th>كود المندوب</th>
                <th>رقم الحساب المندوب</th>
                <th>حالة رصيد اول المدة</th>
                <th>حالة اجر المندوب</th>
                <th>رصيد اول المدة </th>
                <th>الرصيد الحالي </th>
                <th>الحالة </th>
                <th>اخر تحديث</th>
                <th>الاجراءات</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($data))
                @php
                    $x = 1;
                @endphp
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $x++ }}</td>
                        <td>{{ $item->name }}</td>


                        <td>{{ $item->servant_code }}</td>
                        <td>{{ $item->account_number }}</td>
                        <td>{{ $item->started_balance_status() }}</td>
                        <td>{{ $item->salary_status() }}</td>
                        <td>{{ $item->start_balance }}</td>
                        <td>{{ $item->current_balance }}</td>
                        @if ($item->status == 'active')
                            <td style="background-color: rgb(47, 167, 227);color:#fff" class="text-center">
                                {{ getStatus($item->status) }}</td>
                        @else
                            <td style="background-color: rgb(220, 48, 48);color:#fff" class="text-center">
                                {{ getStatus($item->status) }}</td>
                        @endif

                        <td>
                            {{ last_update($item) }}
                            بواسطة {{ $item->adminCreate->name }}
                        </td>


                        <td>
                            <div class="d-flex align-items-center">
                                @can('تعديل المندوب')
                                    <a class="btn btn-primary waves-effect waves-float waves-light" title="Edit"
                                        href="#"
                                        wire:click.prevent="$dispatch('servantUpdate', {id: {{ $item->id }}})">
                                        نعديل
                                    </a>
                                @endcan

                                @can('حذف المندوب')
                                    <a class="btn btn-danger waves-effect waves-float waves-light mr-3" href="#"
                                        data-id="{{ $item->id }}"
                                        wire:click.prevent="$dispatch('servantDelete', {id: {{ $item->id }}})"
                                        ti`le="Delete">
                                        حذف
                                    </a>
                                @endcan

                                @can('تفاصيل المندوب')
                                    <a class="btn btn-warning waves-effect waves-float waves-light" title="Show"
                                        href="#"
                                        wire:click.prevent="$dispatch('servantShow', {id: {{ $item->id }}})">
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
        @can('حذف المندوب')
            <p style="color: rgb(153, 93, 51); font-weight: bold"><span style="color: red">ملحوظة</span> عند الحذف سيتم حذف
                المندوب اذا كان رصيد حسابه الحالي = 0 </p>
        @endcan
        {{ $data->links() }}
    </div>
</div>
