@extends('layouts.backEnd.app')

@section('title', 'تفاصيل فاتورة مرتجع المبيعات')
@section('page_name', 'تفاصيل فاتورة مرتجع المبيعات')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'تفاصيل فاتورة مرتجع المبيعات ')
@section('current_page_link', route('salesOrder.index_returns'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid-theme.min.css') }}">


@endsection
@section('content')

    <!-- Main content -->
    <section class="content">
        @livewire('back-end.sales-order-returns.show', ['id' => $id])
        @livewire('back-end.sales-order-returns-detailes.create')
        @livewire('back-end.sales-orders-returns-detailes.update')
        @livewire('back-end.sales-order-returns-detailes.delete')
        @livewire('back-end.sales-order-returns-detailes.show')

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
            Livewire.on('purcgaseOrderDetailesUpdateMS', function()
            {
                toastr.success('تم تحديث الصنف بنجاح', 'رسالة تعديل', { timeOut: 5000 });
            });



            Livewire.on('salesOrderReturnsDetailesCreateMS', function()
            {
                toastr.success('تم اضافة نوع الصنف بنجاح', 'رسالة اضافة', { timeOut: 5000 });
            });


            Livewire.on('purchaseOrderDetailesDeleteMS', function()
            {
                toastr.error('تم حذف الصنف بنجاح', 'رسالة حذف', { timeOut: 5000 });
            });


            Livewire.on('purcgaseOrderDetailesErrorMS', function()
            {
                toastr.error('Error This Item Allready Find', 'Error Message', { timeOut: 5000 });
            });


        });
    </script>



@endsection
