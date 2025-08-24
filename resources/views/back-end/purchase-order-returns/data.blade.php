<div class="table-responsive">
    <div class="card-header ">
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control w-25" placeholder="بحث">
        @if (!empty($get_active_shift))
            @can('اضافة فاتورة مرتجع المشتريات')
                <button class="btn btn-primary" wire:click.prevent="$dispatch('purchaseOrderReturnsCreate')">اضافة</button>
            @endcan
        @endif

    </div>
    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
        <thead>
            <tr>
                <th>#</th>
                <th>كود الفاتورة</th>
                <th> تاريخ الفاتورة</th>
                <th> نوع الفاتورة</th>
                <th> اسم المورد</th>
                <th> المخزن المستلم</th>
                <th> اجمالي الفاتورة</th>
                <th>حالة الفاتورة</th>
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
                        <td>{{ $item->auto_serial }}</td>
                        <td>{{ $item->order_date }}</td>
                        <td>{{ $item->invoiceType() }}</td>
                        @if (isset($item->supplier->name))
                            <td>{{ $item->supplier->name }}</td>
                        @else
                            <td></td>
                        @endif

                        @if (isset($item->store->name))
                            <td>{{ $item->store->name }}</td>
                        @endif
                        <td>{{ $item->total_cost }}</td>

                        @if ($item->approve == '1')
                            <td style="background-color: rgb(47, 167, 227);color:#fff" class="text-center">
                                {{ $item->approval() }}</td>
                        @else
                            <td style="background-color: rgb(220, 48, 48);color:#fff" class="text-center">
                                {{ $item->approval() }}</td>
                        @endif



                        <td>
                            {{ last_update($item) }}
                        </td>
                        <td>{{ $item->company_code }}</td>


                        <td>
                            @if ($item->approve == 0 && !empty($get_active_shift))
                                <div class="d-flex align-items-center">
                                    @can('تعديل فاتورة مرتجع المشتريات')
                                        <a class="btn btn-primary waves-effect waves-float waves-light" title="Edit"
                                            href="#"
                                            wire:click.prevent="$dispatch('purchaseOrderReturnsUpdate', {id: {{ $item->auto_serial }}})">
                                            نعديل
                                        </a>
                                    @endcan


                                    @can('حذف فاتورة مرتجع المشتريات')
                                        <a class="btn btn-danger waves-effect waves-float waves-light mr-3" href="#"
                                            data-id="{{ $item->id }}"
                                            wire:click.prevent="$dispatch('purchaseOrderReturnsDelete', {id: {{ $item->auto_serial }}})"
                                            ti`le="Delete">
                                            حذف
                                        </a>
                                    @endcan

                                    @if ($item->order_detailes->count() > 0)
                                        @can('اعتماد فاتورة مرتجع المشتريات')
                                            <a class="btn btn-success waves-effect waves-float waves-light mr-3"
                                                href="#" data-id="{{ $item->id }}"
                                                wire:click.prevent="$dispatch('purchaseOrderReturnsApprove', {id: {{ $item->auto_serial }}})"
                                                title="Approve">
                                                اعتمد
                                            </a>
                                        @endcan
                                    @endif
                            @endif



                            @can('تفاصيل فاتورة مرتجع المشتريات')
                                <a class="btn btn-warning waves-effect waves-float waves-light" title="Show" wire:navigate
                                    href="{{ route('purchaseOrders.show_returns', $item->auto_serial) }}">
                                    المزيد
                                </a>
                            @endcan

</div>
</td>
</tr>
@endforeach
@else
<td colspan="12">
    <div class="text-danger text-center">لا يوجد بيانات</div>
</td>
@endif
</tbody>
</table>
<div class=" mt-2">
    {{ $data->links() }}
</div>
</div>
