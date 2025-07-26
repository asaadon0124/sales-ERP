@extends('layouts.backEnd.app')

@section('title', 'انواع الحسابات')
@section('page_name', 'انواع الحسابات')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'انواع الحسابات ')
@section('current_page_link', route('stores.index'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid-theme.min.css') }}">
@endsection
@section('content')

    <!-- Main content -->
    <section class="content">
        @livewire('back-end.accounts-type.data')
        @livewire('back-end.accounts-type.create')
        @livewire('back-end.accounts-type.update')
        @livewire('back-end.accounts-type.Delete')

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
        Livewire.on('accountsTypesUpdateMS', function()
        {
            toastr.success('Update Successfully', 'Updated', { timeOut: 5000 });
        });


        Livewire.on('accountsTypesCreateMS', function()
        {
            toastr.success('تم اضافة نوع الحساب بنجاح', 'رسالة اضافة', { timeOut: 5000 });
        });


        Livewire.on('accountsTypesDeleteMS', function()
        {
            toastr.error('تم حذف نوع الحساب بنجاح', 'رسالة حذف', { timeOut: 5000 });
        });


        Livewire.on('accountsTypesValidationMS', function()
        {
            toastr.error('نوع الحساب يحتوي علي حسبات قد تحتوي علي ارصدة  ', 'رسالة تحزير', { timeOut: 10000 });
        });


        Livewire.on('accountsTypesErrorMS', function()
        {
            toastr.error('Error This Item Allready Find', 'Error Message', { timeOut: 5000 });
        });

        // Livewire.on('accountsTypesShow', function(data)
        // {
        //     // Redirect to the show page
        //     window.location.href = '/admin/treasuries/show/' + data.id; // Adjust the URL as needed
        // });

    });
</script>
@endsection
