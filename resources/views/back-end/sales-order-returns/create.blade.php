<div class="modal fade text-start modal-primary" id="createModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">اضافة فاتورة مرتجع مشتريات من مورد </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form form-horizontal" wire:submit.prevent='submit'>
                <div class="modal-body">
                    <div class="row">

                        {{-- order_date تاريخ الفاتورة  --}}
                        <div class="col-sm-12 mb-4">
                            <div class="form-group">
                                <label>تاريخ الفاتورة</label>
                                <input type="date" class="form-control" placeholder="ادخل تاريخ الفاتورة" wire:model="order_date">
                                @include('backEnd.error', ['property' => 'order_date'])

                            </div>
                        </div>


                         {{-- invoice_type نوع الفاتورة كاش او اجل  --}}
                        <div class="col-sm-12 mb-4">
                            <div class="form-group">
                                <label>نوع الفاتورة</label>

                                <select wire:model="invoice_type" wire:loading.attr="disabled" class="form-control" wire:target="invoice_type">
                                    <option selected>نوع الفاتورة</option>
                                    <option value="0">كاش</option>
                                    <option value="1"> اجل</option>
                                </select>
                                @include('backEnd.error', ['property' => 'invoice_type'])
                            </div>
                        </div>


                        {{-- اسم العميل customer_code --}}
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>اسم العميل</label>
                                <select wire:change="customerChanged($event.target.value)" wire:model="customer_code" wire:loading.attr="disabled" class="form-control select2" wire:target="customer_code">
                                    <option selected>اسم العميل</option>
                                    @if (isset($customers))
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->customer_code }}">{{ $customer->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @include('backEnd.error', ['property' => 'customer_code'])
                            </div>
                        </div>



                         {{-- customer_balance رصيد العميل --}}
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>رصيد العميل</label>
                                <input type="number" class="form-control" wire:model="customer_balance" readonly>
                                @include('backEnd.error', ['property' => 'customer_balance'])

                            </div>
                        </div>


                        {{-- اسم المندوب customer_code --}}
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>اسم المندوب</label>
                                <select wire:change="servantChanged($event.target.value)" wire:model="servant_code" wire:loading.attr="disabled" class="form-control select2" wire:target="servant_code">
                                    <option value="">اسم المندوب</option>
                                    @if (isset($servants))
                                        @foreach ($servants as $servant)
                                            <option value="{{ $servant->servant_code }}">{{ $servant->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @include('backEnd.error', ['property' => 'servant_code'])
                            </div>
                        </div>



                         {{-- customer_balance رصيد المندوب --}}
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>رصيد المندوب</label>
                                <input type="number" class="form-control" wire:model="servant_balance" readonly>
                                @include('backEnd.error', ['property' => 'servant_balance'])

                            </div>
                        </div>



                        {{-- matrial_types_id فئة الفاتورة   --}}
                        <div class="col-sm-12 mb-4">
                            <div class="form-group">
                                <label>فئة الفاتورة</label>

                                <select wire:model="matrial_types_id" wire:loading.attr="disabled" class="form-control" wire:target="matrial_types_id">
                                    <option value="">فئة الفاتورة</option>
                                    @if (!empty($matrial_types))
                                        @foreach ($matrial_types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @include('backEnd.error', ['property' => 'matrial_types_id'])
                            </div>
                        </div>



                         {{-- بيانات المخازن store_id --}}
                        {{-- <div class="col-sm-12 mb-4">
                            <div class="form-group">
                                <label>اسم المخزن المستلم للفاتورة</label>

                                <select class="form-control select2" wire:model="store_id">
                                    <option selected>اسم المخزن المستلم للفاتورة</option>
                                    @if (isset($stores))
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @include('backEnd.error', ['property' => 'store_id'])
                            </div>
                        </div> --}}


                        {{-- الملاحظات notes --}}
                        <div class="col-sm-12 mb-4">
                            <div class="form-group">
                                <label>الملاحظات </label>
                                <textarea name="notes" wire:model="notes" class="form-control" id="" cols="30" rows="5"></textarea>
                                @include('backEnd.error', ['property' => 'notes'])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" type="button"
                        class="btn btn-success waves-effect waves-float waves-light">اضاقة</button>
                </div>
            </form>
        </div>
    </div>
</div>
