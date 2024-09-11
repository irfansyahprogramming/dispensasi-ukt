{{-- <button class="btn btn-outline-primary" id="btnCetakPenerima" data-toggle="modal" data-target="#modal-Cetak"><i class="ace-icon fa fa-plus"></i> Cetak Pengajuan </button> --}}
<div><h3>Verifikasi dan Validasi Fakultas/Dekan</h3></div>
<div class="mt-4table-responsive">
    <table id="tableWD2" class="table table-hover">
    <thead>
        <tr>
        <th scope="col">No</th>
        <th scope="col">Semester</th>
        <th scope="col">NIM</th>
        <th scope="col">Nama Lengkap</th>
        <th scope="col">Program Studi</th>
        <th scope="col">Jenis Keringanan</th>
        <th scope="col">Kel.UKT</th>
        <th scope="col">Nom.UKT</th>
        <th scope="col">File Pendukung</th>
        <th scope="col">Status Pengajuan</th>
        </tr>
    </thead>
    <tbody>
        
        @foreach ($verval_dekan as $row)
            {{-- @dd($row->semester) --}}
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $row->semester }}</td>
            <td>{{ $row->nim }}</td>
            <td>{{ $row->nama }}</td>
            <td>{{ $row->jenjang_prodi }} {{ $row->nama_prodi }}</td>
            <td>{{ $row->jenis_dispensasi }}</td>
            <td>{{ $row->kelompok }}</td>
            <td>{{ number_format($row->nominal_ukt, 0) }}</td>
            <td>
            @if ($row->file_pernyataan)
                <a href="{{ asset('storage/' . $row->file_pernyataan) }}" target="_blank" title="Surat Pernyataan Kebenaran Dokumen">Surat Pernyataan</a><br />
            @endif
            @if ($row->file_keterangan)
                <a href="{{ asset('storage/' . $row->file_keterangan) }}" target="_blank" title="Surat Keterangan dari kelurahan untuk yang terdampak">Surat Keterangan</a><br />
            @endif
            @if ($row->file_penghasilan)
                <a href="{{ asset('storage/' . $row->file_penghasilan) }}" target="_blank" title="Slip Gaji/Surat Keterangan Penghasilan yang disahkan oleh Lurah/Kepala Desa">Slip Gaji</a><br />
            @endif
            @if ($row->file_pailit)
                <a href="{{ asset('storage/' . $row->file_pailit) }}" target="_blank" title="Keputusan Pengadilan yang bersifat tetap untuk yang mengalami pailit/Surat Keterangan dari Kelurahan tentang usaha yang mengalami kebangkrutan">Surat Keterangan Pailit</a><br />
            @endif
            @if ($row->file_phk)
                <a href="{{ asset('storage/' . $row->file_phk) }}" target="_blank" title="Surat Keterangan Kematian/Surat Keterangan PHK/SK Pensiun/Keterangan Dokter jika sakit permanen">Surat PHK/Kematian</a><br />
            @endif
            @if ($row->file_pratranskrip)
                <a href="{{ asset('storage/' . $row->file_pratranskrip) }}" target="_blank">[Pra Transkrip]</a>
            @endif
            </td>
            <td><div class="alert alert-success">{{ $row->status_ajuan ?? '' }}</div> </td>
        </tr>
        @endforeach
    </tbody>
    </table>
</div>

