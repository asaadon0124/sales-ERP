<div class="modal fade text-start modal-primary" id="createModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">اضافة نوع حساب جديد  </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form form-horizontal" wire:submit.prevent='submit'>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>نوع الحساب</label>
                                <input type="text" class="form-control" placeholder="ادخل نوع الحساب" wire:model="name">
                                @include('backEnd.error', ['property' => 'name'])

                            </div>
                        </div>

                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>حالة الحساب</label>

                                <select wire:model="status" wire:loading.attr="disabled" class="form-control" wire:target="status">
                                    <option selected>حالة الحساب</option>
                                    <option value="active">مفعل</option>
                                    <option value="un_active">غير مفعل</option>
                                </select>
                                @include('backEnd.error', ['property' => 'status'])
                            </div>
                        </div>

                        

                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>التاريخ </label>
                                <input type="date" class="form-control" wire:model="date">
                                @include('backEnd.error', ['property' => 'date'])
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
