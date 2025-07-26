<div class="table-responsive">
    <div class="card-header ">
        <input type="text" wire:model.live="search" class="form-control w-25"
            placeholder="بحث">

        <button class="btn btn-primary" wire:click.prevent="$dispatch('matrialTypeCreate')">Create</button>
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
            @if ($data->count() > 0)
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



                        <td>{{ $item->adminCreate->name }}</td>
                        <td>
                            {{ last_update($item) }}
                        </td>
                        <td>{{ $item->company_code }}</td>

                        <!-- <td>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox"
                                    {{ $item->status == 1 ? 'checked' : '' }}
                                    wire:click="updateStatus({{ $item->id }}, {{ $item->status == 1 ? 0 : 1 }})">
                            </div>
                        </td>  -->
                        <td>
                            <div class="d-flex align-items-center">
                                <a class="btn btn-primary waves-effect waves-float waves-light" title="Edit" href="#" wire:click.prevent="$dispatch('matrialTypeUpdate', {id: {{ $item->id }}})">
                                    نعديل
                                </a>

                                <a class="btn btn-danger waves-effect waves-float waves-light mr-3" href="#"
                                    data-id="{{ $item->id }}"
                                    wire:click.prevent="$dispatch('treasuriesDelete', {id: {{ $item->id }}})"
                                    ti`le="Delete">
                                    حذف
                                </a>


                                <!-- {{-- <a class="btn btn-warning waves-effect waves-float waves-light" title="Show" href="#" wire:click.prevent="$dispatch('treasuriesShow', {id: {{ $item->id }}})">
                                    المزيد
                                </a> --}} -->

                                <a class="btn btn-warning waves-effect waves-float waves-light" title="Show" wire:navigate href="{{ route('treasuries.show',$item->id) }}" wire:click.prevent="$dispatch('treasuriesShow', {id: {{ $item->id }}})">
                                    المزيد
                                </a>

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
