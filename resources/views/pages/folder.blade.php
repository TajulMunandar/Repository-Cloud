@extends('layout.application')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('files.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $folder->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col d-flex justify-content-between align-items-center">
                <h2 class="fw-bold mb-0">📁 {{ $folder->name }}</h2>
                <div>
                    <button type="button" class="btn btn-success me-2" data-bs-toggle="modal"
                        data-bs-target="#createSubFolderModal">
                        📁 New Subfolder
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        ⬆ Upload File
                    </button>
                </div>
            </div>
        </div>

        <!-- Create Subfolder Modal -->
        <div class="modal fade" id="createSubFolderModal" tabindex="-1" aria-labelledby="createSubFolderModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createSubFolderModalLabel">Create Subfolder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createSubFolderForm">
                            @csrf
                            <div class="mb-3">
                                <label for="subFolderName" class="form-label">Folder Name</label>
                                <input type="text" class="form-control" id="subFolderName" name="name" required>
                            </div>
                            <button type="submit" class="btn btn-success">Create Subfolder</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Modal -->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header text-white" style="background: linear-gradient(135deg, #4e73df, #1cc88a);">
                        <h5 class="modal-title fw-bold" id="uploadModalLabel">📂 Upload File</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="uploadForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                            <div class="mb-3">
                                <label for="file" class="form-label fw-semibold">Choose File</label>
                                <div class="file-upload-wrapper rounded-3 border border-2 border-dashed text-center p-4"
                                    id="fileDropArea">
                                    <div class="upload-icon mb-2">📂</div>
                                    <p class="mb-1 fw-bold">Drag & Drop file here</p>
                                    <p class="text-muted small">or click to select from your device</p>
                                    <input type="file" class="form-control d-none" name="file" id="file"
                                        required>
                                    <button type="button" class="btn btn-sm btn-gradient mt-2"
                                        onclick="document.getElementById('file').click()">Browse File</button>
                                    <p class="mt-2 fw-semibold text-primary d-none" id="fileName"></p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="expired_date" class="form-label fw-semibold">Expired Date (Optional)</label>
                                <input type="date" class="form-control form-control-lg rounded-3" name="expired_date"
                                    id="expired_date">
                            </div>
                            <button type="submit" class="btn btn-gradient w-100 py-2 rounded-3 fw-bold">⬆ Upload
                                Sekarang</button>
                        </form>
                        <div class="progress mt-4 d-none rounded-3" id="uploadProgressContainer" style="height: 28px;">
                            <div id="uploadProgress" class="progress-bar progress-bar-striped progress-bar-animated fw-bold"
                                role="progressbar" style="width: 0%">0%</div>
                        </div>
                        <small id="uploadStats" class="text-muted d-none mt-2"></small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid View -->
        <div class="row">
            <!-- Subfolders -->
            @foreach ($folders as $subfolder)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100 folder-card"
                        onclick="window.location='{{ route('folders.show', $subfolder->id) }}'">
                        <div class="card-body text-center">
                            <div class="folder-icon mb-3">📁</div>
                            <h6 class="card-title">{{ $subfolder->name }}</h6>
                            <small class="text-muted">{{ $subfolder->files->count() }} files</small>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Files -->
            @foreach ($files as $file)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100 file-card">
                        <div class="card-body text-center">
                            <div class="file-icon mb-3">📄</div>
                            <h6 class="card-title text-truncate" title="{{ $file->file_name }}">{{ $file->file_name }}
                            </h6>
                            <small class="text-muted">{{ number_format($file->file_size / 1024, 2) }} KB</small>
                            <div class="mt-3">
                                <a href="{{ route('files.show', $file->id) }}"
                                    class="btn btn-sm btn-outline-info me-1">Detail</a>
                                <a href="{{ route('files.download', $file->id) }}"
                                    class="btn btn-sm btn-outline-success me-1">⬇</a>
                                <button class="btn btn-sm btn-outline-warning me-1"
                                    onclick="renameFile({{ $file->id }}, '{{ $file->file_name }}')">✏</button>
                                <button class="btn btn-sm btn-outline-primary me-1"
                                    onclick="shareFile({{ $file->id }})">📤</button>
                                <form action="{{ route('files.destroy', $file->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus file ini?')">🗑</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($folders->isEmpty() && $files->isEmpty())
            <div class="text-center mt-5">
                <div class="empty-state">
                    📂
                    <h4 class="mt-3">Folder kosong</h4>
                    <p class="text-muted">Belum ada file atau subfolder di sini.</p>
                </div>
            </div>
        @endif
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

        .folder-card,
        .file-card {
            cursor: pointer;
            transition: 0.3s;
            border: 2px solid transparent;
        }

        .folder-card:hover,
        .file-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #4e73df;
        }

        .folder-icon,
        .file-icon {
            font-size: 3rem;
            color: #6c757d;
        }

        .empty-state {
            font-size: 4rem;
            color: #dee2e6;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
        }
    </style>

    @push('scripts')
        <script>
            // File upload functionality
            const fileInput = document.getElementById('file');
            const fileDropArea = document.getElementById('fileDropArea');
            const fileName = document.getElementById('fileName');

            fileDropArea.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    fileName.classList.remove('d-none');
                    fileName.innerText = `✅ Selected: ${this.files[0].name}`;
                }
            });

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

            // Create subfolder functionality
            document.getElementById("createSubFolderForm").addEventListener("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                formData.append('parent_id', '{{ $folder->id }}');

                fetch("{{ route('folders.store') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    });
            });

            // Rename file function
            function renameFile(fileId, currentName) {
                let newName = prompt("Enter new file name:", currentName);
                if (newName && newName !== currentName) {
                    fetch(`{{ url('/files') }}/${fileId}/rename`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                file_name: newName
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        });
                }
            }

            // Share file function
            function shareFile(fileId) {
                let userId = prompt("Enter user ID to share with:");
                let permission = confirm("Allow download? (OK for yes, Cancel for view only)") ? 'download' : 'view';
                if (userId) {
                    fetch(`{{ url('/files') }}/${fileId}/share`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                shared_with: userId,
                                permission: permission
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('File shared successfully!');
                            } else {
                                alert('Error: ' + data.message);
                            }
                        });
                }
            }
        </script>
    @endpush
@endsection
