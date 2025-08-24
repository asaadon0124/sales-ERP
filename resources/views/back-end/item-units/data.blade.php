<div class="table-responsive">
    <div class="card-header ">
        <input type="text" wire:model.live="search" class="form-control w-25"
            placeholder="بحث">
        @can('اضافة وحدة صنف جديدة')
            <button class="btn btn-primary" wire:click.prevent="$dispatch('itemUnitsCreate')">اضافة</button>
        @endcan

    </div>
    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الحالة </th>
                <th>نوع الوحدة </th>
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


                        @if ($item->is_master == 'master')
                            <td style="background-color: rgb(47, 167, 227);color:#fff" class="text-center">{{ $item->IsMaster()  }}</td>
                        @else
                            <td style="background-color: rgb(37, 236, 23);color:#fff" class="text-center">{{  $item->IsMaster()  }}</td>
                        @endif



                        <td>{{ $item->adminCreate->name }}</td>
                        <td>
                            {{ last_update($item) }}
                        </td>
                        <td>{{ $item->company_code }}</td>

                        <td>
                            <div class="d-flex align-items-center">
                                @can('تعديل وحدة الصنف')
                                    <a class="btn btn-primary waves-effect waves-float waves-light" title="Edit" href="#" wire:click.prevent="$dispatch('itemUnitsUpdate', {id: {{ $item->id }}})">
                                        نعديل
                                    </a>
                                @endcan


                                @can('حذف وحدة الصنف')
                                    <a class="btn btn-danger waves-effect waves-float waves-light mr-3" href="#"
                                        data-id="{{ $item->id }}"
                                        wire:click.prevent="$dispatch('itemUnitsDelete', {id: {{ $item->id }}})"
                                        ti`le="Delete">
                                        حذف
                                    </a>
                                 @endcan

                                <!-- {{-- <a class="btn btn-warning waves-effect waves-float waves-light" title="Show" href="#" wire:click.prevent="$dispatch('treasuriesShow', {id: {{ $item->id }}})">
                                    المزيد
                                </a> --}} -->

                                {{-- <a class="btn btn-warning waves-effect waves-float waves-light" title="Show" wire:navigate href="{{ route('treasuries.show',$item->id) }}" wire:click.prevent="$dispatch('treasuriesShow', {id: {{ $item->id }}})">
                                    المزيد
                                </a> --}}

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
        @can('حذف وحدة الصنف')
            <p style="color: rebeccapurple"><span style="color: red">ملحوظة</span> عند الحذف سيتم حذف الوحدة اذاكانت لا تحتوي علي اصناف او الاصناف بداخلها كمياتها  ==  0 </p>
        @endcan
        {{ $data->links() }}
    </div>
</div>
