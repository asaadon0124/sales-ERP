<div class="table-responsive">
    <div class="card-header ">
        <input type="text" wire:model.live="search" class="form-control w-25"
            placeholder="بحث">
        @can('اضافة خزنة')
            <button class="btn btn-primary" wire:click.prevent="$dispatch('treasuriesCreate')">انشاء خزنة جديدة</button>
        @endcan
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


                        <td>{{ Treasries_balances($item->id) }}</td>
                        <td>{{ $item->company_code }}</td>
                        {{-- <td>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox"
                                    {{ $item->status == 1 ? 'checked' : '' }}
                                    wire:click="updateStatus({{ $item->id }}, {{ $item->status == 1 ? 0 : 1 }})">
                            </div>
                        </td> --}}
                        <td>
                            <div class="d-flex align-items-center">
                                @if (empty(treasures_with_Active_shifts($item->id)))
                                    @can('تعديل الخزن')
                                        <a class="btn btn-primary waves-effect waves-float waves-light" title="Edit" href="#" wire:click.prevent="$dispatch('treasuriesUpdate', {id: {{ $item->id }}})">
                                            نعديل
                                        </a>
                                    @endcan

                                    @can('حذف الخزن')
                                        <a class="btn btn-danger waves-effect waves-float waves-light mr-3" href="#"
                                            data-id="{{ $item->id }}"
                                            wire:click.prevent="$dispatch('treasuriesDelete', {id: {{ $item->id }}})"
                                            ti`le="Delete">
                                            حذف
                                        </a>
                                    @endcan
                                @endif

                                {{-- <a class="btn btn-warning waves-effect waves-float waves-light" title="Show" href="#" wire:click.prevent="$dispatch('treasuriesShow', {id: {{ $item->id }}})">
                                    المزيد
                                </a> --}}

                                @can('تفاصيل الخزن')
                                    <a class="btn btn-warning waves-effect waves-float waves-light" title="Show" wire:navigate href="{{ route('treasuries.show',$item->id) }}" wire:click.prevent="$dispatch('treasuriesShow', {id: {{ $item->id }}})">
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
    @can('حذف الخزن')
        <p style="color: red">لا يمكن حذف الخزنة اذا كانت لا تحتوي علي خزن فرعية و ليس لديها شيفت مفعل او لم تتم مراجعته</p>
    @endcan
    <div class=" mt-2">
        {{ $data->links() }}
    </div>
</div>
