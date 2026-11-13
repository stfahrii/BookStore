@extends('layouts.admin')
@section('title', 'Pesan Masuk (Contact Us)')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Bookora</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Pesan User (Contact to Admin)</span>
</div>

<h1 class="page-title">Pesan Masuk dari User</h1>
<p class="page-subtitle">Daftar pertanyaan dan masukan yang dikirim oleh pengunjung atau user melalui menu Contact to Admin.</p>

<div class="card">
    <div class="card-header">
        <span class="card-title">Kotak Pesan Masuk</span>
    </div>
    <div class="table-responsive">
        @if($messages->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Pengirim</th>
                    <th>Subjek & Pesan</th>
                    <th>Waktu</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $m)
                <tr style="{{ $m->status === 'belum_dibaca' ? 'background: #F8FAFC; font-weight: 500;' : '' }}">
                    <td>
                        <strong>{{ $m->nama }}</strong>
                        <div style="font-size: 12px; color: #64748B;">{{ $m->email }}</div>
                    </td>
                    <td>
                        <div style="color: #4F46E5; font-weight: 600;">{{ $m->subjek }}</div>
                        <div style="font-size: 13px; color: #334155; margin-top: 4px;">{{ $m->pesan }}</div>
                    </td>
                    <td>{{ date('d M Y H:i', strtotime($m->created_at)) }}</td>
                    <td>
                        @if($m->status === 'belum_dibaca')
                            <span class="badge badge-warning">Belum Dibaca</span>
                        @else
                            <span class="badge badge-success">Sudah Dibaca</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        @if($m->status === 'belum_dibaca')
                            <a href="{{ route('admin.pesan.baca', $m->id) }}" class="btn btn-secondary btn-sm">Tandai Dibaca</a>
                        @else
                            <span style="font-size: 12px; color: #94A3B8;">Selesai</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-state">
                <div class="empty-state-title">Belum ada pesan masuk</div>
                <div class="empty-state-desc">Pesan dari menu Contact to Admin akan tampil di sini.</div>
            </div>
        @endif
    </div>
</div>
@endsection
