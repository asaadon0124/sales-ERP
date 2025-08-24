@extends('layouts.backEnd.app')

@section('title', 'الاعدادات العامة')
@section('page_name', 'الاعدادات')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'الاعدادات')
@section('current_page_link', route('adminSittings.index'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid-theme.min.css') }}">
@endsection
@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">

                    {{--START  DATATABLES SECTIONS  --}}
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div id="example1_filter" class="dataTables_filter">
                                    @if ($sitting)
                                        @can('تعديل الاعدادات')
                                            <a href="{{ route('adminSittings.edit',$sitting->id) }}" class="btn btn-info mb-5">تعديل</a>
                                        @endcan
                                    @endif

                                </div>
                            </div>

                        </div>
                    {{--END  DATATABLES SECTIONS  --}}

                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example1" class="table table-bordered table-striped dataTable" role="grid"
                                aria-describedby="example1_info">
                                    @if (isset($sitting) && $sitting->count() > 0)

                                        {{-- COMPANY NAME  --}}
                                        <tr role="row">
                                            <td style="width: 30%;background-color:#d6cacae6;">اسم الشركة</td>
                                            <td>{{ $sitting->system_name }}</td>
                                        </tr>

                                         {{-- COMPANY CODE  --}}
                                         <tr role="row">
                                            <td style="width: 30%;background-color:#d6cacae6;">كود الشركة</td>
                                            <td>{{ $sitting->company_code }}</td>
                                        </tr>

                                         {{-- COMPANY STATUS  --}}
                                         <tr role="row">
                                            <td style="width: 30%;background-color:#d6cacae6;">حالة الشركة</td>
                                            <td>{{ $sitting->status }}</td>
                                        </tr>

                                         {{-- COMPANY ADDRESS  --}}
                                         <tr role="row">
                                            <td style="width: 30%;background-color:#d6cacae6;">عنوان الشركة</td>
                                            <td>{{ $sitting->address }}</td>
                                        </tr>

                                         {{-- COMPANY PHONE  --}}
                                         <tr role="row">
                                            <td style="width: 30%;background-color:#d6cacae6;">تليفون الشركة</td>
                                            <td>{{ $sitting->phone }}</td>
                                        </tr>


                                         {{-- COMPANY GENERAL ALERT  --}}
                                         <tr role="row">
                                            <td style="width: 30%;background-color:#d6cacae6;">رسالة تنبيه علي الشاشة للشركة</td>
                                            <td>{{ $sitting->general_alert }}</td>
                                        </tr>


                                         {{-- COMPANY LOGO  --}}
                                         <tr role="row">
                                            <td style="width: 30%;background-color:#d6cacae6;">شعار الشركة</td>
                                            <td>
                                                <img class="img-responsive mb-1" src="{{ asset('/assets/backEnd/images/' . $sitting->photo)}}" style="height: 300px; width: 300px">
                                            </td>
                                        </tr>


                                         {{-- LAST UPDATE DATE  --}}
                                         <tr role="row">
                                            <td style="width: 30%;background-color:#d6cacae6;"> تاريخ اخر تحديث</td>
                                            <td>
                                               {{ last_update($sitting) }}
                                            </td>
                                        </tr>
                                    @else
                                        <div class="alert alert-danger text-center mt-4">
                                            لا يوجد بيانات
                                        </div>
                                    @endif

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

@endsection


@section('js')
    <!-- jsGrid -->
    <script src="{{ asset('assets/backEnd/plugins/jsgrid/demos/db.js') }}"></script>
    <script src="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.js') }}"></script>

    <script>
        $(function () {
          $("#jsGrid1").jsGrid({
              height: "100%",
              width: "100%",

              sorting: true,
              paging: true,

              data: db.clients,

              fields: [
                  { name: "Name", type: "text", width: 150 },
                  { name: "Age", type: "number", width: 50 },
                  { name: "Address", type: "text", width: 200 },
                  { name: "Country", type: "select", items: db.countries, valueField: "Id", textField: "Name" },
                  { name: "Married", type: "checkbox", title: "Is Married" }
              ]
          });
        });
      </script>
@endsection
