@extends('print.layouts.pdf', ['title' => 'Cetak Penerima Dispensasi'])

@section('content')
  <h6 class="text-bold">Penerima Dispensasi UKT</h6>
  <table id="dataTabel" class="table table-hover" border="1">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">NIM</th>
        <th scope="col">Nama</th>
        <th scope="col">Program Studi</th>
        <th scope="col">Kel.UKT</th>
        <th scope="col">Nom.UKT</th>
        <th scope="col">Jenis Dispensasi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($pengajuan as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->nim }}</td>
          <td>{{ $item->nama }}</td>
          <td>{{ $item->jenjang_prodi }} {{ $item->nama_prodi }}</td>
          <td>{{ $item->kelompok }}</td>
          <td>{{ number_format($item->nominal_ukt, 0) }}</td>
          <td>{{ $item->jenis }}</td>
        </tr>
      @endforeach

    </tbody>
  </table>
@endsection
