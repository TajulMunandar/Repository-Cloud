@extends('layout.application')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>File yang Dibagikan kepada Saya</h4>
                    </div>
                    <div class="card-body">
                        @if ($sharedFiles->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama File</th>
                                            <th>Dibagikan Oleh</th>
                                            <th>Izin</th>
                                            <th>Kadaluarsa</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sharedFiles as $share)
                                            <tr>
                                                <td>{{ $share->file->file_name }}</td>
                                                <td>{{ $share->sharedBy->first_name }} {{ $share->sharedBy->last_name }}
                                                </td>
                                                <td>
                                                    @if ($share->permission == 'view')
                                                        <span class="badge bg-info">Lihat</span>
                                                    @else
                                                        <span class="badge bg-success">Unduh</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($share->expires_at)
                                                        {{ $share->expires_at->format('d/m/Y H:i') }}
                                                    @else
                                                        Tidak ada
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('files.view', $share->file->id) }}"
                                                        class="btn btn-sm btn-info">Lihat</a>
                                                    @if ($share->permission == 'download')
                                                        <a href="{{ route('files.download', $share->file->id) }}"
                                                            class="btn btn-sm btn-success">Unduh</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center">Belum ada file yang dibagikan kepada Anda.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
