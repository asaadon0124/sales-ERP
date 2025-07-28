<div class="modal fade text-start modal-primary" id="aproveModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">اعتماد فاتورة رقم  {{ $auto_serial }}  </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form form-horizontal" wire:submit.prevent='submit'>
                <div class="modal-body">
                    <div class="row">

                        {{-- اجمالي الفاتورة قبل الخصم و الضرائب    total_cost_before_all  --}}
                        <div class="col-sm-12 mb-4">
                            <label>اجمالي الفاتورة قبل الخصم و الضرائب </label>
                            <input type="number" readonly class="form-control" wire:model="total_cost_before_all">
                        </div>



                        {{-- نسبة الضريبة  tax_percent --}}
                        <div class="col-sm-6 mb-4">
                            <label> نسبة الضريبة </label>
                            <input type="number" class="form-control" wire:model="tax_percent" wire:change="change_tax_percent($event.target.value)" max="100">
                            @include('backEnd.error', ['property' => 'tax_percent'])
                        </div>



                         {{-- قيمة الضريبة  tax_value --}}
                         <div class="col-sm-6 mb-4">
                            <label> قيمة الضريبة </label>
                            <input type="number" readonly class="form-control" wire:model.live="tax_value">
                            @include('backEnd.error', ['property' => 'tax_value'])

                        </div>


                        {{--  اجمالي الفاتورة قبل الخصم  tax_percent --}}
                        <div class="col-sm-6 mb-4">
                            <label>  اجمالي الفاتورة قبل الخصم </label>
                            <input type="number" class="form-control" readonly value="{{ number_format($total_before_discount, 2, '.', '') }}">
                        </div>


                        {{-- نوع الخصم discount_type  --}}
                        <div class="col-sm-6 mb-4">
                            <label>نوع الخصم</label>
                            <select wire:model="status" wire:loading.attr="disabled" class="form-control" wire:target="discount_type" wire:change="change_discount_type($event.target.value)">
                                <option value="">اختار نوع الخصم</option>
                                <option value="0">قيمة</option>
                                <option value="1">نسبة</option>
                            </select>
                            @include('backEnd.error', ['property' => 'discount_type'])
                        </div>





                        {{-- نسبة الخصم discount_percent  --}}
                        <div class="col-sm-6 mb-4 {{$discount_type != '' && $discount_type == 1 ? '' : 'd-none' }}" wire:change="change_discount_percent($event.target.value)">
                            <label>نسبة الخصم </label>
                            <input type="number" class="form-control" wire:model="discount_percent"  step="0.01">
                            @include('backEnd.error', ['property' => 'discount_percent'])
                        </div>



                         {{-- قيمة الخصم discount_amount  --}}
                         <div class="col-sm-6 mb-4 {{ $discount_type != '' && $discount_type == 0 ? '' : 'd-none' }}" wire:change="change_discount_amount($event.target.value)">
                            <label>قيمة الخصم </label>
                            <input type="number" class="form-control" wire:model="discount_amount"  step="0.01">
                            @include('backEnd.error', ['property' => 'discount_amount'])
                        </div>



                         {{-- الاجمالي بعد الخصم و الضريبة total_cost  --}}
                         <div class="col-sm-12 mb-4">
                            <label>الاجمالي بعد الخصم و الضريبة </label>
                            <input type="number" readonly class="form-control" wire:model="total_cost"  step="0.01">
                            @include('backEnd.error', ['property' => 'total_cost'])
                        </div>


                          {{-- اسم الخزنة الذي ستحدث عليها الحركة treasury name  --}}
                          <div class="col-sm-6 mb-4">
                            <label>اسم الخزنة الذي ستحدث عليها الحركة  </label>
                            <select class="form-control">
                                @if (!empty($get_active_shift->treasury_id))
                                    <option value="{{ $get_active_shift->treasury_id }}">{{ $get_active_shift->treasury->name }}</option>
                                @endif
                            </select>
                        </div>


                          {{-- الرصيد الحالي للخزنة الذي ستحدث عليها الحركة treasury balance  --}}
                          <div class="col-sm-6 mb-4">
                            <label>الرصيد الحالي اسم الخزنة الذي ستحدث عليها الحركة  </label>
                            @if (!empty($get_active_shift->treasury_id))
                                <input type="number" readonly class="form-control" value="{{ $treasury_balance }}"  step="0.01">
                                @include('backEnd.error', ['property' => 'treasury_balance'])
                            @endif
                        </div>


                         {{-- نوع الفاتورة order type  --}}
                         <div class="col-sm-12 mb-4">
                            <label>نوع الفاتورة  </label>
                            <input type="text" readonly class="form-control" readonly value="{{ $invoice_type }}">
                        </div>



                        {{-- المبلغ المدفوع الان للمورد paid  --}}
                        @if ($invoice_type == 'كاش')
                            <div class="col-sm-12 mb-4">
                                <label>المبلغ المدفوع الان للمورد   </label>
                                <input type="number" class="form-control" wire:model="paid" readonly step="0.01">
                                @include('backEnd.error', ['property' => 'paid'])
                            </div>
                        @else
                            <div class="col-sm-12 mb-4">
                                <label>المبلغ المدفوع الان للمورد   </label>
                                <input type="number"  class="form-control" step="0.01" wire:model="paid" wire:change="change_paid($event.target.value)">
                                @include('backEnd.error', ['property' => 'paid'])
                            </div>




                            {{-- المبلغ المتبقي  للمورد unpaid  --}}
                            <div class="col-sm-12 mb-4">
                                <label>المبلغ المتبقي  للمورد</label>
                                <input type="number" class="form-control" wire:model="unpaid" step="0.01" wire:change="change_unpaid($event.target.value)">
                                @include('backEnd.error', ['property' => 'unpaid'])
                            </div>
                        @endif


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" type="button"
                        class="btn btn-info waves-effect waves-float waves-light" wire:mouseenter="updateTreasuryBalance">اعتماد</button>
                </div>
            </form>
        </div>
    </div>
</div>
