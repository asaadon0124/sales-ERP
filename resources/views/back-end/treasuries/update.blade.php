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
                        {{-- NAME اسم الخزنة --}}
                        <div class="col-sm-6 mb-4">
                            <label for=""> اسم الخزنة</label>
                            <input type="text" class="form-control" wire:model="name">
                            @include('backEnd.error', ['property' => 'name'])
                        </div>

                         {{-- STATUS حالة الخزنة --}}
                        {{-- <div class="col-sm-6 mb-4">
                            <label for=""> حالة  الخزنة</label>
                            <select wire:model="status" wire:loading.attr="disabled" class="form-control" wire:target="status">
                                <option selected>{{ __('dashboard.select-status') }}</option>
                                <option value="active">Active</option>
                                <option value="un_active">Inactive</option>
                            </select>
                            @include('backEnd.error', ['property' => 'status'])
                        </div> --}}

                         {{-- IS MASTER هل الخزنة رئيسية ام فرعية--}}
                        <div class="col-sm-6 mb-4">
                            <label for="">نوع الخزنة</label>
                            <select wire:model="is_master" wire:loading.attr="disabled" class="form-control" wire:target="is_master">
                                <option selected>{{ __('dashboard.select-status') }}</option>
                                <option value="master">master</option>
                                <option value="user">user</option>
                            </select>
                            @include('backEnd.error', ['property' => 'is_master'])
                        </div>

                        {{-- اخر ايصال صرف LAST RECEPT PAY  --}}
                        <div class="col-sm-6 mb-4">
                            <label for="">اخر ايصال صرف</label>
                            <input type="number" class="form-control" wire:model.live="last_recept_pay">
                            @include('backEnd.error', ['property' => 'last_recept_pay'])
                        </div>

                        {{-- اخر ايصال تحصيل LAST RECEPT RECEPT  --}}
                        <div class="col-sm-6 mb-4">
                            <label for="">اخر ايصال تحصيل</label>
                            <input type="number" class="form-control" wire:model.live="last_recept_recive">
                            @include('backEnd.error', ['property' => 'last_recept_recive'])
                        </div>

                        {{-- total  --}}
                        <div class="col-sm-6 mb-4">
                            <label for=""> الاجمالي</label>
                            <input type="number" class="form-control" wire:model.live="total" readonly>
                            @include('backEnd.error', ['property' => 'total'])
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
