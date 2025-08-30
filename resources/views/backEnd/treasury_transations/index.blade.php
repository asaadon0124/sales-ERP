@extends('layouts.backEnd.app')

@section('title', 'حركات النقدية')
@section('page_name', 'حركات تحصيل النقدية')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'حركات تحصيل النقدية')
@section('current_page_link', route('treasury_transations.index'))

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
        @livewire('back-end.treasury-transation.data')
        @livewire('back-end.treasury-transation.delete')
        @livewire('back-end.treasury-transation.show')
    </section>
    <!-- /.content -->

@endsection


@section('js')
    <!-- jsGrid -->
    <script src="{{ asset('assets/backEnd/plugins/jsgrid/demos/db.js') }}"></script>
    <script src="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
        Livewire.on('treasury_transationsUpdateMS', function()
        {
            toastr.success('تم تحديث التحصيل بنجاح', 'رسالة تحديث', { timeOut: 5000 });
        });


        Livewire.on('treasury_transationsCreateMS', function()
        {
            // alert('dsd');
            toastr.success('تم اضافة التحصيل بنجاح', 'رسالة اضافة', { timeOut: 5000 });
            // Flasher['success']('Update Successfully');
        });

        Livewire.on('treasury_transationsDeleteMS', function()
        {
            toastr.error('تم حذف التحصيل بنجاح', 'رسالة حذف', { timeOut: 5000 });
        });


        Livewire.on('treasury_transationsErrorMS', function()
        {
            toastr.error('هناك خطا ما برجاء المحاولة فيما بعد ', 'رسالة خطا', { timeOut: 5000 });
        });

        // Livewire.on('treasury_transationsShow', function(data)
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
        $('#account_id').select2().on('change', function (e)
        {
            let selectedValue = $(this).val();

            // alert(selectedValue);
            Livewire.dispatch('account_id_changed', { value: selectedValue });
        });
    });
</script>


<script>
    $(document).ready(function ()
    {
        $('#move_type').select2().on('change', function (e)
        {
            let selectedValue = $(this).val();
            Livewire.dispatch('Mtypes', { value: selectedValue });
        });
    });
</script>
@endsection
