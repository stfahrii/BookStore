@extends('layouts.admin')
@section('title', 'Peminjaman Buku Per Transaksi')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Bookora</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Transaksi Peminjaman</span>
</div>

<h1 class="page-title">Peminjaman Buku (Per Transaksi User)</h1>
<p class="page-subtitle">Disusun per transaksi sehingga Admin dapat menyetujui seluruh buku dalam 1 kali klik.</p>

<div class="card">
    <div class="card-header">
        <span class="card-title">Daftar Transaksi Peminjaman Siswa</span>
        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
            <a href="{{ route('admin.peminjaman', ['status' => 'semua']) }}" class="btn btn-sm {{ $filter === 'semua' ? 'btn-primary' : 'btn-secondary' }}">Semua</a>
            <a href="{{ route('admin.peminjaman', ['status' => 'menunggu']) }}" class="btn btn-sm {{ $filter === 'menunggu' ? 'btn-primary' : 'btn-secondary' }}">Menunggu</a>
            <a href="{{ route('admin.peminjaman', ['status' => 'disetujui']) }}" class="btn btn-sm {{ $filter === 'disetujui' ? 'btn-primary' : 'btn-secondary' }}">Sedang Dipinjam</a>
            <a href="{{ route('admin.peminjaman', ['status' => 'kembali']) }}" class="btn btn-sm {{ $filter === 'kembali' ? 'btn-primary' : 'btn-secondary' }}">Kembali (Baik)</a>
            <a href="{{ route('admin.peminjaman', ['status' => 'terlambat']) }}" class="btn btn-sm {{ $filter === 'terlambat' ? 'btn-primary' : 'btn-secondary' }}">Terlambat</a>
            <a href="{{ route('admin.peminjaman', ['status' => 'rusak']) }}" class="btn btn-sm {{ $filter === 'rusak' ? 'btn-primary' : 'btn-secondary' }}">Rusak (Suspend)</a>
        </div>
    </div>
    <div class="table-responsive">
        @if($pinjam_list->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Kode TRX</th>
                    <th>Siswa Peminjam</th>
                    <th>Daftar Buku</th>
                    <th>Batas Waktu</th>
                    <th>Kondisi & Denda</th>
                    <th>Status</th>
                    <th style="text-align: right; width: 230px;">Aksi Verifikasi Admin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pinjam_list as $row)
                @php
                    $total_buku = $row->details->count();
                    $tgl_kembali_time = strtotime($row->tanggal_kembali);
                    $today_time = strtotime(date('Y-m-d'));
                    $selisih_hari = floor(($today_time - $tgl_kembali_time) / 86400);
                    $denda_display = $row->denda > 0 ? $row->denda : ($row->status === 'terlambat' && $selisih_hari > 0 ? $selisih_hari * 1000 : 0);
                @endphp
                <tr>
                    <td>
                        <strong style="font-family: monospace; color: #4F46E5; font-size: 13.5px;">{{ $row->kode_transaksi }}</strong>
                        <div style="font-size: 11.5px; color: #64748B;">{{ date('d M Y', strtotime($row->tanggal_pinjam)) }}</div>
                    </td>
                    <td>
                        <strong>{{ $row->user->nama ?? '-' }}</strong>
                        <div style="font-size: 12px; color: #64748B;">NIS: {{ $row->user->nis ?? '-' }} · {{ $row->user->kelas ?? '-' }}</div>
                        @if($row->user && $row->user->status_akun === 'terkunci')
                            <span class="badge badge-danger" style="font-size: 10.5px; margin-top: 3px; display: inline-block;">AKUN DISUSPEND</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 4px; max-width: 320px;">
                            @foreach($row->details as $idx => $it)
                                <div class="{{ $idx >= 3 ? 'buku-hidden-' . $row->id : '' }}" {!! $idx >= 3 ? 'style="display:none;"' : '' !!}
                                     style="font-size: 12.5px; background: #F8FAFC; padding: 4px 8px; border-radius: 4px; border: 1px solid #E2E8F0;">
                                    • <strong>{{ $it->buku->judul ?? '-' }}</strong> ({{ $it->jumlah }}x)
                                </div>
                            @endforeach

                            @if($total_buku > 3)
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-more-{{ $row->id }}"
                                        style="height: 24px; font-size: 11px; margin-top: 2px;"
                                        onclick="toggleBukuList({{ $row->id }})">
                                    Lihat Selengkapnya ({{ $total_buku - 3 }} buku lagi)
                                </button>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div>{{ date('d M Y', strtotime($row->tanggal_kembali)) }}</div>
                        @if($row->status === 'kembali' && $row->tanggal_dikembalikan)
                            <div style="font-size: 11px; color: #16A34A;">Kembali: {{ date('d M Y', strtotime($row->tanggal_dikembalikan)) }}</div>
                        @endif
                    </td>
                    <td>
                        @if($row->status === 'rusak')
                            <span class="badge badge-danger">Kondisi: Rusak</span>
                            <div style="font-size: 12px; color: #DC2626; font-weight: 700; margin-top: 2px;">
                                Ganti Rugi: Rp {{ number_format($row->denda, 0, ',', '.') }}
                            </div>
                        @elseif($row->status === 'hilang')
                            <span class="badge badge-danger">Kondisi: Hilang</span>
                            <div style="font-size: 12px; color: #DC2626; font-weight: 700; margin-top: 2px;">
                                Ganti Buku: Rp {{ number_format($row->denda, 0, ',', '.') }}
                            </div>
                        @elseif($row->status === 'terlambat')
                            <span class="badge badge-warning">Telat {{ $selisih_hari }} Hari</span>
                            <div style="font-size: 12px; color: #DC2626; font-weight: 700; margin-top: 2px;">
                                Denda: Rp {{ number_format($denda_display, 0, ',', '.') }}
                            </div>
                            <div style="font-size: 10.5px; color: #64748B;">(Rp 1.000 / hari)</div>
                        @elseif($row->status === 'kembali')
                            <span class="badge badge-success">Kondisi: Baik</span>
                            @if($row->denda > 0)
                                <div style="font-size: 11px; color: #64748B;">Denda: Rp {{ number_format($row->denda, 0, ',', '.') }}</div>
                            @endif
                        @else
                            <span style="font-size: 12px; color: #64748B;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($row->status === 'menunggu')
                            <span class="badge badge-warning">Menunggu</span>
                        @elseif($row->status === 'disetujui')
                            <span class="badge badge-primary">Sedang Dipinjam</span>
                        @elseif($row->status === 'kembali')
                            <span class="badge badge-success">Selesai Kembali</span>
                        @elseif($row->status === 'terlambat')
                            <span class="badge badge-danger">Terlambat (Suspend)</span>
                        @elseif($row->status === 'rusak')
                            <span class="badge badge-danger">Rusak (Suspend)</span>
                        @elseif($row->status === 'hilang')
                            <span class="badge badge-danger">Hilang (Suspend)</span>
                        @else
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        @if($row->status === 'menunggu')
                            <a href="{{ route('admin.peminjaman.setujui', $row->id) }}" class="btn btn-success btn-sm"
                               onclick="return confirm('Setujui semua buku dalam peminjaman ini sekaligus?')">
                                Setujui Semua
                            </a>
                            <a href="{{ route('admin.peminjaman.tolak', $row->id) }}" class="btn btn-danger btn-sm"
                               onclick="return confirm('Tolak peminjaman ini?')">
                                Tolak
                            </a>
                        @elseif(in_array($row->status, ['disetujui', 'terlambat']))
                            <div style="display: flex; flex-direction: column; gap: 4px; align-items: stretch; max-width: 170px; margin-left: auto;">
                                <a href="{{ route('admin.peminjaman.kembalikan', ['id' => $row->id, 'kondisi' => 'baik']) }}" 
                                   class="btn btn-primary btn-sm btn-full"
                                   onclick="return confirm('Buku dikembalikan dalam KONDISI BAIK? Stok akan otomatis bertambah.')">
                                    Kembalikan (Baik)
                                </a>
                                <a href="{{ route('admin.peminjaman.kembalikan', ['id' => $row->id, 'kondisi' => 'rusak']) }}" 
                                   class="btn btn-secondary btn-sm btn-full"
                                   style="color: #DC2626; border-color: #FCA5A5; background: #FEF2F2;"
                                   onclick="return confirm('PERINGATAN SANKSI: Buku rusak akan dikenakan denda dan akun siswa ({!! addslashes($row->user->nama ?? 'Siswa') !!}) OTOMATIS DISUSPEND. Lanjutkan?')">
                                    Catat Rusak (Suspend)
                                </a>
                            </div>
                        @elseif($row->user && $row->user->status_akun === 'terkunci')
                            <a href="{{ route('admin.anggota.toggle', $row->user->id) }}" 
                               class="btn btn-warning btn-sm"
                               onclick="return confirm('Buka kunci / cabut status suspend untuk siswa {{ $row->user->nama }}?')">
                                Cabut Suspend
                            </a>
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
                <div class="empty-state-title">Belum ada transaksi peminjaman</div>
                <div class="empty-state-desc">Peminjaman buku oleh siswa akan dikelompokkan per transaksi di sini.</div>
            </div>
        @endif
    </div>
</div>

<script>
function toggleBukuList(trxId) {
    const hiddenItems = document.querySelectorAll('.buku-hidden-' + trxId);
    const btn = document.getElementById('btn-more-' + trxId);
    let isHidden = true;

    hiddenItems.forEach(el => {
        if (el.style.display === 'none') {
            el.style.display = 'block';
            isHidden = false;
        } else {
            el.style.display = 'none';
            isHidden = true;
        }
    });

    btn.innerText = isHidden ? 'Lihat Selengkapnya' : 'Sembunyikan';
}
</script>
@endsection
