@extends('adminlte::page')
@section('title', 'Users - ATLAS Blog')

@section('content_header')
<h1>Users Table</h1>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Profession</th>
                        <th>Birthday</th>
                        <th>Gender</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>



<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    @csrf
                    <input type="hidden" id="editUserId">
                    <div class="mb-3">
                        <label for="editUserName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editUserName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editUserEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserProfession" class="form-label">Profession</label>
                        <input type="name" class="form-control" id="editUserProfession" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserBirthday" class="form-label">Birthday</label>
                        <input type="date" class="form-control" id="editUserBirthday" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserGender" class="form-label">Gender</label>
                        <input type="name" class="form-control" id="editUserGender" required>
                    </div>

                    <div class="mb-3">
                        <label for="editUserRole" class="form-label">Role</label>
                        <select class="form-control" id="editUserRole" required>
                            <option value="User">User</option>
                            <option value="Writer">Writer</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
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

<script type="text/javascript">

    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#myTable')) {
            $('#myTable').DataTable().destroy();
        }

        let table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'profession', name: 'profession' },
                { data: 'birthday', name: 'birthday' },
                { data: 'gender', name: 'gender' },
                {
                    data: 'roles',
                    name: 'roles',
                    render: function (data) {
                        if (data && data.length > 0) {
                            return data.map(role => `${role.name}`).join(' ');
                        }
                        return '<span class="badge bg-secondary">No Role</span>';
                    }
                },
                {
                    data: 'id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                        return `
                        <button class="btn btn-sm btn-primary editBtn" data-id="${data}" data-bs-toggle="modal" data-bs-target="#editUserModal">
                            <i class="fas fa-edit"> Edit</i>
                        </button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="${data}" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash"> Delete</i>
                        </button>
                    `;
                    }
                }
            ],
            error: function (xhr, error, code) {
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
                error: function () {
                    alert('Error deleting user.');
                }
            });
        });

        $(document).on('click', '.editBtn', function () {
            let userId = $(this).data('id');
            $.get(`/admin/users/${userId}/edit`, function (user) {
                $('#editUserId').val(user.id);
                $('#editUserName').val(user.name);
                $('#editUserEmail').val(user.email);
                $('#editUserProfession').val(user.profession);
                $('#editUserBirthday').val(user.birthday);
                $('#editUserGender').val(user.gender);

                if (user.roles && user.roles.length > 0) {
                    let roleNames = user.roles.map(role => role.name).join(', ');
                    $('#editUserRole').val(roleNames);
                } else {
                    $('#editUserRole').val('No Role');
                }
                
            });
        });

        $('#editUserForm').submit(function (e) {
            e.preventDefault();
            let userId = $('#editUserId').val();
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
                success: function () {
                    $('#editUserModal').modal('hide');
                    table.ajax.reload();
                },
                error: function () {
                    alert('Error updating user.');
                }
            });
        });

    });
</script>
@stop