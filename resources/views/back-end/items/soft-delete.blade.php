<div class="table-responsive">
    <div class="card-header ">
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control w-25" placeholder="بحث">

    </div>
    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>النوع </th>
                <th>الفئة </th>
                <th>الصنف الاب </th>
                <th>الوحدة التجزئة </th>
                <th>الحالة</th>
                <th>انشاء بواسطة</th>
                <th>تحديث بواسطة</th>
                <th>كود الشركة</th>
                <th>الاجراءات</th>
            </tr>
        </thead>
        <tbody>





            @forelse ($data as $item)
                <tr>

                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ getItemType($item->item_type) }}</td>
                    <td>{{ $item->itemCategory?->name }}</td>
                    <td>{{ $item->itemUnit?->name }}</td>

                    <td>{{ $item->itemUnitChild->name ?? 'غير موجود' }}</td>


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

                                @can('تفعيل صنف')
                                    <a class="btn btn-success waves-effect waves-float waves-light mr-3" href="#"
                                        data-id="{{ $item->id }}"
                                        wire:click.prevent="$dispatch('itemRestore', {id: {{ $item->id }}})"
                                        ti`le="Delete">
                                        تفعيل
                                    </a>
                                @endcan

                            </div>
                        </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12">
                        <div class="text-danger text-center">لا يوجد بيانات</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class=" mt-2">
        {{ $data->links() }}
    </div>
</div>
