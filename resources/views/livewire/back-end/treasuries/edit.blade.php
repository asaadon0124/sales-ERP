<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">تعديل الخزنة</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" wire:submit="save">
                    <div class="card-body">
                        <h1>{{ $treasuries->name }}</h1>
                        {{-- اسم الخزنة  NAME --}}
                        <div class="form-group">
                            <label>اسم الخزنة</label>
                            <input type="text" class="form-control" placeholder="ادخل اسم الخزنة" wire:model="name" value="{{ $treasuries->name }}">
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        {{-- حالة التفعيل STATUS --}}
                        <div class="form-group">
                            <label>حالة التفعيل</label>
                            <select wire:model="status" class="form-control">
                                <option value=""> اختار حالة الخزنة</option>
                                <option value="active">مفعل</option>
                                <option value="un_active">غير مفعل</option>
                            </select>
                            @error('status')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        {{-- نوع الخزنة IS MASTER --}}
                        <div class="form-group">
                            <label>هل رئيسية</label>
                            <select wire:model="is_master" class="form-control">
                                <option value=""> اختار نوع الخزنة</option>
                                <option value="master">خزنة رئيسية</option>
                                <option value="user"> خزنة فرعية</option>
                            </select>
                            @error('is_master')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- اخر ايصال صرف  last_recept_pay  --}}
                        <div class="form-group">
                            <label>اخر ايصال صرف </label>
                            <input type="number" class="form-control" placeholder="ادخل اخر ايصال صرف " wire:model="last_recept_pay">
                            @error('last_recept_pay')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        {{-- اخر ايصال تحصيل  last_recept_recive  --}}
                        <div class="form-group">
                            <label>اخر ايصال تحصيل </label>
                            <input type="number" class="form-control" placeholder="ادخل اخر ايصال تحصيل " wire:model="last_recept_recive">
                            @error('last_recept_recive')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">أضافة</button>
                        <button class="btn btn-danger" wire:navigate href="{{ route('treasuries.index') }}">رجوع</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
