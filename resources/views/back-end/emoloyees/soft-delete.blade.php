<div class="table-responsive">
    <div class="card-header ">
        <div class="row">
            <div class="col-sm-4">
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="form-control"placeholder="  بحث عن اسم - الحساب اب - رقم الحساب ">
            </div>

            <div class="col-sm-4">
                <select wire:model.lazy="searchSupplierType" class="form-control">
                    <option value="">كل انواع الحسابات</option>
                    @if (!empty($acountsTypes))
                        @foreach ($acountsTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    @endif
                </select>

            </div>

            <div class="col-sm-4">
                <select wire:model.lazy="searchSupplierBalanceType" class="form-control">
                    <option value=""> حالة الرصيد اول المدة</option>
                    <option value="credit">مدين</option>
                    <option value="debit">دائن</option>
                    <option value="nun">متزن</option>
                </select>

            </div>
        </div>



    </div>
    <table class="table table-bordered table-striped dataTable" style="margin: 10px">
        <thead>
            <tr>
                <th>#</th>
                <th>اسم الحساب</th>
                <th> حالة رصيد اول المدة</th>
                <th>رصيد اول المدة </th>
                <th>الرصيد الحالي</th>
                {{-- <th>الحالة </th> --}}
                <th>انشاء بواسطة</th>
                <th>اخر تحديث</th>
                <th>رقم الحساب</th>
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


                        <td>{{ $item->started_balance_status() }}</td>
                        <td>{{ $item->start_balance }}</td>
                        {{-- <td>{{ $item->current_balance }}</td> --}}
                        @if ($item->current_balance > 0)
                            <td style="background-color: rgb(47, 167, 227);color:#fff" class="text-center">
                                {{ $item->current_balance }} جنيه</td>
                        @elseif($item->current_balance == 0)
                            <td style="background-color: rgb(32, 194, 135);color:#fff" class="text-center">
                                {{ $item->current_balance }} جنيه</td>
                        @else
                            <td style="background-color: rgb(220, 48, 48);color:#fff" class="text-center">
                                {{ $item->current_balance }} جنيه</td>
                        @endif


                        <td>{{ $item->adminCreate->name }}</td>
                        <td>
                            {{ last_update($item) }}
                        </td>
                        <td>{{ $item->account_number }}</td>


                        <td>
                            <div class="d-flex align-items-center">

                                @can('تفعيل مورد')
                                    <a class="btn btn-success waves-effect waves-float waves-light mr-3" href="#"
                                        data-id="{{ $item->id }}"
                                        wire:click.prevent="$dispatch('adminRestore', {id: {{ $item->id }}})"
                                        ti`le="Restore">
                                        تفعيل
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
    <div class=" mt-2">
        {{ $data->links() }}
    </div>
</div>
