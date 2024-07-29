@extends('layouts.app')
@section('content')
    <style>
        .form-group select {
            max-width: 100%;
        }

        .btn {
            margin-top: 10px;
        }
    </style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Admin Panel</div>

                    <div class="card-body px-0">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th class="text-nowrap">Email</th>
                                        <th>Roles</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <form id="assign-roles-{{ $user->id }}" method="POST"
                                                    class="d-flex flex-column align-items-start mb-3"
                                                    style="max-width: 300px;">
                                                    @csrf
                                                    <div class="form-group w-100 mb-2">
                                                        <label for="roles-{{ $user->id }}"
                                                            class="form-label w-100">Assign Roles</label>
                                                        <select name="roles[]" id="roles-{{ $user->id }}" multiple
                                                            class="form-control">
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->name }}"
                                                                    {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                                    {{ $role->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button type="button" class="btn btn-info btn-sm mt-0"
                                                        onclick="assignRoles({{ $user->id }})">
                                                        Assign Roles
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <form id="block-unblock-{{ $user->id }}" method="POST">
                                                    @csrf
                                                    <button type="button"
                                                        class="btn {{ $user->is_blocked ? 'btn-success' : 'btn-danger' }}"
                                                        onclick="toggleBlock({{ $user->id }})">
                                                        <i
                                                            class="bi {{ $user->is_blocked ? 'bi-unlock' : 'bi-lock' }}"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $users->links('pagination::bootstrap-4') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>


    <script>
        $(document).ready(function() {
            $('select[name="roles[]"]').select2({
                placeholder: "Select roles",
                allowClear: true
            });
        });

        function assignRoles(userId) {
            var form = $('#assign-roles-' + userId);
            $.ajax({
                url: '/admin/users/' + userId + '/assign-roles',
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    alert(response.success);
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        if (xhr.responseJSON.message === "User does not have the right roles.") {
                            alert('You do not have permission to perform this action.');
                        } else {
                            alert('Error: ' + xhr.responseJSON.message);
                        }
                    } else {
                        alert('Error assigning roles.');
                    }
                }

            });
        }


        function toggleBlock(userId) {
            var form = $('#block-unblock-' + userId);
            var button = form.find('button');
            var url = button.hasClass('btn-danger') ? '/admin/users/' + userId + '/block' : '/admin/users/' + userId +
                '/unblock';

            $.ajax({
                url: url,
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    alert(response.success);
                    // Toggle button classes and icon
                    if (button.hasClass('btn-danger')) {
                        button.removeClass('btn-danger').addClass('btn-success');
                        button.html('<i class="bi bi-unlock"></i>');
                    } else {
                        button.removeClass('btn-success').addClass('btn-danger');
                        button.html('<i class="bi bi-lock"></i>');
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        if (xhr.responseJSON.message === "User does not have the right roles.") {
                            alert('You do not have permission to perform this action.');
                        } else {
                            alert('Error: ' + xhr.responseJSON.message);
                        }
                    } else {
                        alert('Error changing user block status.');
                    }
                }
            });
        }
    </script>
@endsection
