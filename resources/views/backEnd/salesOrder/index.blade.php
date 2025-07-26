@extends('layouts.backEnd.app')

@section('title', 'فواتير المبيعات')
@section('page_name', 'فواتير المبيعات')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'فواتير المبيعات ')
@section('current_page_link', route('salesOrder.index'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid-theme.min.css') }}">
@endsection
@section('content')

    <!-- Main content -->
    <section class="content">
        @livewire('back-end.sales-order.data')
        @livewire('back-end.sales-order.create')
        @livewire('back-end.sales-order.delete')
        @livewire('back-end.sales-order.update')

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
        Livewire.on('salesOrderUpdateMS', function()
        {
            toastr.success('Update Successfully', 'Updated', { timeOut: 5000 });
        });


        Livewire.on('salesOrderCreateMS', function()
        {
            toastr.success('تم اضافة الفاتورة بنجاح', 'رسالة اضافة', { timeOut: 5000 });
        });

        Livewire.on('salesOrderDeleteMS', function()
        {
            toastr.error('تم حذف الفاتورة بنجاح', 'رسالة حذف', { timeOut: 5000 });
        });


        Livewire.on('salesOrderUpdateMS', function()
        {
            toastr.success.('تم تعديل الفاتورة بنجاح', 'رسالة تعديل', { timeOut: 5000 });
        });


        Livewire.on('salesOrderErrorMS', function()
        {
            toastr.error('Error This Item Allready Find', 'Error Message', { timeOut: 5000 });
        });


        Livewire.on('salesOrderErrorVarMS', function(data)
        {
            toastr[data.type || 'error'](data.message, 'تنبيه', { timeOut: 5000 });
        });

        // Livewire.on('salesOrderShow', function(data)
        // {
        //     // Redirect to the show page
        //     window.location.href = '/admin/treasuries/show/' + data.id; // Adjust the URL as needed
        // });

    });
</script>
@endsection
