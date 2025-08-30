@extends('layouts.backEnd.app')

@section('title', 'المخازن')
@section('page_name', 'المخازن')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'المخازن ')
@section('current_page_link', route('stores.index'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid-theme.min.css') }}">
@endsection
@section('content')

    <!-- Main content -->
    <section class="content">
        @livewire('back-end.stores.data')
        @livewire('back-end.stores.create')
        @livewire('back-end.stores.update')
        @livewire('back-end.stores.delete')


       

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
        Livewire.on('storesUpdateMS', function()
        {
            toastr.success('Update Successfully', 'Updated', { timeOut: 5000 });
        });


        Livewire.on('storesCreateMS', function()
        {
            toastr.success('تم اضافة القسم بنجاح', 'رسالة اضافة', { timeOut: 5000 });
        });


        Livewire.on('storesDeleteMS', function()
        {
            toastr.error('تم حذف القسم بنجاح', 'رسالة حذف', { timeOut: 5000 });
        });

        Livewire.on('storesValidationMS', function()
        {
            toastr.error('هذا المخزن غير موجود', 'رسالة تحزير', { timeOut: 5000 });
        });

        Livewire.on('storesValidation2MS', function()
        {
            toastr.error('هذا المخزن يحتوي علي كميات من الاصناف', 'رسالة تحزير', { timeOut: 5000 });
        });


        Livewire.on('storesErrorMS', function()
        {
            toastr.error('Error This Item Allready Find', 'Error Message', { timeOut: 5000 });
        });

        // Livewire.on('storesShow', function(data)
        // {
        //     // Redirect to the show page
        //     window.location.href = '/admin/treasuries/show/' + data.id; // Adjust the URL as needed
        // });

    });
</script>
@endsection
