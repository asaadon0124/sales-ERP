<div class="table-responsive">
    <div class="card-header ">
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control w-25" placeholder="بحث">
        @can('اضافة صنف جديد')
            <button class="btn btn-primary" wire:navigate href="{{ route('items.create') }}">اضافة</button>
        @endcan
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
                <th>عدد الوحدات الفرعية في الوحدة الاب</th>
                <th>الكمية في المخازن</th>
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
                      <td>{{ $item->qty_sub_item_unit }}</td>


                    @php
                        $qty_after_all_stores   = \App\Models\ItemBatch::where('item_code', $item->item_code)->sum('qty');
                        $total_deduction        = \App\Models\ItemBatch::where('item_code', $item->item_code)->sum('deduction');
                    @endphp
                    <td>{{ $qty_after_all_stores - $total_deduction }}</td>
                    {{-- @if ($item->status == 'active')
                        <td style="background-color: rgb(47, 167, 227);color:#fff" class="text-center">
                            {{ getStatus($item->status) }}</td>
                    @else
                        <td style="background-color: rgb(220, 48, 48);color:#fff" class="text-center">
                            {{ getStatus($item->status) }}</td>
                    @endif --}}



                    <td>{{ $item->adminCreate->name }}</td>
                    <td>
                        {{ last_update($item) }}
                    </td>
                    <td>{{ $item->company_code }}</td>


                    <td>
                        <div class="d-flex align-items-center">
                            @can('تعديل صنف')
                                 <a class="btn btn-primary waves-effect waves-float waves-light" title="Edit" wire:navigate
                                    href="{{ route('items.edit', $item->id) }}">
                                    نعديل
                                </a>
                            @endcan

                            @can('حذف صنف')
                                <a class="btn btn-danger waves-effect waves-float waves-light mr-3" href="#"
                                    data-id="{{ $item->id }}"
                                    wire:click.prevent="$dispatch('itemsDelete', {id: {{ $item->id }}})"
                                    ti`le="Delete">
                                    حذف
                                </a>
                            @endcan





                            @can('تفاصيل الصنف')
                                <a class="btn btn-warning waves-effect waves-float waves-light" title="Show" wire:navigate
                                    href="{{ route('items.show', $item->id) }}">
                                    المزيد
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
        @can('حذف صنف')
            <p style="color: rgb(153, 93, 51); font-weight: bold"><span style="color: red">ملحوظة</span> عند الحذف سيتم حذف الصنف اذا كان لا يحتوي علي كميات في المخازن او الكميات  =  0 </p>
        @endcan
        {{ $data->links() }}
    </div>
</div>
