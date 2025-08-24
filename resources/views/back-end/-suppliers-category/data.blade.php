<div class="table-responsive">
    <div class="card-header ">
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control w-25" placeholder="بحث">
        @can('اضافة قسم جديد')
            <button class="btn btn-primary" wire:click.prevent="$dispatch('suppliersCategoryCreate')">اضافة</button>
        @endcan
    </div>
    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الحالة </th>
                <th>انشاء بواسطة</th>
                <th>اخر تحديث</th>
                <th>كود الشركة</th>
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
                        @if ($item->status == 'active')
                            <td style="background-color: rgb(47, 167, 227);color:#fff" class="text-center">
                                {{ getStatus($item->status) }}</td>
                        @else
                            <td style="background-color: rgb(220, 48, 48);color:#fff" class="text-center">
                                {{ getStatus($item->status) }}</td>
                        @endif



                        <td>{{ $item->adminCreate->name }}</td>
                        <td>
                            {{ last_update($item) }}
                        </td>
                        <td>{{ $item->company_code }}</td>


                        <td>
                            <div class="d-flex align-items-center">
                                @can('تعديل القسم')
                                    <a class="btn btn-primary waves-effect waves-float waves-light" title="Edit"
                                        href="#"
                                        wire:click.prevent="$dispatch('suppliersCategoryUpdate', {id: {{ $item->id }}})">
                                        نعديل
                                    </a>
                                @endcan

                                @can('حذف القسم')
                                    <a class="btn btn-danger waves-effect waves-float waves-light mr-3" href="#"
                                        data-id="{{ $item->id }}"
                                        wire:click.prevent="$dispatch('suppliersCategoryDelete', {id: {{ $item->id }}})"
                                        ti`le="Delete">
                                        حذف
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
        @can('حذف القسم')
            <p style="color: rgb(153, 93, 51); font-weight: bold"><span style="color: red">ملحوظة</span> عند الحذف سيتم حذف القسم و الموردين التابعين له و الحسابات التابعة لهم اذا كانت ارصدتهم الحالية =  0 </p>
        @endcan
        {{ $data->links() }}
    </div>
</div>
