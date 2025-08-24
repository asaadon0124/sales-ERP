<div class="modal fade text-start modal-primary" id="updateModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">Update {{ $name }}  </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form form-horizontal" wire:submit.prevent='submit'>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-sm-6 mb-4">
                            <label>اسم المخزن</label>
                            <input type="text" class="form-control" wire:model="name">
                            @include('backEnd.error', ['property' => 'name'])
                        </div>


                        {{-- <div class="col-sm-6 mb-4">
                            <label>حالة المخزن</label>
                            <select wire:model="status" wire:loading.attr="disabled" class="form-control" wire:target="status">
                                <option selected>اختار حالة المخزن</option>
                                <option value="active">مفعل</option>
                                <option value="un_active">غير مفعل</option>
                            </select>
                            @include('backEnd.error', ['property' => 'status'])
                        </div> --}}



                        <div class="col-sm-6 mb-4">
                            <label>(اختياري) تليفون المخزن</label>
                            <input type="text" class="form-control" wire:model.live="phone">
                            @include('backEnd.error', ['property' => 'phone'])
                        </div>


                        <div class="col-sm-6 mb-4">
                            <label>(اختياري) عنوان المخزن</label>
                            <input type="text" class="form-control" wire:model.live="address">
                            @include('backEnd.error', ['property' => 'address'])
                        </div>


                        <div class="col-sm-6 mb-4">
                            <label>التاريخ</label>
                            <input type="date" class="form-control" wire:model.live="date">
                            @include('backEnd.error', ['property' => 'date'])
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" type="button"
                        class="btn btn-info waves-effect waves-float waves-light">تعديل</button>
                </div>
            </form>
        </div>
    </div>
</div>
