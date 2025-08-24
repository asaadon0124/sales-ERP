@extends('layouts.backEnd.app')

@section('title', 'الموردين')
@section('page_name', 'الموردين')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'الموردين ')
@section('current_page_link', route('suppliers.index'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid-theme.min.css') }}">
@endsection
@section('content')

    <!-- Main content -->
    <section class="content">
        @livewire('back-end.suppliers.data')
        @livewire('back-end.suppliers.create')
        @livewire('back-end.suppliers.update')
        @livewire('back-end.suppliers.delete')
        @livewire('back-end.suppliers.Show')

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
        Livewire.on('supplierUpdateMS', function()
        {
            toastr.success('تم تحديث حساب المورد بنجاح', 'رسالة تعديل', { timeOut: 5000 });
        });



        Livewire.on('supplierCreateMS', function()
        {
            toastr.success('تم اضافة نوع حساب المورد بنجاح', 'رسالة اضافة', { timeOut: 5000 });
        });


        Livewire.on('supplierErrorMS', function()
        {
            toastr.error('Error This Item Allready Find', 'Error Message', { timeOut: 5000 });
        });

        // Livewire.on('supplierShow', function(data)
        // {
        //     // Redirect to the show page
        //     window.location.href = '/admin/treasuries/show/' + data.id; // Adjust the URL as needed
        // });

    });
</script>



@endsection
