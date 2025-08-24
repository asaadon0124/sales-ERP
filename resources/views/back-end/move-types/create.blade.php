<div class="modal fade text-start modal-primary" id="createModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">اضافة حركة نقدية جديد  </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form form-horizontal" wire:submit.prevent='submit'>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>اسم جركة النقدية</label>
                                <input type="text" class="form-control" placeholder="ادخل اسم حركة النقدية" wire:model="name">
                                @include('backEnd.error', ['property' => 'name'])

                            </div>
                        </div>

                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>حالة حركة النقدية</label>

                                <select wire:model="status" wire:loading.attr="disabled" class="form-control" wire:target="status">
                                    <option selected>حالة حركة النقدية</option>
                                    <option value="active">مفعل</option>
                                    <option value="un_active">غير مفعل</option>
                                </select>
                                @include('backEnd.error', ['property' => 'status'])
                            </div>
                        </div>


                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label> حركة النقدية</label>

                                <select wire:model="in_screen" wire:loading.attr="disabled" class="form-control" wire:target="in_screen">
                                    <option selected> حركة النقدية</option>
                                    <option value="pay">صرف</option>
                                    <option value="collect">تحصيل</option>
                                </select>
                                @include('backEnd.error', ['property' => 'in_screen'])
                            </div>
                        </div>



                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>  نوع حركة النقدية</label>

                                <select wire:model="is_private_internal" wire:loading.attr="disabled" class="form-control" wire:target="is_private_internal">
                                    <option selected> نوع حركة النقدية</option>
                                    <option value="global">عامة</option>
                                    <option value="private">داخلية</option>
                                </select>
                                @include('backEnd.error', ['property' => 'is_private_internal'])
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
