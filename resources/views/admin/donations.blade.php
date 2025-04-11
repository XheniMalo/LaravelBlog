@extends('adminlte::page')
@section('title', __('messages.donations') . ' - ATLAS Blog')

@section('content_header')
<h1>{{ __('messages.donations') }}</h1>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap5.min.css">
@stop

@section('content')

<div class="container-fluid">

    <div class="card">
        <div class="card-body">
            <div class="toast align-items-center text-white bg-success border-0" id="successToast" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <span id="toastMessage"></span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
            
            <table id="donationsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.no') }}</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.amount') }}</th>
                        <th>{{ __('messages.currency') }}</th>
                        <th>{{ __('messages.payment_status') }}</th>
                        <th>{{ __('messages.created_at') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function() {
    $('#donationsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('admin/donations') }}",
        columns: [
            { data: 'id', name: 'DT_RowIndex' },
            { data: 'user.name', name: 'user.name' }, 
            { data: 'amount', name: 'amount' },
            { data: 'currency', name: 'currency' },
            { data: 'payment_status', name: 'payment_status' },
            { data: 'created_at', name: 'created_at' },
        ],
        error: function (xhr, error, code) {
                console.error('Error:', xhr.responseText);
            },
        responsive: true,
        order: [[5, 'desc']] 
    });
});
</script>
@stop
