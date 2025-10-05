@extends('layout.application')

@section('content')
    <div class="container">
        <h2 class="mb-4 fw-bold">📁 File Management</h2>

        <div class="row mb-4">
            <!-- Total Bandwidth -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Throughput Terpakai</h6>
                        <h3 class="fw-bold text-warning">
                            {{ number_format($files->sum('upload_bw') / 1024 / 1024, 2) }} MB
                        </h3>
                        <small class="text-muted">Semua resource yang sudah dipakai</small>
                    </div>
                </div>
            </div>

            <!-- Total Files -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Ukuran </h6>
                        <h3 class="fw-bold text-primary">
                            {{ number_format($files->sum('file_size') / 1024 / 1024, 2) }} MB
                            @if (Auth::check() && Auth::user()->is_admin == 1)
                                <span class="text-success">/ ♾️ Unlimited</span>
                            @else
                                <span class="text-danger">/ 1024 MB</span>
                            @endif
                        </h3>
                        <small class="text-muted">Semua file yang sudah diupload</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total File Terupload</h6>
                        <h3 class="fw-bold text-success">
                            {{ $files->count() }} File
                        </h3>
                        <small class="text-muted">Jumlah keseluruhan file tersimpan</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col d-flex">
                <button type="button" class="btn btn-primary btn-lg shadow-sm mb-3 me-2" data-bs-toggle="modal"
                    data-bs-target="#uploadModal">
                    ⬆ Upload File
                </button>
                <a href="{{ route('files.trash') }}" class="btn btn-info btn-lg shadow-sm mb-3 ">
                    <span><i class="fa fa-trash"></i> History File</span>
                </a>
            </div>
        </div>
        <!-- Trigger Button -->


        <!-- Upload Modal -->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <!-- Modal Header -->
                    <div class="modal-header text-white" style="background: linear-gradient(135deg, #4e73df, #1cc88a);">
                        <h5 class="modal-title fw-bold" id="uploadModalLabel">📂 Upload File</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body p-4">
                        <form id="uploadForm" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="file" class="form-label fw-semibold">Choose File</label>
                                <div class="file-upload-wrapper rounded-3 border border-2 border-dashed text-center p-4"
                                    id="fileDropArea">
                                    <div class="upload-icon mb-2">
                                        📂
                                    </div>
                                    <p class="mb-1 fw-bold">Drag & Drop file here</p>
                                    <p class="text-muted small">or click to select from your device</p>
                                    <input type="file" class="form-control d-none" name="file" id="file"
                                        required>
                                    <button type="button" class="btn btn-sm btn-gradient mt-2"
                                        onclick="document.getElementById('file').click()">
                                        Browse File
                                    </button>
                                    <p class="mt-2 fw-semibold text-primary d-none" id="fileName"></p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="expired_date" class="form-label fw-semibold">Expired Date (Optional)</label>
                                <input type="date" class="form-control form-control-lg rounded-3" name="expired_date"
                                    id="expired_date">
                            </div>

                            <button type="submit" class="btn btn-gradient w-100 py-2 rounded-3 fw-bold">
                                ⬆ Upload Sekarang
                            </button>
                        </form>

                        <!-- Progress Bar -->
                        <div class="progress mt-4 d-none rounded-3" id="uploadProgressContainer" style="height: 28px;">
                            <div id="uploadProgress" class="progress-bar progress-bar-striped progress-bar-animated fw-bold"
                                role="progressbar" style="width: 0%">0%
                            </div>
                        </div>
                        <small id="uploadStats" class="text-muted d-none mt-2"></small>
                    </div>
                </div>
            </div>
        </div>


        <!-- File Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Daftar File</h5>
                <table id="filesTable" class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama File</th>
                            <th>Tipe</th>
                            <th>Ukuran</th>
                            <th>Upload Date</th>
                            <th>Expired Date</th>
                            <th>Views</th>
                            <th>Downloads</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($files as $index => $file)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $file->file_name }}</td>
                                <td>{{ $file->file_type }}</td>
                                <td>{{ number_format($file->file_size / 1024, 2) }} KB</td>
                                <td>{{ $file->upload_date }}</td>
                                <td>{{ $file->expired_date ?? '-' }}</td>
                                <td>{{ $file->total_views }}</td>
                                <td>{{ $file->total_downloads }}</td>
                                <td>
                                    <a href="{{ route('files.show', $file->id) }}" class="btn btn-sm btn-info">👁 View</a>
                                    <a href="{{ route('files.download', $file->id) }}" class="btn btn-sm btn-success">⬇
                                        Download</a>
                                    <form action="{{ route('files.destroy', $file->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Hapus file ini?')">🗑 Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .file-upload-wrapper {
            background: #f9fafb;
            cursor: pointer;
            transition: 0.3s;
        }

        .file-upload-wrapper:hover {
            background: #eef5ff;
            border-color: #4e73df;
        }

        .file-upload-wrapper.dragover {
            background: #e0f7ef;
            border-color: #1cc88a;
        }

        .upload-icon {
            font-size: 3rem;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            border: none;
            color: white;
            transition: 0.3s ease-in-out;
        }

        .btn-gradient:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(78, 115, 223, 0.3);
        }
    </style>

    <!-- DataTables Script -->
    @push('scripts')
        <script>
            const fileInput = document.getElementById('file');
            const fileDropArea = document.getElementById('fileDropArea');
            const fileName = document.getElementById('fileName');

            // click on area = trigger input
            fileDropArea.addEventListener('click', () => fileInput.click());

            // tampilkan nama file
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    fileName.classList.remove('d-none');
                    fileName.innerText = `✅ Selected: ${this.files[0].name}`;
                }
            });

            // drag & drop effect
            fileDropArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                fileDropArea.classList.add('dragover');
            });

            fileDropArea.addEventListener('dragleave', () => {
                fileDropArea.classList.remove('dragover');
            });

            fileDropArea.addEventListener('drop', (e) => {
                e.preventDefault();
                fileDropArea.classList.remove('dragover');

                if (e.dataTransfer.files.length > 0) {
                    fileInput.files = e.dataTransfer.files;
                    fileName.classList.remove('d-none');
                    fileName.innerText = `✅ Selected: ${e.dataTransfer.files[0].name}`;
                }
            });

            document.getElementById("uploadForm").addEventListener("submit", function(e) {
                e.preventDefault();

                let form = e.target;
                let formData = new FormData(form);
                let xhr = new XMLHttpRequest();

                let progressContainer = document.getElementById("uploadProgressContainer");
                let progressBar = document.getElementById("uploadProgress");
                let uploadStats = document.getElementById("uploadStats");

                progressContainer.classList.remove("d-none");
                uploadStats.classList.remove("d-none");

                let startTime = new Date().getTime();
                let lastSpeed = 0;

                xhr.upload.addEventListener("progress", function(e) {
                    if (e.lengthComputable) {
                        let percent = Math.round((e.loaded / e.total) * 100);
                        progressBar.style.width = percent + "%";
                        progressBar.innerText = percent + "%";

                        let elapsedTime = (new Date().getTime() - startTime) / 1000;
                        let speed = (e.loaded / 1024 / elapsedTime).toFixed(2);
                        lastSpeed = speed;

                        uploadStats.innerText = `⏱ ${elapsedTime.toFixed(2)}s | ⚡ ${speed} KB/s`;
                    }
                });

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            progressBar.classList.remove("bg-danger");
                            progressBar.classList.add("bg-success");

                            let endTime = new Date().getTime();
                            let uploadDuration = (endTime - startTime) / 1000;

                            uploadStats.innerText += " ✅ Upload Selesai";

                            // kirim update bandwidth & durasi ke server
                            fetch("{{ route('files.updateUploadStats') }}", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    file_name: form.querySelector('input[type="file"]').files[0]
                                        .name,
                                    upload_duration: uploadDuration.toFixed(2),
                                    upload_bw: lastSpeed
                                })
                            });

                            setTimeout(() => location.reload(), 1500);
                        } else {
                            progressBar.classList.add("bg-danger");
                            uploadStats.innerText = "❌ Upload gagal!";
                        }
                    }
                };

                xhr.open("POST", "{{ route('files.store') }}", true);
                xhr.setRequestHeader("X-CSRF-TOKEN", document.querySelector('input[name="_token"]').value);
                xhr.send(formData);
            });
        </script>
    @endpush
@endsection
