@extends('layout.application')

@section('content')
    <div class="container">
        <h2 class="mb-4">📂 File Detail</h2>

        <!-- File Preview Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">👁 File Preview</h5>
            </div>
            <div class="card-body">
                @php
                    $filePath = asset('storage/' . $file->file_path);
                    $mimeType = $file->file_type;
                    $fileExtension = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
                @endphp

                @if (strpos($mimeType, 'image/') === 0)
                    <!-- Image Preview -->
                    <div class="text-center">
                        <img src="{{ $filePath }}" alt="{{ $file->file_name }}" class="img-fluid rounded shadow"
                            style="max-height: 500px;">
                    </div>
                @elseif($fileExtension === 'pdf')
                    <!-- PDF Preview -->
                    <div class="text-center">
                        <iframe src="{{ $filePath }}" width="100%" height="600px" class="border rounded">
                            <p>Your browser does not support iframes.
                                <a href="{{ $filePath }}" target="_blank">Click here to view the PDF</a>
                            </p>
                        </iframe>
                    </div>
                @elseif(strpos($mimeType, 'video/') === 0)
                    <!-- Video Preview -->
                    <div class="text-center">
                        <video controls class="w-100 rounded shadow" style="max-height: 500px;">
                            <source src="{{ $filePath }}" type="{{ $mimeType }}">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @elseif(strpos($mimeType, 'audio/') === 0)
                    <!-- Audio Preview -->
                    <div class="text-center">
                        <audio controls class="w-100">
                            <source src="{{ $filePath }}" type="{{ $mimeType }}">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                @elseif(strpos($mimeType, 'text/') === 0)
                    <!-- Text Preview -->
                    <div class="bg-light p-3 rounded">
                        <pre class="mb-0" style="max-height: 400px; overflow-y: auto; white-space: pre-wrap;">@php
                            try {
                                echo htmlspecialchars(
                                    file_get_contents(storage_path('app/public/' . $file->file_path)),
                                );
                            } catch (Exception $e) {
                                echo 'Unable to preview text file.';
                            }
                        @endphp</pre>
                    </div>
                @else
                    <!-- Generic File -->
                    <div class="text-center py-5">
                        <div class="mb-3">
                            📄
                        </div>
                        <h5>{{ $file->file_name }}</h5>
                        <p class="text-muted">Preview not available for this file type</p>
                        <a href="{{ route('files.view', $file->id) }}" target="_blank" class="btn btn-primary">👁 Open
                            File</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- File Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">ℹ️ File Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama File:</strong> {{ $file->file_name }}</p>
                        <p><strong>Tipe:</strong> {{ $file->file_type }}</p>
                        <p><strong>Ukuran:</strong> {{ number_format($file->file_size / 1024, 2) }} KB</p>
                        <p><strong>Diupload Oleh:</strong> {{ $file->user->username }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tanggal Upload:</strong> {{ $file->upload_date }}</p>
                        <p><strong>Tanggal Expired:</strong> {{ $file->expired_date ?? '-' }}</p>
                        <p><strong>Total Views:</strong> {{ $file->total_views }}</p>
                        <p><strong>Total Downloads:</strong> {{ $file->total_downloads }}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Throughput Upload:</strong>
                            {{ $file->upload_bw ? number_format($file->upload_bw, 2) . ' KB/s' : '-' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Durasi Upload:</strong>
                            {{ $file->upload_duration ? number_format($file->upload_duration, 2) . ' detik' : '-' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('files.view', $file->id) }}" target="_blank" class="btn btn-primary">👁 Open File</a>
                <a href="{{ route('files.download', $file->id) }}" download class="btn btn-success">⬇ Download</a>
                <button class="btn btn-warning" id="copyLinkBtn">📋 Copy Link</button>
                <a href="{{ route('files.index') }}" class="btn btn-secondary">⬅ Kembali</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('copyLinkBtn').addEventListener('click', function() {
            const link = "{{ route('files.view', $file->id) }}";
            navigator.clipboard.writeText(link).then(() => {
                alert('✅ Link berhasil disalin!');
            }).catch(err => {
                alert('❌ Gagal menyalin link');
            });
        });
    </script>
@endsection
