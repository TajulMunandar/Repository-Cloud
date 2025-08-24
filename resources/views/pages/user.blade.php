@extends('layout.application')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Manajemen User</h4>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#userModal" onclick="resetForm()">
                    <i class="bi bi-plus-lg"></i> Tambah User
                </button>
            </div>
            <div class="card-body">

                {{-- 🔎 Filter --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select id="filter-active" class="form-select">
                            <option value="">-- Filter Status --</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="filter-role" class="form-select">
                            <option value="">-- Filter Role --</option>
                            <option value="1">Admin</option>
                            <option value="0">User</option>
                        </select>
                    </div>
                </div>

                {{-- 📊 DataTable --}}
                <div class="table-responsive">
                    <table id="usersTable" class="table table-striped table-hover table-bordered w-100">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Role</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data akan diisi oleh DataTable --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- 📝 Modal Form Tambah / Edit --}}
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="userForm">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="userModalLabel">Tambah User</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="user_id" name="id">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Depan</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Belakang</label>
                                <input type="text" name="last_name" id="last_name" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password <small class="text-muted">(kosongkan jika tidak
                                    diubah)</small></label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="is_active" id="is_active" class="form-select">
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select name="is_admin" id="is_admin" class="form-select">
                                    <option value="0">User</option>
                                    <option value="1">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let table;

        $(document).ready(function() {
            // Init DataTable
            table = $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('users.data') }}",
                    data: function(d) {
                        d.is_active = $('#filter-active').val();
                        d.is_admin = $('#filter-role').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        render: data => data == 1 ? '<span class="badge bg-success">Aktif</span>' :
                            '<span class="badge bg-danger">Tidak Aktif</span>'
                    },
                    {
                        data: 'is_admin',
                        name: 'is_admin',
                        render: data => data == 1 ?
                            '<span class="badge bg-primary">Admin</span>' :
                            '<span class="badge bg-secondary">User</span>'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Filter
            $('#filter-active, #filter-role').change(function() {
                table.ajax.reload();
            });

            // Simpan User
            $('#userForm').submit(function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let id = $('#user_id').val();
                let url = id ? "{{ url('users') }}/" + id : "{{ route('users.store') }}";
                let method = id ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    success: function(res) {
                        $('#userModal').modal('hide');
                        table.ajax.reload();
                        alert(res.message);
                    },
                    error: function(err) {
                        alert("Terjadi kesalahan");
                    }
                });
            });
        });

        // Reset form modal
        function resetForm() {
            $('#userForm')[0].reset();
            $('#user_id').val('');
            $('#userModalLabel').text('Tambah User');
        }

        // Edit User
        function editUser(id) {
            $.get("{{ url('users') }}/" + id, function(data) {
                $('#user_id').val(data.id);
                $('#first_name').val(data.first_name);
                $('#last_name').val(data.last_name);
                $('#username').val(data.username);
                $('#email').val(data.email);
                $('#is_active').val(data.is_active);
                $('#is_admin').val(data.is_admin);
                $('#userModalLabel').text('Edit User');
                $('#userModal').modal('show');
            });
        }

        // Hapus User
        function deleteUser(id) {
            if (confirm("Yakin hapus user ini?")) {
                $.ajax({
                    url: "{{ url('users') }}/" + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        table.ajax.reload();
                        alert(res.message);
                    }
                });
            }
        }
    </script>
@endpush
