@extends('layouts.backEnd.app')

@section('title', 'المستخدمين')
@section('page_name', ' المستخدمين')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', ' المستخدمين ')
@section('current_page_link', route('emoloyees.index'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid-theme.min.css') }}">
@endsection
@section('content')

    <!-- Main content -->
    <section class="content">
        @livewire('back-end.emoloyees.data')
        @livewire('back-end.emoloyees.create')
        @livewire('back-end.emoloyees.update')
        @livewire('back-end.emoloyees.delete')
        {{-- @livewire('back-end.admins.show') --}}
        {{-- @livewire('back-end.admin-treasuries.Show') --}}

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
        Livewire.on('adminsUpdateMS', function()
        {
            toastr.success('تم تحديث المستخدم بنجاح', 'رسالة تعديل', { timeOut: 5000 });
        });



        Livewire.on('adminsCreateMS', function()
        {
            toastr.success('تم اضافة  المستخدم بنجاح', 'رسالة اضافة', { timeOut: 5000 });
        });


        Livewire.on('adminsErrorMS', function()
        {
            toastr.error('Error This Item Allready Find', 'Error Message', { timeOut: 5000 });
        });

        // Livewire.on('adminsShow', function(data)
        // {
        //     // Redirect to the show page
        //     window.location.href = '/admin/treasuries/show/' + data.id; // Adjust the URL as needed
        // });

    });
</script>



@endsection
