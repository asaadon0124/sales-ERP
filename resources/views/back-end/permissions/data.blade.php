<div class="table-responsive">
    <div class="card-header ">
         <input type="text" wire:model.live="search" class="form-control w-25 mb-4"
            placeholder="بحث" >


        <button class="btn btn-primary" wire:click.prevent="$dispatch('permissionCreate')">اضافة</button>
    </div>
    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
        <thead>
            <tr>
                <th>#</th>
                <th>اسم الصلاحية</th>
                {{-- <th>اخر تحديث</th>  --}}
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

                        {{-- <td>
                            {{ last_update($item) }}
                             بواسطة  {{ $item->adminCreate->name }}
                        </td> --}}


                        <td>
                            <div class="d-flex align-items-center">
                                <a class="btn btn-primary waves-effect waves-float waves-light" title="Edit" href="#" wire:click.prevent="$dispatch('permissionUpdate', {id: {{ $item->id }}})">
                                    نعديل
                                </a>

                                <a class="btn btn-danger waves-effect waves-float waves-light mr-3" href="#"
                                    data-id="{{ $item->id }}"
                                    wire:click.prevent="$dispatch('permissionDelete', {id: {{ $item->id }}})"
                                    ti`le="Delete">
                                    حذف
                                </a>

                                <a class="btn btn-warning waves-effect waves-float waves-light" title="Show" href="#" wire:click.prevent="$dispatch('permissionShow', {id: {{ $item->id }}})">
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
