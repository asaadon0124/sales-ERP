@extends('layouts.backEnd.app')

@section('title', 'الاصناف')
@section('page_name', 'الاصناف')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'الاصناف')
@section('current_page_link', route('items.index'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid-theme.min.css') }}">
@endsection
@section('content')

    <!-- Main content -->
    <section class="content">
        @livewire('back-end.Items.data')
        @livewire('back-end.Items.delete')


        {{-- @livewire('back-end.Items.show') --}}
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
        Livewire.on('itemsUpdateMS', function()
        {
            toastr.success('تم تحديث الصنف بنجاح', 'رسالة تحديث', { timeOut: 5000 });
        });


        Livewire.on('itemsCreateMS', function()
        {
            // alert('dsd');
            toastr.success('تم اضافة الصنف بنجاح', 'رسالة اضافة', { timeOut: 5000 });
            // Flasher['success']('Update Successfully');
        });


        Livewire.on('itemsErrorMS', function()
        {
            toastr.error('هناك خطا ما برجاء المحاولة فيما بعد ', 'رسالة خطا', { timeOut: 5000 });
        });



        Livewire.on('itemsDeleteMS', function()
        {
            toastr.error('تم حذف الصنف بنجاح ', 'رسالة حذف', { timeOut: 5000 });
        });

        Livewire.on('itemsValidationMS', function()
        {
            toastr.error('هذا الصنف غير موجوداو الصنف لديه كمية في المخازن اكبر من 0', 'رسالة تحزير', { timeOut: 10000 });
        });



        // Livewire.on('itemsShow', function(data)
        // {
        //     // Redirect to the show page
        //     window.location.href = '/admin/items/show/' + data.id; // Adjust the URL as needed
        // });

    });
</script>
@endsection
