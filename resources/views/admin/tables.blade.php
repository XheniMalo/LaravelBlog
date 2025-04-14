@extends('adminlte::page')
@section('title', __('messages.users') . ' - ATLAS Blog')

@section('content_header')
    <h1>{{ __('messages.users_table') }}</h1>
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
                        aria-label="{{ __('messages.close') }}"></button>
                </div>
            </div>

            <div class="card-body">
                <a href="{{ route('download') }}" class="btn btn-primary mb-3">{{ __('messages.download_data') }}</a>
            </div>

            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.no') }}</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.email') }}</th>
                        <th>{{ __('messages.profession') }}</th>
                        <th>{{ __('messages.birthday') }}</th>
                        <th>{{ __('messages.gender') }}</th>
                        <th>{{ __('messages.role') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __('messages.delete_user') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.cancel') }}"></button>
            </div>
            <div class="modal-body">
                {{ __('messages.delete_user_confirmation') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">{{ __('messages.delete') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">{{ __('messages.edit_user') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.cancel') }}"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    @csrf
                    <input type="hidden" id="editUserId">
                    <div class="mb-3">
                        <label for="editUserName" class="form-label">{{ __('messages.name') }}</label>
                        <input type="text" class="form-control" id="editUserName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserEmail" class="form-label">{{ __('messages.email') }}</label>
                        <input type="email" class="form-control" id="editUserEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserProfession" class="form-label">{{ __('messages.profession') }}</label>
                        <input type="text" class="form-control" id="editUserProfession" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserBirthday" class="form-label">{{ __('messages.birthday') }}</label>
                        <input type="date" class="form-control" id="editUserBirthday" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserGender" class="form-label">{{ __('messages.gender') }}</label>
                        <input type="text" class="form-control" id="editUserGender" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserRole" class="form-label">{{ __('messages.role') }}</label>
                        <select class="form-select" id="editUserRole" required>
                            <option value="Writer">Writer</option>
                            <option value="User">User</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">{{ __('messages.update_user') }}</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        var modals = document.querySelectorAll('.modal');
        modals.forEach(function(modal) {
            new bootstrap.Modal(modal);
        });
        
        if ($.fn.DataTable.isDataTable('#myTable')) {
            $('#myTable').DataTable().destroy();
        }

        let table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.index') }}",
            responsive: true,
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'profession', name: 'profession' },
                { data: 'birthday', name: 'birthday' },
                { data: 'gender', name: 'gender' },
                {
                    data: 'roles', name: 'roles',
                    render: function (data) {
                        if (data && data.length > 0) {
                            return data.map(role => `${role.name}`).join(' | ');
                        }
                        return '';
                    }
                },
                {
                    data: 'id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                    return `
                    <div>
                        <button class="btn btn-sm btn-primary editBtn" data-id="${data}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="${data}" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    `;
                    }
                }
            ],
            error: function (xhr) {
                console.error('Error:', xhr.responseText);
            }
        });

        let deleteUserId;
        $(document).on('click', '.deleteBtn', function () {
            deleteUserId = $(this).data('id');
        });

        $('#confirmDelete').click(function () {
            $.ajax({
                url: `/admin/users/${deleteUserId}`,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function (response) {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                    $('#toastMessage').text(response.message);
                    let toast = new bootstrap.Toast(document.getElementById('successToast'));
                    toast.show();
                },
                error: function (xhr) {
                    console.error('Delete error:', xhr.responseText);
                    alert('{{ __("messages.delete_user_error") }}');
                }
            });
        });

        $(document).on('click', '.editBtn', function () {
            console.log("Edit button clicked");
            let userId = $(this).data('id');
            console.log("User ID:", userId);
            
            $.get(`/admin/users/${userId}/edit`, function (user) {
                console.log("User data received:", user);
                $('#editUserId').val(user.id);
                $('#editUserName').val(user.name);
                $('#editUserEmail').val(user.email);
                $('#editUserProfession').val(user.profession);
                $('#editUserBirthday').val(user.birthday);
                $('#editUserGender').val(user.gender);

                if (user.roles && user.roles.length > 0) {
                    let roles = user.roles.map(role => role.name);
                    console.log("User roles:", roles);
                    if (roles.includes('Writer') || roles.includes('writer')) {
                        $('#editUserRole').val('Writer');
                    } else if (roles.includes('User') || roles.includes('user')) {
                        $('#editUserRole').val('User');
                    }
                }
                
                let editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                editModal.show();
            }).fail(function(xhr, status, error) {
                console.error("Error fetching user data:", error);
                console.log("Response:", xhr.responseText);
                alert('{{ __("messages.edit_user_error") }}');
            });
        });

        $('#editUserForm').on('submit', function (e) {
            e.preventDefault();
            let userId = $('#editUserId').val();
            console.log("Submitting form for user:", userId);
            
            $.ajax({
                url: `/admin/users/${userId}`,
                type: 'PUT',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: {
                    name: $('#editUserName').val(),
                    email: $('#editUserEmail').val(),
                    profession: $('#editUserProfession').val(),
                    birthday: $('#editUserBirthday').val(),
                    gender: $('#editUserGender').val(),
                    role: $('#editUserRole').val(),
                },
                success: function (response) {
                    console.log("Update success:", response);
                    let editModal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
                    editModal.hide();
                    table.ajax.reload();
                    
                    $('#toastMessage').text('{{ __("messages.user_updated_successfully") }}');
                    let toast = new bootstrap.Toast(document.getElementById('successToast'));
                    toast.show();
                },
                error: function (xhr) {
                    console.error("Update error:", xhr.responseText);
                    alert('{{ __("messages.update_user_error") }}');
                }
            });
        });
    });
</script>
@stop