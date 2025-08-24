<div class="table-responsive">
    <div class="card-header ">
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control w-25"
            placeholder="بحث باسم الحركة , حالة الحركة , نوع الحركة">

        @can('اضافة نوع حركة النقدية')
            <button class="btn btn-primary" wire:click.prevent="$dispatch('moveTypesCreate')">اضافة</button>
        @endcan

    </div>
    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الحالة </th>
                <th>نوع الحركة </th>
                <th>نوع الحركة </th>
                <th>عدد العمليات </th>
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
                            <td style="background-color: rgb(47, 167, 227);color:#fff" class="text-center">{{ getStatus($item->status)  }}</td>
                        @else
                            <td style="background-color: rgb(220, 48, 48);color:#fff" class="text-center">{{  getStatus($item->status)  }}</td>
                        @endif


                        <td>{{ $item->isPrivate() }}</td>
                        <td>{{ $item->inScrean() }}</td>

                        <td>{{ $item->treasuries_transactions_count  }}</td>
                      @if (isset($item->adminCreate->name))
                        <td>{{ $item->adminCreate->name }}</td>
                      @endif

                        <td>
                            {{ last_update($item) }}
                        </td>
                        <td>{{ $item->company_code }}</td>


                        <td>
                            @if ($item->id > 36)
                                @can('تعديل انواع حركات النقدية')
                                    @if ($item->treasuries_transactions_count == 0)
                                        <div class="d-flex align-items-center">
                                            <a class="btn btn-primary waves-effect waves-float waves-light" title="Edit" href="#" wire:click.prevent="$dispatch('moveTypesUpdate', {id: {{ $item->id }}})">
                                                نعديل
                                            </a>
                                        </div>
                                    @endif
                                @endcan



                                @can('حذف انواع حركات النقدية')
                                    @if ($item->treasuries_transactions_count == 0)
                                        <div class="d-flex align-items-center">
                                        <a class="btn btn-danger waves-effect waves-float waves-light mr-3" href="#"
                                        data-id="{{ $item->id }}"
                                        wire:click.prevent="$dispatch('moveTypesDelete', {id: {{ $item->id }}})"
                                        ti`le="Delete">
                                        حذف
                                    </a>
                                        </div>
                                    @endif
                                @endcan
                             @endif
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
        {{ $data->links() }}
    </div>
</div>
