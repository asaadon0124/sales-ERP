<div class="table-responsive" wire:ignore.self>

    <div class="card-header bg-light border-bottom">
        <form class="form form-horizontal" wire:submit.prevent='submit'>
            <div class="modal-body">
                <div class="row align-items-end">

                    {{-- اسم المورد --}}
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="font-weight-bold">اسم المورد</label>
                            <select wire:model="account_number" wire:loading.attr="disabled" class="form-control"
                                wire:target="account_number">
                                <option value="">اختار اسم المورد</option>
                                @if (!empty($accounts))
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->account_number }}">{{ $account->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @include('backEnd.error', ['property' => 'account_number'])
                        </div>
                    </div>

                    {{-- تاريخ البداية --}}
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="font-weight-bold">تاريخ البداية</label>
                            <input type="date" class="form-control" wire:model="start_date">
                            @include('backEnd.error', ['property' => 'start_date'])
                        </div>
                    </div>

                    {{-- تاريخ النهاية --}}
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="font-weight-bold">تاريخ النهاية</label>
                            <input type="date" class="form-control" wire:model="end_date">
                            @include('backEnd.error', ['property' => 'end_date'])
                        </div>
                    </div>

                    {{-- زر البحث --}}
                    <div class="col-md-2 d-flex align-items-end mb-4">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fa fa-search mr-1"></i> بحث
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>



    <!-- Nav pills -->
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab"
                aria-controls="pills-home" aria-selected="true">كشف الحساب</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                aria-controls="pills-profile" aria-selected="false">الفواتير</a>
        </li>
    </ul>

    <!-- الرصيد -->
    @if (!empty($supplier))
        <h4 class="text-center mb-4">
            الرصيد الحالي للمورد:
            <strong class="{{ $supplier->current_balance < 0 ? 'text-danger' : 'text-success' }}">
                {{ $supplier->current_balance }}
            </strong>
        </h4>
    @endif

    <!-- Tab content -->
    <div class="tab-content" id="pills-tabContent">

        {{-- كشف الحساب --}}
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            @if (!empty($data) && !empty($supplier))
                <div class="text-center mb-3">
                    <h5 class="text-primary">كشف حساب المورد: <strong>{{ $supplier->name }}</strong></h5>
                    @if ($start_date != '')
                        <p class="text-muted">الفترة من <span class="text-info">{{ $start_date }}</span> إلى <span
                                class="text-info">{{ $end_date }}</span></p>
                    @else
                        <p class="text-muted">(كل الحركات النقدية)</p>
                    @endif
                </div>
            @endif

            <table class="table table-bordered table-striped">
                <thead class="bg-info">
                    <tr>
                        <th>#</th>
                        <th>رقم الحركة</th>
                        <th>نوع الحركة</th>
                        <th>رصيد قبل</th>
                        <th>المبلغ</th>
                        <th>رصيد بعد</th>
                        <th>تاريخ</th>
                        <th>بواسطة</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($data) && !empty($supplier))
                        @php $x = 1; @endphp
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $x++ }}</td>
                                <td>{{ $item->auto_serial }}</td>
                                <td>{{ $item->move_type->name }}</td>
                                <td>{{ $item->account_balance_before }}</td>
                                <td>{{ $item->cash_amount }}</td>
                                <td>{{ $item->account_balance_after }}</td>
                                <td>{{ $item->move_date }}</td>
                                <td>{{ $item->adminCreate->name }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center text-danger">لا يوجد بيانات</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- الفواتير --}}
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            @if (!empty($data_invoices) && !empty($supplier))
                <div class="text-center mb-3">
                    <h5 class="text-primary">فواتير المشتريات من المورد: <strong>{{ $supplier->name }}</strong></h5>
                    @if ($start_date != '')
                        <p class="text-muted">الفترة من <span class="text-info">{{ $start_date }}</span> إلى <span
                                class="text-info">{{ $end_date }}</span></p>
                    @else
                        <p class="text-muted">(كل الحركات النقدية)</p>
                    @endif
                </div>
            @endif

            <table class="table table-bordered table-striped">
                <thead class="bg-info">
                    <tr>
                        <th>#</th>
                        <th>رقم الفاتورة</th>
                        <th>نوع الفاتورة</th>
                        <th>حالة الفاتورة</th>
                        <th>تاريخ</th>
                        <th>الإجمالي قبل الخصم</th>
                        <th>الإجمالي</th>
                        <th>المدفوع</th>
                        <th>المتبقي</th>
                        <th>بواسطة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($data_invoices) && !empty($supplier))
                        @php $x = 1; @endphp
                        @foreach ($data_invoices as $item)
                            <tr>
                                <td>{{ $x++ }}</td>
                                <td>{{ $item->auto_serial }}</td>
                                <td>{{ $item->InvoiceType() }}</td>
                                <td>{{ $item->OrderType() }}</td>
                                <td>{{ $item->order_date }}</td>
                                <td>{{ $item->total_cost_before_all }}</td>
                                <td>{{ $item->total_cost }}</td>
                                <td>{{ $item->paid * -1 }}</td>
                                <td>{{ $item->unpaid }}</td>
                                <td>{{ $item->adminCreate->name }}</td>
                                <td>
                                    @can('تفاصيل فاتورة المشتريات')
                                        <a href="{{ route('purchaseOrders.show', $item->auto_serial) }}"
                                            class="btn btn-warning btn-sm" wire:navigate>
                                            المزيد
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="11" class="text-center text-danger">لا يوجد بيانات</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>





    <div class=" mt-2">
        {{-- {{ $data->links() }} --}}
    </div>
</div>
