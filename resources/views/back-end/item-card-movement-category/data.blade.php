<div class="table-responsive" wire:ignore.self>

    <div class="card-header ">
        <input type="text" wire:model.live="search" class="form-control w-25"
            placeholder="بحث">
        @can('اضافة فئة حركة جديدة للصنف')
            <button class="btn btn-primary" wire:click.prevent="$dispatch('iteCardMovementsCategoryCreate')">اضافة</button>
        @endcan
    </div>
    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>انشاء بواسطة</th>
                <th>اخر تحديث</th>
                <th>كود الشركة</th>
                <th>الاجراءات</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($data))
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
                                @can('تعديل فئة حركة الصنف')
                                    <a class="btn btn-primary" href="#" wire:click.prevent="$dispatch('iteCardMovementsCategoryUpdate', {id: {{ $item->id }}})">تعديل</a>
                                @endcan
                                {{-- @can('حذف فئة حركة الصنف')
                                    <a class="btn btn-danger mx-2" href="#" wire:click.prevent="$dispatch('iteCardMovementsCategoryDelete', {id: {{ $item->id }}})">حذف</a>
                                @endcan --}}
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
         {{-- @can('حذف فئة حركة الصنف')
            <p style="color: rgb(153, 93, 51); font-weight: bold"><span style="color: red">ملحوظة</span> عند الحذف سيتم حذف فئة حركة الصنف اذا كان لا يحتوي علي اصناف  او سيتم حذف الاصناف التابعة لها </p>
        @endcan --}}
        {{ $data->links() }}
    </div>
</div>
