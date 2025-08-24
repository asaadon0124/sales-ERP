<div class="modal fade text-start modal-primary" id="createModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">اضافة دور جديد  </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form form-horizontal" wire:submit.prevent='submit'>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-sm-12 mb-4">
                            <div class="form-group">
                                <label>اسم الدور</label>
                                <input type="text" class="form-control" placeholder="ادخل اسم الدور" wire:model="name">
                                @include('backEnd.error', ['property' => 'name'])

                            </div>



                             <div class="col-sm-12 mb-4">
                            <div class="form-group">
                                @if (!empty($permtions))
                                    @foreach ($permtions as $permtion)
                                         <label class="ml-2">
                                            <input type="checkbox" value="{{ $permtion->name }}" wire:model="permtion_names">
                                            {{ $permtion->name }}
                                        </label>
                                    @endforeach
                                @endif

                                @include('backEnd.error', ['property' => 'permtion_names'])

                            </div>
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
