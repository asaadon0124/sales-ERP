@extends('layouts.backEnd.app')

@section('title', 'انواع الحسابات المحذوفة')
@section('page_name', 'انواع الحسابات المحذوفة')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'انواع الحسابات المحذوفة')
@section('current_page_link', route('accounts_types.index'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid-theme.min.css') }}">
@endsection
@section('content')

    <!-- Main content -->
    <section class="content">
        @livewire('back-end.accounts-type.soft-delete')
        @livewire('back-end.accounts-type.restore')

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
        Livewire.on('accountsTypesRestoreMS', function()
        {
            toastr.success('تم تفعيل نوع الحساب بنجاح', 'رسالة تفعيل', { timeOut: 5000 });
        });

        Livewire.on('accountsTypesValidationMS', function()
        {
            toastr.success('نوع الحساب غير موجود', 'رسالة تحزير', { timeOut: 5000 });
        });



         Livewire.on('accountsTypesErrorMS', function()
        {
            toastr.error('هناك خطا حاول مرة اخري', 'رسالة خطا', { timeOut: 5000 });
        });

    });
</script>
@endsection
