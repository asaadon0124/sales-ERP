<div class="modal fade text-start modal-primary" id="createModal" wire:ignore.self tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">استلام شيفت جديد  </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form form-horizontal" wire:submit.prevent='submit'>
                <div class="modal-body">
                    <div class="row">

                        {{-- <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>اسم الخزنة</label>
                                <input type="text" class="form-control" placeholder="ادخل اسم الخزنة" wire:model="name">
                                @include('backEnd.error', ['property' => 'name'])

                            </div>
                        </div> --}}



                        {{-- اسم الخزنة treasury_id  --}}
                        <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>اسم الخزنة</label>

                                <select wire:model="treasury_id" wire:loading.attr="disabled" class="form-control" wire:target="treasury_id">
                                    <option value="">اسم الخزنة المراد استلامها</option>
                                    @if (isset($admin))
                                        @foreach ($admin as $treasury)
                                            <option value="{{ $treasury->id }}">{{ $treasury->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @include('backEnd.error', ['property' => 'treasury_id'])
                            </div>
                        </div>



                        {{-- <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>تليفون المخزن</label>
                                <input type="text" class="form-control" placeholder="(اختياري) ادخل تليفون المخزن" wire:model="phone">
                                @include('backEnd.error', ['property' => 'phone'])

                            </div>
                        </div> --}}


                        {{-- <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>عنوان المخزن</label>
                                <input type="text" class="form-control" placeholder="(اختياري) ادخل عنوان المخزن" wire:model="address">
                                @include('backEnd.error', ['property' => 'address'])
                            </div>
                        </div> --}}


                        {{-- <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>التاريخ </label>
                                <input type="date" class="form-control" wire:model="date">
                                @include('backEnd.error', ['property' => 'date'])
                            </div>
                        </div> --}}
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
