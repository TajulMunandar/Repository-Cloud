@extends('layout.application')

@section('content')
    <div class="container">
        <h2 class="mb-4 fw-bold">🗑 History File Terhapus</h2>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">File di Trash</h5>
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama File</th>
                            <th>Tipe</th>
                            <th>Ukuran</th>
                            <th>Deleted At</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($files as $index => $file)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $file->file_name }}</td>
                                <td>{{ $file->file_type }}</td>
                                <td>{{ number_format($file->file_size / 1024, 2) }} KB</td>
                                <td>{{ $file->deleted_at }}</td>
                                <td>
                                    <form action="{{ route('files.restore', $file->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning"
                                            onclick="return confirm('Restore file ini?')">♻ Restore</button>
                                    </form>
                                    <form action="{{ route('files.forceDelete', $file->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Hapus permanen file ini?')">❌ Hapus Permanen</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada file di Trash</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
