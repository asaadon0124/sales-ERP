<div class="modal fade text-start modal-primary" id="deleteModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">حذف فئة الصنف </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form form-horizontal" wire:submit.prevent='submit'>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-sm-6 mb-4">
                            <div class="form-group">

                                    @if (isset($itemCategory))
                                        <h3>هل تريد حذف فئة الصنف  <span style="color: #f00;">{{$itemCategory->name}}</span></h3>
                                    @endif
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" type="button"
                        class="btn btn-success waves-effect waves-float waves-light">حذف</button>
                </div>
            </form>
        </div>
    </div>
</div>
