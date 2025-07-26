@extends('layouts.backEnd.app')

@section('title', 'عرض فاتورة المبيعات')
@section('page_name', 'عرض فاتورة المبيعات')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'عرض فاتورة المبيعات ')
@section('current_page_link', route('salesOrder.index'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid-theme.min.css') }}">


@endsection
@section('content')

    <!-- Main content -->
    <section class="content">
        @livewire('back-end.sales-order.show', ['id' => $id])
        @livewire('back-end.sales-order-detailes.create')
        @livewire('back-end.sales-order-detailes.update')
        @livewire('back-end.sales-order-detailes.delete')
        @livewire('back-end.sales-order-detailes.show')

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
            Livewire.on('salesOrderDtailesUpdateMS', function()
            {
                toastr.success('تم تحديث الفاتورة بنجاح', 'رسالة تعديل', { timeOut: 5000 });
            });



            Livewire.on('salesOrderDtailesCreateMS', function()
            {
                toastr.success('تم اضافة نوع الفاتورة بنجاح', 'رسالة اضافة', { timeOut: 5000 });
            });


            Livewire.on('salesOrderDtailesDeleteMS', function()
            {
                toastr.error('تم حذف الفاتورة بنجاح', 'رسالة حذف', { timeOut: 5000 });
            });


            Livewire.on('salesOrderDtailesErrorMS', function()
            {
                toastr.error('Error This Item Allready Find', 'Error Message', { timeOut: 5000 });
            });





        });
    </script>


<script>
    Livewire.on('redirectToIndex', () => {
        window.location.href = "{{ route('salesOrder.index') }}";
    });
</script>



@endsection
