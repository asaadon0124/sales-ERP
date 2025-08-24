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
                            <label>اسم المورد</label>
                            <input type="text" class="form-control" wire:model="name">
                            @include('backEnd.error', ['property' => 'name'])
                        </div>


                           <!--  عنوان المورد address  -->
                           <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>عنوان المورد</label>
                                <input type="text" class="form-control" placeholder="ادخل عنوان المورد" wire:model="address">
                                @include('backEnd.error', ['property' => 'address'])

                            </div>
                        </div>

                         <!-- supplier_category_id  اسم القسم الخاص بالمورد -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label> اسم القسم الخاص بالمورد </label>

                                <select wire:model="supplier_Category_id" wire:loading.attr="disabled"
                                    class="form-control" wire:target="supplier_Category_id">
                                    <option selected> اسم القسم الخاص بالمورد </option>
                                    @if (isset($supplierCategory))
                                        @foreach ($supplierCategory as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif



                                </select>
                                @include('backEnd.error', ['property' => 'supplier_Category_id'])
                            </div>
                        </div>




                         <!-- الملاحظات notes  -->
                         <div class="col-sm-6 mb-4">
                            <div class="form-group">
                                <label>الملاحظات</label>
                                <textarea class="form-control" wire:model="notes" placeholder="الملاحظات"></textarea>
                                @include('backEnd.error', ['property' => 'notes'])

                            </div>
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
