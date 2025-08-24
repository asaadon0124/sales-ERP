<div class="modal fade text-start modal-primary" id="createModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">اضافة خزنة جديدة  </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form form-horizontal" wire:submit.prevent='submit'>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>اسم الخزنة</label>
                                <input type="text" class="form-control" placeholder="ادخل اسم الخزنة" wire:model="name">
                                @include('backEnd.error', ['property' => 'name'])

                            </div>
                        </div>

                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>حالة الخزنة</label>

                                <select wire:model="status" wire:loading.attr="disabled" class="form-control" wire:target="status">
                                    <option selected>حالة الخزنة</option>
                                    <option value="active">Active</option>
                                    <option value="un_active">Inactive</option>
                                </select>
                                @include('backEnd.error', ['property' => 'status'])
                            </div>
                        </div>

                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>هل رئيسية الخزنة</label>
                                <select wire:model="is_master" wire:loading.attr="disabled" class="form-control" wire:target="is_master">
                                    <option selected>هل رئيسية</option>
                                    <option value="master">master</option>
                                    <option value="user">user</option>
                                </select>
                                @include('backEnd.error', ['property' => 'is_master'])
                            </div>
                        </div>

                        <div class="col-sm-6 mb-4">
                           <div class="form-group">
                                <label>رقم اخر ايصال صرف </label>
                                <input type="number" class="form-control" wire:model="last_recept_pay">
                                @include('backEnd.error', ['property' => 'last_recept_pay'])
                           </div>
                        </div>


                        <div class="col-sm-6 mb-4">
                           <div class="form-group">
                            <label>رقم اخر ايصال استلام </label>
                                <input type="number" class="form-control" wire:model="last_recept_recive">
                                @include('backEnd.error', ['property' => 'last_recept_recive'])
                           </div>
                        </div>




                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" type="button"
                        class="btn btn-success waves-effect waves-float waves-light">اضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>
