`@extends('layouts.backEnd.app')

@section('title','فواتير مرتجع المبيعات ')

@section('page_name', 'فواتير مرتجع المبيعات  من العملاء')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'فواتير مرتجع المبيعات  من العملاء')
@section('current_page_link', route('purchaseOrders.index_returns'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid-theme.min.css') }}">

    <!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

@endsection
@section('content')

    <!-- Main content -->
    <section class="content">
        @livewire('back-end.sales-order-returns.data')
        @livewire('back-end.sales-order-returns.create')
        @livewire('back-end.sales-order-returns.update')
        @livewire('back-end.sales-order-returns.delete')
        @livewire('back-end.sales-order-returns.aprove')



        {{-- @livewire('back-end.Items.show') --}}
    </section>
    <!-- /.content -->

@endsection


@section('js')
    <!-- jsGrid -->
    <script src="{{ asset('assets/backEnd/plugins/jsgrid/demos/db.js') }}"></script>
    <script src="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}


    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>



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
        Livewire.on('salesOrdersUpdateMS', function()
        {
            toastr.success('تم تحديث الفاتورة بنجاح', 'رسالة تحديث', { timeOut: 5000 });
        });


        Livewire.on('salesOrdersCreateMS', function()
        {
            // alert('dsd');
            toastr.success('تم اضافة الفاتورة بنجاح', 'رسالة اضافة', { timeOut: 5000 });
            // Flasher['success']('Update Successfully');
        });



        Livewire.on('salesOrdersDeleteMS', function()
        {
            // alert('dsd');
            toastr.Error('تم حذف الفاتورة بنجاح', 'رسالة حذف', { timeOut: 5000 });
        });



        Livewire.on('salesOrdersApproveMS', function()
        {
            // alert('dsd');
            toastr.Error('تم اعتماد الفاتورة بنجاح', 'رسالة تعديل', { timeOut: 5000 });
        });



        Livewire.on('salesOrdersErrorMS', function()
        {
            toastr.error('هناك خطا ما برجاء المحاولة فيما بعد ', 'رسالة خطا', { timeOut: 5000 });
        });

        // Livewire.on('salesOrdersShow', function(data)
        // {
        //     // Redirect to the show page
        //     window.location.href = '/admin/items/show/' + data.id; // Adjust the URL as needed
        // });

    });
</script>


<script>
    $(document).ready(function()
    {
        // Initialize Select2 Elements
        $('.select2').select2(
        {
            theme: 'bootstrap4'
        });
    });
</script>


<script>
    $(document).ready(function ()
    {
        $('#unit-select').select2().on('change', function (e)
        {
            let selectedValue = $(this).val();

            Livewire.dispatch('unitChanged',
            {
                newContent: selectedValue
            });
        });
    });
</script>



@endsection

