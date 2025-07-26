@extends('layouts.backEnd.app')

@section('title', 'الخزن')
@section('page_name', 'الخزن')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'الخزن')
@section('current_page_link', route('treasuries.index'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid-theme.min.css') }}">
@endsection
@section('content')

    <!-- Main content -->
    <section class="content">
        @livewire('back-end.treasuries.data')
        @livewire('back-end.treasuries.update')
        @livewire('back-end.treasuries.create')
        @livewire('back-end.treasuries.delete')
    </section>
    <!-- /.content -->

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
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function()
    {
        Livewire.on('treasuriesUpdateMS', function()
        {
            toastr.success('تم تحديث الخزنة بنجاح', 'رسالة تحديث', { timeOut: 5000 });
        });


        Livewire.on('treasuriesCreateMS', function()
        {
            toastr.success('تم اضافة خزنة جديدة بنجاح', 'رسالة اضافة', { timeOut: 5000 });
        });


         Livewire.on('treasuriesDeleteMS', function()
        {
            toastr.error('تم حذف الخزنة بنجاح', 'رسالة حذف', { timeOut: 5000 });
        });


        Livewire.on('treasuriesValidationMS', function()
        {
            toastr.error('تاكد من عدم وجود خزن فرعية تابعة تلك الخزنة و انها ليس لديها شيفتات او رصيد غير مرحل', 'رسالة خطا', { timeOut: 10000 });
        });


         Livewire.on('treasuriesErrorMS', function()
        {
            toastr.error('هناك خطا حاول مرة اخري', 'رسالة خطا', { timeOut: 5000 });
        });

        // Livewire.on('treasuriesShow', function(data)
        // {
        //     // Redirect to the show page
        //     window.location.href = '/admin/treasuries/show/' + data.id; // Adjust the URL as needed
        // });

    });
</script>
@endsection
