@extends('layouts.backEnd.app')

@section('title', 'التقارير')
@section('page_name', 'تقارير الاصناف')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'تقارير الاصناف ')
@section('current_page_link', route('Reborts.items.index'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid-theme.min.css') }}">
    <style>
        @page {
    margin: 0;
}
    @media print {
        body * {
            visibility: hidden; /* أخفي كل حاجة */
        }

        #print-area, #print-area * {
            visibility: visible; /* أظهر بس الـ div ده */
        }

        #print-area {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }

        .no-print {
            display: none !important; /* أخفي الأزرار وخلافه */
        }
    }
</style>

@endsection
@section('content')

    <!-- Main content -->
    <section class="content">
        @livewire('back-end.reborts.items.data')
        @livewire('back-end.reborts.items.show-invoice')
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
            Livewire.on('rebortsUpdateMS', function()
            {
                toastr.success('Update Successfully', 'Updated', { timeOut: 5000 });
            });


            Livewire.on('rebortsCreateMS', function()
            {
                toastr.success('تم اضافة القسم بنجاح', 'رسالة اضافة', { timeOut: 5000 });
            });


            Livewire.on('rebortsErrorMS', function()
            {
                toastr.error('Error This Item Allready Find', 'Error Message', { timeOut: 5000 });
            });

            // Livewire.on('rebortsShow', function(data)
            // {
            //     // Redirect to the show page
            //     window.location.href = '/admin/treasuries/show/' + data.id; // Adjust the URL as needed
            // });

        });
    </script>

    <script>
    function printDiv(divId)
    {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload(); // عشان يرجع كل حاجة بعد الطباعة
    }
</script>
@endsection
