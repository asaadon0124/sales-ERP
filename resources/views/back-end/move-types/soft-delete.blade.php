<div class="table-responsive">
    <div class="card-header ">
        <input type="text" wire:model.live="search" class="form-control w-25"
            placeholder="بحث">

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
            {{-- {{ $data }} --}}
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
                            <div class="d-flex align-items-center">
                                @if (empty(treasures_with_Active_shifts($item->id)))
                                    @can('تفعيل انواع حركات النقدية')
                                        <a class="btn btn-success waves-effect waves-float waves-light mr-3" href="#"
                                            data-id="{{ $item->id }}"
                                            wire:click.prevent="$dispatch('moveTypeRestore', {id: {{ $item->id }}})"
                                            ti`le="Delete">
                                            تفعيل
                                        </a>
                                    @endcan
                                @endif
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
        {{ $data->links() }}
    </div>
</div>
