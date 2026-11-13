@extends('layouts.admin')
@section('title', 'List User Terdaftar')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Bookora</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">List User Terdaftar</span>
</div>

<h1 class="page-title">List User Terdaftar (Anggota Siswa)</h1>
<p class="page-subtitle">Kelola akun siswa, kunci/buka akses akun, dan pantau aktivitas transaksi siswa.</p>

<div class="card">
    <div class="card-header">
        <span class="card-title">Daftar Akun Siswa</span>
        <form method="GET" action="{{ route('admin.anggota') }}" style="display: flex; gap: 8px;">
            <input type="text" name="q" class="form-control" style="width: 240px; height: 34px; font-size: 13px;"
                   placeholder="Cari nama, NIS, email..." value="{{ $q }}">
            <button type="submit" class="btn btn-secondary btn-sm">Cari</button>
            @if($q)
                <a href="{{ route('admin.anggota') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        @if($anggota_list->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>NIS (Unik)</th>
                    <th>Nama & Kelas</th>
                    <th>Email & WhatsApp</th>
                    <th>Status Akun</th>
                    <th style="text-align: right; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($anggota_list as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span style="font-family: monospace; font-weight: 700; color: #4F46E5; font-size: 13.5px;">{{ $row->nis }}</span></td>
                    <td>
                        <strong>{{ $row->nama }}</strong>
                        <div style="font-size: 12px; color: #64748B;">Kelas: {{ $row->kelas ?? '-' }}</div>
                    </td>
                    <td>
                        <div>{{ $row->email }}</div>
                        <div style="font-size: 12px; color: #16A34A; font-weight: 500;">WA: {{ $row->telepon }}</div>
                    </td>
                    <td>
                        @if($row->status_akun === 'aktif')
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Terkunci (Off)</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        @if($row->status_akun === 'aktif')
                            <a href="{{ route('admin.anggota.toggle', $row->id) }}" class="btn btn-secondary btn-sm"
                               onclick="return confirm('Kunci akun {{ $row->nama }} agar tidak bisa login?')">
                                Kunci (Off)
                            </a>
                        @else
                            <a href="{{ route('admin.anggota.toggle', $row->id) }}" class="btn btn-success btn-sm"
                               onclick="return confirm('Buka kunci akun {{ $row->nama }}?')">
                                Buka (On)
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-state">
                <div class="empty-state-title">Belum ada akun siswa terdaftar</div>
                <div class="empty-state-desc">Siswa dapat mendaftarkan akun secara mandiri melalui form registrasi.</div>
            </div>
        @endif
    </div>
</div>
@endsection
