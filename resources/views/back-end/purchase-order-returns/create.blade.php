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


                        {{-- order_number رقم الفاتورة المسجل بأصل فاتورة المشتريات --}}
                        <div class="col-sm-12 mb-4">
                            <div class="form-group">
                                <label>رقم الفاتورة المسجل بأصل فاتورة المشتريات</label>
                                <input type="number" class="form-control" placeholder="ادخل رقم الفاتورة المسجل بأصل فاتورة المشتريات" wire:model="order_number">
                                @include('backEnd.error', ['property' => 'order_number'])

                            </div>
                        </div>


                        {{-- اسم المورد supplier_code --}}
                        <div class="col-sm-12 mb-4">
                            <div class="form-group">
                                <label>اسم المورد</label>
                                <select wire:change="supplierChanged($event.target.value)" wire:model="supplier_code" wire:loading.attr="disabled" class="form-control select2" wire:target="supplier_code">
                                    <option selected>اسم المورد</option>
                                    @if (isset($suppliers))
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->supplier_code }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @include('backEnd.error', ['property' => 'supplier_code'])
                            </div>
                        </div>



                        {{-- invoice_type نوع الفاتورة كاش او اجل  --}}
                        {{-- <div class="col-sm-12 mb-4">
                            <div class="form-group">
                                <label>نوع الفاتورة</label>

                                <select wire:model="invoice_type" wire:loading.attr="disabled" class="form-control" wire:target="invoice_type">
                                    <option selected>نوع الفاتورة</option>
                                    <option value="0">كاش</option>
                                    <option value="1"> اجل</option>
                                </select>
                                @include('backEnd.error', ['property' => 'invoice_type'])
                            </div>
                        </div> --}}



                         {{-- بيانات المخازن store_id --}}
                        <div class="col-sm-12 mb-4">
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
                        </div>


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
