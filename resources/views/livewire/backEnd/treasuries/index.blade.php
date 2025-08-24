<div>
    <div class="card">

        <!-- /.card-header -->
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="example1_length"><label>Show <select name="example1_length"
                                    aria-controls="example1"
                                    class="custom-select custom-select-sm form-control form-control-sm">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> entries</label></div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div id="example1_filter" class="dataTables_filter"><label>Search:<input type="search"
                                    class="form-control form-control-sm" placeholder=""
                                    aria-controls="example1"></label></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">

                        <button wire:navigate href="{{ route('treasuries.create') }}" class="btn btn-primary mb-5 mt-5">اضافة خزنة جديدة</button>
                        <table id="example1" class="table table-bordered table-striped dataTable" role="grid"
                            aria-describedby="example1_info">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-sort="ascending"
                                        aria-label="Rendering engine: activate to sort column descending"
                                        style="width: 106.925px;">#
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="Browser: activate to sort column ascending"
                                        style="width: 123.713px;">اسم الخزنة
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="Platform(s): activate to sort column ascending"
                                        style="width: 127.137px;">حالة الخزنة
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="Engine version: activate to sort column ascending"
                                        style="width: 84.025px;">نوع الخزنة
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="CSS grade: activate to sort column ascending"
                                        style="width: 60.6px;"> اخر ايصال صرف
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="CSS grade: activate to sort column ascending"
                                        style="width: 60.6px;"> اخر ايصال تحصيل
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="CSS grade: activate to sort column ascending"
                                        style="width: 60.6px;"> تاريخ الاضافة
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="CSS grade: activate to sort column ascending"
                                        style="width: 60.6px;"> تاريخ التحديث
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="CSS grade: activate to sort column ascending"
                                        style="width: 60.6px;">  الاجرائات
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $x = 1;
                                @endphp
                                @foreach ($treasuries as $treasurie)
                                    <tr role="row" class="odd">
                                        <td class="sorting_1">{{$x++}}</td>
                                        <td>{{ $treasurie->name }}</td>
                                        <td>{{ $treasurie->status }}</td>
                                        <td>{{ $treasurie->is_master }}</td>
                                        <td>{{ $treasurie->last_recept_pay }}</td>
                                        <td>{{ $treasurie->last_recept_recive }}</td>
                                        <td>{{ $treasurie->created_at }}</td>
                                        <td>  {{ last_update($treasurie) }}</td>
                                        <td>
                                            {{-- <button wire:navigate href="{{route(name: 'treasuries.edit',[$treasurie->id])}}" class="btn btn-info">تعديل</button> --}}
                                            <button class="btn btn-danger">حذف</button>
                                            {{-- <button @livewire('back-end.treasuries.edit',['id' => $treasurie->id])>Edit</button> --}}
                                            {{-- <button wire:click.prevent="$dispatch('editTreasuries',{id:{{$treasuries->id}}})"> Edit</button> --}}
                                            {{-- <a href="#" wire:click.prevent="$dispatch('editTreasuries')">Edit</a> --}}
                                            <a class="btn btn-primary waves-effect waves-float waves-light"
                                                title="{{ __('dashboard.update') }}" href="#"
                                                wire:click.prevent="$dispatch('editTreasuries', {id: {{ $treasurie->id }}})">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing 1
                            to 10 of 57 entries</div>
                    </div>

                    {{ $treasuries->links() }}
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
</div>
