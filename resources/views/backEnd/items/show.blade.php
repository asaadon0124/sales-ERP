@extends('layouts.backEnd.app')

@section('title', 'عرض الصنف')
@section('page_name', 'عرض الصنف')
@section('main_page_name', 'الرئيسية')
@section('current_page_name', 'عرض الصنف')
@section('current_page_link', route('items.index'))

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
@livewire('back-end.items.show', ['id' => $id])
</section>
<!-- /.content -->

@endsection


@section('js')
<!-- jsGrid -->
<script src="{{ asset('assets/backEnd/plugins/jsgrid/demos/db.js') }}"></script>
<script src="{{ asset('assets/backEnd/plugins/jsgrid/jsgrid.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>



@endsection
