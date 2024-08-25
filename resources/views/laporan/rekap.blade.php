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

  <section class="content">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Rekapitulasi Dispensasi {{ $semester }}</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
          {{-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
          </button> --}}
        </div>
      </div>
      <div class="card-body">
        <div class="mt-0">
          <button class="btn btn-outline-primary" id="btnFilter" data-toggle="modal" data-target="#modal-FilterData"><i class="ace-icon fa fa-list"></i> Filter</button>
        </div>

        <div class="mt-4 table-responsive">
          <table id="dataTabel" class="table table-hover">
            <thead>
              <tr>
                <th scope="col" class="align-middle" rowspan="2">No</th>
                <th scope="col" class="align-middle" rowspan="2">Jenis Keringanan</th>
                <th scope="col" class="text-center" colspan="8">Status</th>
              </tr>
              <tr>
                <th scope="col">Pengajuan Awal</th>
                <th scope="col">Disetujui Dekan/Fakultas</th>
                <th scope="col">Terkirim Ke Kantor WR2</th>
                <th scope="col">Disetujui Kantor WR2</th>
                <th scope="col">Terkirim ke Hutalak dan WR1</th>
                <th scope="col">SK Telah dibuat</th>
                <th scope="col">Proses Perubahan Tagihan</th>
                <th scope="col">Selesai</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($rekap as $item)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $item->jenis_dispensasi }}</td>
                  <td>{{ $item->ajuan0 }}</td>
                  <td>{{ $item->ajuan1 }}</td>
                  <td>{{ $item->ajuan2 }}</td>
                  <td>{{ $item->ajuan3 }}</td>
                  <td>{{ $item->ajuan4 }}</td>
                  <td>{{ $item->ajuan5 }}</td>
                  <td>{{ $item->ajuan6 }}</td>
                  <td>{{ $item->ajuan7 }}</td>
                </tr>
              @endforeach

            </tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
@endsection

@section('script')
  <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
  <script>
    $(function() {

      $("#dataTabel1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

      $('#dataTabel').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });

    // function cetakPenerima(){
    //     var semester = $('#semester').val();
    //     alert(semester);
    // }
  </script>
@endsection
