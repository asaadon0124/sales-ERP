@extends('layouts.backEnd.app')

@section('title', 'شيفتات المستخدم')
@section('page_name', 'شيفتات المستخدم')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'شيفتات المستخدم ')
@section('current_page_link', route('shifts.index'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid-theme.min.css') }}">
@endsection
@section('content')

    <!-- Main content -->
    <section class="content">
        @livewire('back-end.shifts.data')
        @livewire('back-end.shifts.create')
        @livewire('back-end.shifts.update')
        @livewire('back-end.shifts.aprove')

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
        Livewire.on('shiftsUpdateMS', function()
        {
            toastr.success('تم تحديث الشيفت بنجاح', 'رسالة تعديل ', { timeOut: 5000 });
        });


        Livewire.on('closeModalByJs', function ()
        {
            const modalEl = document.getElementById('createModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modalInstance.hide();
        });

        Livewire.on('shiftsCreateMS', function()
        {
            toastr.success('تم اضافة الشيفت بنجاح', 'رسالة اضافة', { timeOut: 5000 });
        });


         Livewire.on('shiftsUpdateMS', function()
        {
            toastr.success('تم انهاء الشيفت بنجاح', 'رسالة تعديل', { timeOut: 5000 });
        });


         Livewire.on('treasuriesValidationMS', function()
        {
            toastr.success('هذا الشيفت غير متاح', 'رسالة خطا', { timeOut: 5000 });
        });


        Livewire.on('shiftsErrorMS', function()
        {
            toastr.error('هناك خطا ما برجاء المحاولة لاحقا', 'رسالة خطا', { timeOut: 5000 });
        });

        // Livewire.on('shiftsShow', function(data)
        // {
        //     // Redirect to the show page
        //     window.location.href = '/admin/treasuries/show/' + data.id; // Adjust the URL as needed
        // });

    });
</script>
@endsection
