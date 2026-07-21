@extends('layouts.main')

@section('style')
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('contain')
    <section class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>{{ $subtitle }}</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">{{ $title }}</a></li>
                <li class="breadcrumb-item active">{{ $subtitle }}</li>
            </ol>
            </div>
        </div>
        </div>
    </section>

    @if ($errors->any())
    <div class="alert alert-danger mt-2">
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif  

    <section>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Pengajuan Keringanan UKT</h3>
                <a href="{{ route('penerima_dispensasi.pengajuan_export') }}" class="btn btn-success btn-sm float-right">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
            <div class="card-body">
                {{-- <button class="btn btn-outline-primary" id="btnCetakPenerima" data-toggle="modal" data-target="#modal-Cetak"><i class="ace-icon fa fa-plus"></i> Cetak Pengajuan </button> --}}
                <div class="mt-4 table-responsive">
                    <table id="table" class="table table-hover">
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
                        {{-- @dd($pengajuan) --}}
                        @foreach ($pengajuan as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->semester }}</td>
                            <td>{{ $item->nim }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->jenjang_prodi }} {{ $item->nama_prodi }}</td>
                            <td>{{ $item->jenis_dispensasi }}</td>
                            <td>{{ $item->kelompok }}</td>
                            <td>{{ number_format($item->nominal_ukt, 0) }}</td>
                            <td>
                            @if ($item->file_permohonan)
                                <a href="{{ asset('storage/' . $item->file_permohonan) }}" target="_blank" title="Surat Permohonan">Surat Permohonan</a><br />
                            @endif
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
                                <a href="{{ asset('storage/' . $item->file_pratranskrip) }}" target="_blank">Pra Transkrip</a>
                            @endif
                            </td>
                            <td><div class="alert alert-success">{{ $item->status_ajuan ?? '' }}</div> </td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>        
    </section>
    

    <div id="modal-Cetak" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-2">
            <h5 class="modal-title">Cetak Penerima Keringanan UKT</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            </div>
            <div class="modal-body">
            <div class="modal-body py-2">
                @if($cmode == '1' || $cmode == '4' || $cmode == '13' || $cmode == '20' || $cmode == '22'){
                <form id="cetak-penerima-dispensasi" action="{{ route('penerima_dispensasi.print', ['semester' => $semester, 'kode_prodi' => '0']) }}" method="get" target="_blank">
                @else
                <form id="cetak-penerima-dispensasi" action="{{ route('penerima_dispensasi.print', ['semester' => $semester, 'kode_prodi' => $unit]) }}" method="get" target="_blank">
                @endif
                
                <div class="form-body">
                    <div class="form-group">
                    <label for="kelompok_ukt">Format Cetak Laporan</label>
                    <select class="form-control" id="format" name="format" autocomplete="off" required>
                        <option value="">Pilih Format Cetak</option>
                        <option value="pdf">Pdf</option>
                        <option value="excel">Excel</option>
                    </select>
                    </div>
                </div>
                </form>

            </div>
            </div>
            <div class="modal-footer py-2">
            <button type="button" class="btn btn-primary" onclick="document.getElementById('cetak-penerima-dispensasi').submit()"><i class="fas fa-solid fa-print"></i> Cetak</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
        </div>
    </div>
@endsection

@section('script')
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
  <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
  <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
  <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
  <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<script>
$(function(){
  $('#table').DataTable({
        "paging": true,
        // "lengthChange": false,
        "searching": true,
        // "order": [[0, 'desc']],
        "order": false,
        "autoWidth": false,
        "responsive": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print"],
        "layout": {
          "bottomEnd": {
              "paging": {
                  "type": 'full'
              }
          }
        }
      }).buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)');
      $('#tableWR2').DataTable({
        "paging": true,
        // "lengthChange": false,
        "searching": true,
        // "order": [[0, 'desc']],
        "order": false,
        "autoWidth": false,
        "responsive": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print"],
        "layout": {
          "bottomEnd": {
              "paging": {
                  "type": 'full'
              }
          }
        }
      }).buttons().container().appendTo('#tableWR2_wrapper .col-md-6:eq(0)');
      $('#tableWD2').DataTable({
        "paging": true,
        // "lengthChange": false,
        "searching": true,
        // "order": [[0, 'desc']],
        "order": false,
        "autoWidth": false,
        "responsive": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print"],
        "layout": {
          "bottomEnd": {
              "paging": {
                  "type": 'full'
              }
          }
        }
      }).buttons().container().appendTo('#tableWD2_wrapper .col-md-6:eq(0)');
})
</script>
@endsection