<div class="table-responsive" wire:ignore.self>

    <div class="card-header ">
        <input type="text" wire:model.live="search" class="form-control w-25"
            placeholder="بحث">
        @can('اضافة حركة جديدة للصنف')
            <button class="btn btn-primary" wire:click.prevent="$dispatch('iteCardMovementsTypeCreate')">اضافة</button>
        @endcan
    </div>
    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
        <thead>
            <tr>
                <th>#</th>
                <th>اسم حركة الصنف</th>
                <th>انشاء بواسطة</th>
                <th>اخر تحديث</th>
                <th>كود الشركة</th>
                <th>الاجراءات</th>
            </tr>
        </thead>
        <tbody>
            @if ($data->count() > 0)
                @php $x = 1; @endphp
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $x++ }}</td>
                        <td>{{ $item->name }}</td>



                        <td>{{ $item->adminCreate->name ?? '-' }}</td>

                        <td>{{ last_update($item) }}</td>
                        <td>{{ $item->company_code }}</td>

                        <td>
                            <div class="d-flex align-items-center">
                                @can('تعديل حركة الصنف')
                                    <a class="btn btn-primary" href="#" wire:click.prevent="$dispatch('iteCardMovementsTypeUpdate', {id: {{ $item->id }}})">تعديل</a>
                                @endcan
                                {{-- <a class="btn btn-danger mx-2" href="#" wire:click.prevent="$dispatch('treasuriesDelete', {id: {{ $item->id }}})">حذف</a> --}}
                                {{-- <a class="btn btn-warning" wire:navigate href="{{ route('treasuries.show', $item->id) }}">المزيد</a> --}}
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7">
                        <div class="text-danger text-center">لا يوجد بيانات</div>
                    </td>
                </tr>
            @endif
        </tbody>

    </table>
    <div class=" mt-2">
        {{ $data->links() }}
    </div>
</div>
