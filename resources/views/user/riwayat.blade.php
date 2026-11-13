@extends('layouts.user')
@section('title', 'Peminjaman Pustaka Saya')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('user.dashboard') }}">Bookora</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Peminjaman Pustaka Saya</span>
</div>

<h1 class="page-title">Riwayat Peminjaman Buku</h1>
<p class="page-subtitle">Pantau batas waktu pengembalian buku (7 hari), status sirkulasi, dan pengembalian buku.</p>

<!-- BANNER PERINGATAN SUSPENSI (TERLAMBAT / BUKU RUSAK) -->
@if(!empty($is_suspended))
    <div class="alert alert-error" style="border-left: 4px solid #DC2626; padding: 18px 20px; margin-bottom: 24px; background: #FEF2F2;">
        <div style="font-weight: 800; font-size: 15px; margin-bottom: 6px; display: flex; align-items: center; gap: 8px; color: #991B1B;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            @if(($suspended_type ?? '') === 'rusak')
                PERHATIAN: AKUN ANDA DIBEKUKAN / SUSPEND (SANKSI BUKU RUSAK)
            @elseif(($suspended_type ?? '') === 'terlambat')
                PERHATIAN: AKUN ANDA DISUSPEND DARI PEMINJAMAN (KETERLAMBATAN PUSTAKA)
            @else
                PERHATIAN: STATUS AKUN ANDA SEDANG TERKUNCI (SUSPEND)
            @endif
        </div>
        <div style="font-size: 13.5px; line-height: 1.6; color: #7F1D1D;">
            @if(($suspended_type ?? '') === 'rusak')
                Buku yang Anda kembalikan tercatat dalam kondisi <strong>RUSAK FISIK</strong>. Sesuai tata tertib perpustakaan, akun Anda dibekukan sementara dan Anda dikenakan sanksi ganti rugi sebesar <strong>Rp {{ number_format($total_denda_aktif ?? 0, 0, ',', '.') }}</strong>. Silakan segera temui petugas perpustakaan untuk proses pelunasan dan pembukaan suspend akun.
            @elseif(($suspended_type ?? '') === 'terlambat')
                Anda memiliki peminjaman buku yang <strong>melewati batas waktu 7 hari (Terlambat)</strong> dengan total denda berjalan <strong>Rp {{ number_format($total_denda_aktif ?? 0, 0, ',', '.') }}</strong> (Tarif: Rp 1.000 / hari telat). Selama masa suspend, Anda dilarang meminjam buku lain. Silakan segera kembalikan buku fisik ke perpustakaan.
            @else
                Akun Anda dinonaktifkan/dikunci oleh Petugas Perpustakaan karena sanksi peminjaman. Silakan hubungi bagian sirkulasi perpustakaan.
            @endif
        </div>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <span class="card-title">Daftar Peminjaman Saya (Per Transaksi)</span>
        @if(empty($is_suspended))
            <a href="{{ route('user.dashboard') }}" class="btn btn-primary btn-sm">Pinjam Buku Baru</a>
        @else
            <button class="btn btn-secondary btn-sm" disabled style="opacity: 0.6; cursor: not-allowed;" title="Selesaikan kewajiban pinjaman Anda untuk membuka suspend">
                Pinjam Ditutup (Akun Suspend)
            </button>
        @endif
    </div>
    <div class="table-responsive">
        @if($riwayat_list->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Kode TRX</th>
                    <th>Buku yang Dipinjam</th>
                    <th>Tgl Pinjam</th>
                    <th>Batas Kembali</th>
                    <th>Kondisi & Denda</th>
                    <th>Status</th>
                    <th style="width: 210px;">Aksi Pengembalian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayat_list as $row)
                @php
                    $tgl_kembali_time = strtotime($row->tanggal_kembali);
                    $today_time = strtotime(date('Y-m-d'));
                    $selisih_hari = floor(($today_time - $tgl_kembali_time) / 86400);
                @endphp
                <tr>
                    <td><strong style="font-family: monospace; color: #4F46E5;">{{ $row->kode_transaksi }}</strong></td>
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            @foreach($row->details as $it)
                                <div style="font-size: 13.5px; font-weight: 600; color: #0F172A;">
                                    • {{ $it->buku->judul ?? '-' }}
                                </div>
                                <div style="font-size: 11.5px; color: #64748B;">
                                    Penulis: {{ $it->buku->penulis ?? '-' }}
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td><strong>{{ date('d M Y', strtotime($row->tanggal_pinjam)) }}</strong></td>
                    <td>
                        <strong style="color: {{ $row->status === 'terlambat' ? '#DC2626' : '#475569' }};">
                            {{ date('d M Y', strtotime($row->tanggal_kembali)) }}
                        </strong>
                        @if($row->status === 'terlambat' && $selisih_hari > 0)
                            <div style="font-size: 11px; color: #DC2626; font-weight: 700;">
                                (Terlambat {{ $selisih_hari }} Hari)
                            </div>
                        @elseif($row->status === 'disetujui')
                            <div style="font-size: 11.5px; color: #4F46E5; font-weight: 600;">
                                {{ max(0, -$selisih_hari) }} hari tersisa
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($row->status === 'rusak')
                            <span class="badge badge-danger">Kondisi: Rusak</span>
                            <div style="font-size: 12px; color: #DC2626; font-weight: 700; margin-top: 3px;">
                                Ganti Rugi: Rp {{ number_format($row->denda, 0, ',', '.') }}
                            </div>
                        @elseif($row->status === 'hilang')
                            <span class="badge badge-danger">Kondisi: Hilang</span>
                            <div style="font-size: 12px; color: #DC2626; font-weight: 700; margin-top: 3px;">
                                Ganti Buku: Rp {{ number_format($row->denda, 0, ',', '.') }}
                            </div>
                        @elseif($row->status === 'terlambat')
                            <span class="badge badge-warning">Denda Berjalan</span>
                            <div style="font-size: 12px; color: #DC2626; font-weight: 700; margin-top: 3px;">
                                Rp {{ number_format($row->denda > 0 ? $row->denda : ($selisih_hari > 0 ? $selisih_hari * 1000 : 0), 0, ',', '.') }}
                            </div>
                            <div style="font-size: 10.5px; color: #64748B;">(Rp 1.000 / hari)</div>
                        @elseif($row->status === 'dikembalikan' || $row->status === 'kembali')
                            <span class="badge badge-success">Kondisi: Baik</span>
                            @if($row->denda > 0)
                                <div style="font-size: 11px; color: #64748B; margin-top: 2px;">
                                    Denda: Rp {{ number_format($row->denda, 0, ',', '.') }}
                                </div>
                            @endif
                        @else
                            <span style="font-size: 12px; color: #64748B;">Bebas Denda</span>
                        @endif
                    </td>
                    <td>
                        @if($row->status === 'menunggu')
                            <span class="badge badge-warning">Menunggu Konfirmasi</span>
                        @elseif($row->status === 'disetujui')
                            <span class="badge badge-primary">Sedang Dipinjam</span>
                        @elseif($row->status === 'dikembalikan' || $row->status === 'kembali')
                            <span class="badge badge-success">Sudah Dikembalikan</span>
                        @elseif($row->status === 'terlambat')
                            <span class="badge badge-danger">Terlambat (Suspend)</span>
                        @elseif($row->status === 'rusak')
                            <span class="badge badge-danger">Rusak (Suspend)</span>
                        @elseif($row->status === 'hilang')
                            <span class="badge badge-danger">Hilang (Suspend)</span>
                        @else
                            <span class="badge badge-neutral">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        @if(in_array($row->status, ['disetujui', 'terlambat']))
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <a href="{{ route('user.riwayat.kembalikan', ['id' => $row->id, 'kondisi' => 'baik']) }}" 
                                   class="btn btn-success btn-sm btn-full" 
                                   onclick="return confirm('Apakah Anda ingin mengembalikan buku ini dalam KONDISI BAIK?')">
                                    Kembalikan (Kondisi Baik)
                                </a>
                                <a href="{{ route('user.riwayat.kembalikan', ['id' => $row->id, 'kondisi' => 'rusak']) }}" 
                                   class="btn btn-secondary btn-sm btn-full" 
                                   style="color: #DC2626; border-color: #FCA5A5; background: #FEF2F2;"
                                   onclick="return confirm('PERHATIAN SANKSI: Jika Anda melaporkan buku rusak, Akun Anda akan OTOMATIS DISUSPEND / TERKUNCI dan dikenakan denda ganti rugi fisik buku. Lanjutkan pelaporan buku rusak?')">
                                    Lapor Buku Rusak
                                </a>
                            </div>
                        @elseif(in_array($row->status, ['dikembalikan', 'kembali']))
                            <span style="color: #16A34A; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                Transaksi Selesai
                            </span>
                        @elseif(in_array($row->status, ['rusak', 'hilang']))
                            <span style="color: #DC2626; font-size: 11.5px; font-weight: 700; line-height: 1.3; display: block;">
                                Akun Disuspend · Temui Petugas Perpustakaan
                            </span>
                        @else
                            <span style="color: #94A3B8; font-size: 12px;">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-state">
                <div class="empty-state-title">Belum ada riwayat peminjaman buku</div>
                <div class="empty-state-desc">Anda dapat mengajukan peminjaman buku secara mandiri melalui katalog buku.</div>
                <div style="margin-top: 16px;">
                    <a href="{{ route('user.dashboard') }}" class="btn btn-primary btn-sm">Mulai Pinjam Buku</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
