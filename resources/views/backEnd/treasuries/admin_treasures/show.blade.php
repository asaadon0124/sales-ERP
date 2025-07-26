@extends('layouts.backEnd.app')

@section('title', 'عرض تفاصيل الخزنة')
@section('page_name', 'عرض تفاصيل الخزنة')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'الخزن')
@section('current_page_link', route('treasuries.index'))


@section('css')
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid-theme.min.css') }}">
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" /> --}}
@endsection
@section('content')

    <!-- Main content -->
    <section class="content">
        <livewire:back-end.treasuries.show :id="$item->id" />
    </section>
    <!-- /.content -->
    @livewire('back-end.treasuries.create-sub-treasuries')
    @livewire('back-end.treasuries.delete-sub-treasuries')
@endsection


@section('js')
    <!-- jsGrid -->
    <script src="{{ asset('assets/backEnd/plugins/jsgrid/demos/db.js') }}"></script>
    <script src="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <script>

        $(function() {
            $("#jsGrid1").jsGrid({
                height: "100%",
                width: "100%",

                sorting: true,
                paging: true,

                data: db.clients,

                fields: [{
                        name: "Name",
                        type: "text",
                        width: 150
                    },
                    {
                        name: "Age",
                        type: "number",
                        width: 50
                    },
                    {
                        name: "Address",
                        type: "text",
                        width: 200
                    },
                    {
                        name: "Country",
                        type: "select",
                        items: db.countries,
                        valueField: "Id",
                        textField: "Name"
                    },
                    {
                        name: "Married",
                        type: "checkbox",
                        title: "Is Married"
                    }
                ]
            });
        });


        document.addEventListener('DOMContentLoaded', function()
        {

            Livewire.on('SubTreasuresCreateMS', function()
            {

                toastr.success('Created Successfully', 'Create', { timeOut: 5000 });
            });


            Livewire.on('SubTreasuresDeleteMS', function()
            {

                toastr.warning('تم مسح الخزنة بنجاح', 'رسالة حذف', { timeOut: 5000 });
            });

            Livewire.on('SubTreasuresErrorMS', function()
            {
                toastr.error('هذه الخزنة مسجلة بالفعل داخل الخزنة الرئيسية', 'رسالة خطا', { timeOut: 5000 });
            });

        });
    </script>
@endsection
