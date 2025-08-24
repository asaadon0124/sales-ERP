<div class="table-responsive">
    <div class="card-header ">
        <input type="text" wire:model.live="search" class="form-control w-25"
            placeholder="بحث">

    </div>
    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
        <thead>
            <tr>
                {{-- <th>#</th> --}}
                <th>الاسم</th>
                <th>هل  ؤئيسية</th>
                <th>اخر ايصال صرف</th>
                <th>اخر ايصال استلام</th>
                  <th>لديها شيفت</th>
                <th>رصيد الخزنة</th>
                <th>كود الشركة</th>
                <th>الاجراءات</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($data))
                @foreach ($data as $item)
                    <tr>
                        {{-- <td>{{ $loop->iteration }}</td> --}}

                        <td>{{ $item->name }}</td>


                        @if ($item->is_master == 'master')
                            <td style="background-color: rgb(47, 167, 227);color:#fff" class="text-center">{{ $item->isMaster() }}</td>
                        @else
                            <td style="background-color: rgb(214, 220, 48);color:#fff" class="text-center">{{ $item->isMaster() }}</td>
                        @endif
                        <td>{{ $item->last_recept_pay }}</td>
                        <td>{{ $item->last_recept_recive }}</td>
                        @if (!empty(treasures_with_Active_shifts($item->id)))
                            <td style="background-color: rgb(88, 209, 88);text-align:center;color:#fff">نعم يوجد</td>
                        @else
                            <td style="background-color: #f00;text-align:center;color:#fff">لا يوجد</td>
                        @endif


                        <td>{{ Treasries_balances($item->id) }} جنيه</td>
                        <td>{{ $item->company_code }}</td>

                        <td>
                            <div class="d-flex align-items-center">
                                @if (empty(treasures_with_Active_shifts($item->id)))
                                    @can('تفعيل الخزن')
                                        <a class="btn btn-success waves-effect waves-float waves-light mr-3" href="#"
                                            data-id="{{ $item->id }}"
                                            wire:click.prevent="$dispatch('treasuriesRestore', {id: {{ $item->id }}})"
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
