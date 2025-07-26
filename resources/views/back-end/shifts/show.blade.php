<div>
    <div class="card">
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">

                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div id="example1_filter" class="dataTables_filter">

                            {{-- <a href="{{ route('adminSittings.edit',$sitting->id) }}" class="btn btn-info mb-5">تعديل</a> --}}
                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-sm-12">
                        <table id="example1" class="table table-bordered table-striped dataTable" role="grid"
                            aria-describedby="example1_info">
                            @if (!empty($treasury_name))
                                {{-- TREASURY NAME  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">اسم الخزنة</td>
                                    <td>{{ $treasury_name }}</td>
                                </tr>



                                {{-- EMPLOYEE NAME  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">اسم الموظف</td>
                                    <td>{{ $employee_name }}</td>
                                </tr>


                                {{-- SHIFT NUMBER  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">رقم الشيفت</td>
                                    <td>{{ $shift_number }}</td>
                                </tr>


                                {{-- START DATE SHIFT  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">تاريخ بداية الشيفت</td>
                                    <td>{{ $start_date }}</td>
                                </tr>



                                {{-- END DATE SHIFT  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">تاريخ نهاية الشيفت</td>
                                    <td>{{ $end_date }}</td>
                                </tr>



                                {{-- SHIFT STATUS  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">حالة الشيفت</td>
                                    <td>{{ $shift_status }}</td>
                                </tr>


                                {{-- SHIFT REVIEW  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">حالة المراجعة</td>
                                    <td>{{ $shift_review }}</td>
                                </tr>

                                {{-- SHIFT START BALNCE  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">رصيد اول الشيفت</td>
                                    <td>{{ $shift_start_balance }} جنيه</td>
                                </tr>


                                {{-- SHIFT BALNCE  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">رصيد الشيفت</td>
                                    <td>{{ $shift_balance }} جنيه</td>
                                </tr>


                                {{-- SHIFT BALNCE STATUS --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">حالة المديونية </td>
                                    <td>{{ $shift_balance_status }}</td>
                                </tr>



                                {{-- PAID --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">المبلغ الذي تم تحصيله </td>
                                    <td>{{ $paid }} جنيه</td>
                                </tr>



                                {{-- UN PAID --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">المبلغ المتبقي (عجز او زيادة)
                                    </td>
                                    <td>{{ $unpaid }} جنيه</td>
                                </tr>



                                {{-- APROVED BY --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;"> اسم الموظف الي استلم الشيفت و
                                        راجعه </td>
                                    <td>{{ $delevered_to_admin_id }}</td>
                                </tr>



                                {{-- APROVED BY SHIFT ID --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;"> رقم الشيفت الي استلم الشيفت و
                                        راجعه </td>
                                    <td>{{ $delevered_to_shift_id }}</td>
                                </tr>


                                {{-- APROVED BY TREASURY ID --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;"> اسم الخزنة الي استلم الشيفت و
                                        راجعه </td>
                                    <td>{{ $delevered_to_treasury_id }}</td>
                                </tr>


                                {{-- هل تم مراجعة الشيفت من نفس الخزنة ولا خزنة اخري --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;"> هل تم مراجعة الشيفت من نفس
                                        الخزنة ولا خزنة اخري </td>
                                    <td>{{ $recive_type }}</td>
                                </tr>



                                {{-- Review_recive_date --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;"> تاريخ مراجعة الشيفت </td>
                                    <td>{{ $Review_recive_date }}</td>
                                </tr>






                                {{-- COMPANY CODE  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">كود الشركة</td>
                                    <td>{{ $company_code }}</td>
                                </tr>





                                {{-- COMPANY IS_MASTER  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">نوع الخزنة</td>
                                    <td>{{ $this->isMaster() }}</td>
                                </tr>



                                {{-- EMPLOYEE CURRENT BALANCE  --}}
                                <tr role="row">
                                    <td style="width: 30%;background-color:#d6cacae6;">الرصيد الحالي للموظف صاحب الشيفت
                                    </td>
                                    <td>{{ $admin_current_balance }}</td>
                                </tr>
                            @else
                                <div class="alert alert-danger text-center mt-4">
                                    لا يوجد بيانات
                                </div>
                            @endif

                        </table>
                    </div>
                </div>

                <h4 class="text-center mb-5 mt-5">تفاصيل حركات الخزنة خلال الشيفت رقم {{ $shiftId }}</h4>

                @if (!empty($data))



                    <input type="text" wire:model.live="search" class="form-control w-25" placeholder="بحث">
            </div>
            <div>
                <table class="table table-bordered table-striped dataTable" style="margin: 10px">
                    <thead>
                        <tr>
                            <th>رقم العملية</th>
                            <th>اسم العملية</th>
                            <th>اسم الحساب</th>
                            <th>نوع الحساب </th>
                            <th>نوع الفاتورة </th>
                            <th>حالة الفاتورة </th>
                            <th>المبلغ المحصل</th>
                            <th>المبلغ المصروف</th>
                            <th> تاريخ العميلة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $x = 1;
                        @endphp
                        @if (!empty($data))
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->auto_serial }}</td>
                                    <td>
                                        {{ $item->move_type->name }}
                                    </td>
                                    <td>{{ $item->account->name }}</td>
                                    <td>{{ $item->Account_type() }}</td>
                                    <td>{{ $item->Invoice_type_accounts() }}</td>
                                    <td>{{ $item->Invoice_type() }}</td>

                                    <td style="color: green;">
                                        {{ $item->cash_amount > 0 ? $item->cash_amount . ' جنيه' : '0 جنيه' }}
                                    </td>
                                    <td style="color: red;">
                                        {{ $item->cash_amount < 0 ? $item->cash_amount . ' جنيه' : '0 جنيه' }}
                                    </td>
                                    <td>{{ $item->move_date }} </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class=" mt-2">
                    {{ $data->links() }}
                </div>
            </div>
            @endif


        </div>
    </div>
</div>
</div>
