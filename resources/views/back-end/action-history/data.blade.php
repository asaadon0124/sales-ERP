<div class="table-responsive" wire:ignore.self>

    <div class="card-header ">
        <input type="text" wire:model.live="search" class="form-control w-25"
            placeholder="بحث">

    </div>
    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
        <thead>
            <tr>
                <th>#</th>
                <th>اسم الحركة</th>
                <th>تفاصيل الحركة </th>
                <th>انشاء بواسطة</th>
                <th>تاريخ الحركة</th>

            </tr>
        </thead>
        <tbody>
            @if ($data->count() > 0)
                @php $x = 1; @endphp
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $x++ }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->desc }}</td>
                        <td>{{ $item->adminCreate->name ?? '-' }}</td>
                        <td>{{ $item->created_at}}</td>
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
