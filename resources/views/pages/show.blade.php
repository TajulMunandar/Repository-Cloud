@extends('layout.application')

@section('content')
    <div class="container">
        <h2 class="mb-4">📂 File Detail</h2>
        <div class="card">
            <div class="card-body">
                <p><strong>Nama File:</strong> {{ $file->file_name }}</p>
                <p><strong>Tipe:</strong> {{ $file->file_type }}</p>
                <p><strong>Ukuran:</strong> {{ number_format($file->file_size / 1024, 2) }} KB</p>
                <p><strong>Diupload Oleh:</strong> {{ $file->user->username }}</p>
                <p><strong>Tanggal Upload:</strong> {{ $file->upload_date }}</p>
                <p><strong>Tanggal Expired:</strong> {{ $file->expired_date ?? '-' }}</p>
                <p><strong>Total Views:</strong> {{ $file->total_views }}</p>
                <p><strong>Total Downloads:</strong> {{ $file->total_downloads }}</p>
                <p><strong>Bandwidth Upload:</strong>
                    {{ $file->upload_bw ? number_format($file->upload_bw, 2) . ' KB/s' : '-' }}
                </p>
                <p><strong>Durasi Upload:</strong>
                    {{ $file->upload_duration ? number_format($file->upload_duration, 2) . ' detik' : '-' }}
                </p>
            </div>
            <div class="card-footer">
                <a href="{{ route('files.view', $file->id) }}" target="_blank" class="btn btn-primary">👁 Preview File</a>
                <a href="{{ route('files.download', $file->id) }}" download class="btn btn-success">⬇ Download</a>
                <a href="{{ route('files.index') }}" class="btn btn-secondary">⬅ Kembali</a>
            </div>
        </div>
    </div>
@endsection
