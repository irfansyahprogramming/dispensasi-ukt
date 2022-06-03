@extends('print.layouts.pdf', ['title' => 'Cetak Penerima Dispensasi'])

@section('content')
  <h6 class="text-bold">Penerima Dispensasi</h6>
  <table id="dataTabel" class="table table-hover" border="1">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">NIM</th>
        <th scope="col">Nama</th>
        <th scope="col">Program Studi</th>
        <th scope="col">Jenis Dispensasi</th>
        <th scope="col">Kel.UKT</th>
        <th scope="col">Nom.UKT</th>
        <th scope="col">File Pendukung</th>
        <th scope="col">Status Pengajuan Dispensasi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($pengajuan as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->nim }}</td>
          <td>{{ $item->nama }}</td>
          <td>{{ $item->jenjang_prodi }} {{ $item->nama_prodi }}</td>
          <td>{{ $item->jenis }}</td>
          <td>{{ $item->kelompok }}</td>
          <td>{{ number_format($item->nominal_ukt, 0) }}</td>
          <td>
            @if ($item->file_pernyataan)
              <a href="{{ asset('storage/' . $item->file_pernyataan) }}" target="_blank" title="Surat Pernyataan Kebenaran Dokumen">Surat Pernyataan</a><br />
            @endif
            @if ($item->file_keterangan)
              <a href="{{ asset('storage/' . $item->file_keterangan) }}" target="_blank" title="Surat Keterangan dari kelurahan untuk yang terdampak">Surat Keterangan</a><br />
            @endif
            @if ($item->file_penghasilan)
              <a href="{{ asset('storage/' . $item->file_penghasilan) }}" target="_blank" title="Slip Gaji/Surat Keterangan Penghasilan yang disahkan oleh Lurah/Kepala Desa">Slip Gaji</a><br />
            @endif
            @if ($item->file_pailit)
              <a href="{{ asset('storage/' . $item->file_pailit) }}" target="_blank" title="Keputusan Pengadilan yang bersifat tetap untuk yang mengalami pailit/Surat Keterangan dari Kelurahan tentang usaha yang mengalami kebangkrutan">Surat Keterangan Pailit</a><br />
            @endif
            @if ($item->file_phk)
              <a href="{{ asset('storage/' . $item->file_phk) }}" target="_blank" title="Surat Keterangan Kematian/Surat Keterangan PHK/SK Pensiun/Keterangan Dokter jika sakit permanen">Surat PHK/Kematian</a><br />
            @endif
            @if ($item->file_pratranskrip)
              <a href="{{ asset('storage/' . $item->file_pratranskrip) }}" target="_blank">[Pra Transkrip]</a>
            @endif
          </td>
          <td>{{ $item->status ?? '' }}</td>
        </tr>
      @endforeach

    </tbody>
  </table>
@endsection
