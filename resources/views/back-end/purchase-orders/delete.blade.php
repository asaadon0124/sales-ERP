<div class="modal fade text-start modal-primary" id="deleteModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                {{-- {{ $item }} --}}
                <h5 class="modal-title" id="myModalLabel110">حذف الفاتورة رقم{{ $auto_serial }}  </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form form-horizontal" wire:submit.prevent='submit'>
                <div class="modal-body">
                    <div class="row">

                        <h3 class="ml-4">
                            هل تريد حذف الفاتورة رقم<span style="color: rgb(231, 200, 26)">{{ $auto_serial }} </span>؟ <br>
                            مع العلم انه سيتم حذف الفاتورة و {{ $items}} اصناف التي بداخلها
                        </h3>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" type="button"
                        class="btn btn-danger waves-effect waves-float waves-light">حذف</button>
                </div>
            </form>
        </div>
    </div>
</div>
