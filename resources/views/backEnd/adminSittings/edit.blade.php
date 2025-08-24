@extends('layouts.backEnd.app')

@section('title', 'تعديل الاعدادات')
@section('page_name', 'تعديل الاعدادات')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'تعديل الاعدادات')
@section('current_page_link', route('adminSittings.index'))

@section('content')
<div class="col-md-12">
    <!-- general form elements -->
    <div class="card card-primary">
      <div class="card-header" >
        <h3 class="card-title">تعديل الاعدادت</h3>
      </div>

      <form  method="post" action="{{ route('adminSittings.update',$sitting->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-body">

          {{-- system_name  --}}
          <div class="form-group">
            <label for="exampleInputEmail1">اسم الشركة</label>
            <input type="text" name="system_name" class="form-control" id="exampleInputEmail1" placeholder="ادخل اسم الشركة" value="{{ $sitting->system_name }}">
            @error("system_name")
                <span class="text-danger">{{$message}}</span>
            @enderror
          </div>

        {{-- phone  --}}
        <div class="form-group">
            <label for="exampleInputEmail1">تليفون الشركة</label>
            <input type="text" name="phone" class="form-control" id="exampleInputEmail1" placeholder="ادخل تليفون الشركة" value="{{ $sitting->phone }}">
            @error("phone")
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

          {{-- adress  --}}
          <div class="form-group">
            <label for="exampleInputEmail1">عنوان الشركة</label>
            <input type="text" name="address" class="form-control" id="exampleInputEmail1" placeholder="ادخل عنوان الشركة" value="{{ $sitting->address }}">
            @error("address")
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>


         <!-- parent_account_number  حساب العملاء الاب -->
         <div class="col-sm-6 mb-4" >
          <div class="form-group">
              <label> حساب العملاء الاب</label>
              <select name="customer_parent_account_number" class="form-control">
                  <option selected> اختار العملاء الاب</option>
                  @if (isset($get_parent_accounts))
                      @foreach ($get_parent_accounts as $parent_account)
                          <option value="{{ $parent_account->account_number }}"  @if($parent_account->account_number == $sitting->customer_parent_account_number ) selected @endif>{{ $parent_account->name }}</option>
                      @endforeach
                  @endif

              </select>
              @include('backEnd.error', ['property' => 'customer_parent_account_number'])
          </div>
         </div>



        <!-- parent_account_number  حساب الموردين الاب -->
        <div class="col-sm-6 mb-4" >
          <div class="form-group">
              <label> حساب الموردين الاب</label>
              <select name="supplier_parent_account_number" class="form-control">
                  <option selected> اختار حساب الموردين الاب</option>
                  @if (isset($get_parent_Suppliers))
                      @foreach ($get_parent_Suppliers as $parent_account)
                          <option value="{{ $parent_account->account_number }}"  @if($parent_account->account_number == $sitting->supplier_parent_account_number ) selected @endif>{{ $parent_account->name }}</option>
                      @endforeach
                  @endif

              </select>
              @include('backEnd.error', ['property' => 'supplier_parent_account_number'])
          </div>
        </div>



         <!-- parent_account_number  حساب المناديب الاب -->
        <div class="col-sm-6 mb-4" >
          <div class="form-group">
              <label> حساب المناديب الاب</label>
              <select name="servant_parent_account_number" class="form-control">
                  <option selected> اختار حساب المناديب الاب</option>
                  @if (isset($get_parent_Servants))
                      @foreach ($get_parent_Servants as $parent_account)
                          <option value="{{ $parent_account->account_number }}"  @if($parent_account->account_number == $sitting->servant_parent_account_number ) selected @endif>{{ $parent_account->name }}</option>
                      @endforeach
                  @endif

              </select>
              @include('backEnd.error', ['property' => 'supplier_parent_account_number'])
          </div>
        </div>



         <!-- parent_account_number  حساب الموظفين الاب -->
         <div class="col-sm-6 mb-4" >
          <div class="form-group">
              <label> حساب الموظفين الاب</label>
              <select name="employee_parent_account_number" class="form-control">
                  <option selected> اختار الموظفين الاب</option>
                  @if (isset($get_parent_employees))
                      @foreach ($get_parent_employees as $parent_account)
                          <option value="{{ $parent_account->account_number }}"  @if($parent_account->account_number == $sitting->employee_parent_account_number ) selected @endif>{{ $parent_account->name }}</option>
                      @endforeach
                  @endif

              </select>
              @include('backEnd.error', ['property' => 'customer_parent_account_number'])
          </div>
         </div>


          <div class="form-group">
            <label for="exampleInputFile">شعار الشركة</label>
            <div class="input-group">
              <div class="custom-file">
                <input type="file" class="" id="exampleInputFile" name="photo">
                <label class="custom-file-label" for="exampleInputFile">اختار صورة</label>
              </div>
              <div class="input-group-append">
                <span class="input-group-text" id="">اضافة</span>
              </div>
            </div>
          </div>
          <div class="form-check">
            <img class="img-responsive mb-1" src="{{ asset('/assets/backEnd/images/' . $sitting->photo)}}" style="height: 300px; width: 300px">
          </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
    <!-- /.card -->








  </div>
@endsection
