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
      {{-- <div class="card-header">
        <h3 class="card-title">Dispensasi UKT</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div> --}}
      <div class="card-body">

        {{-- <div class="col-12"> --}}
          <div class="mt-4 mb-3 card card-success shadow-none">
              <div class="card-header">
                <ul class="nav nav-justified">
                  <li class="nav-item"><a data-toggle="tab" href="#ajuan" class="nav-link font-weight-bold h6 {{ session('ajuan_active') ?? '' }} show">PENGAJUAN</a></li>
                  <li class="nav-item"><a data-toggle="tab" href="#approve_dekan" class="nav-link font-weight-bold h6 {{ session('dekan_active') ?? '' }} show">Verval Fakultas/DEKAN</a>
                  </li>
                  <li class="nav-item"><a data-toggle="tab" href="#approve_wr2" class="nav-link font-weight-bold {{ session('wr2_active') ?? '' }} h6 show">Verval WAKIL REKTOR 2</a></li>
                  <li class="nav-item"><a data-toggle="tab" href="#approve_wr1" class="nav-link font-weight-bold {{ session('wr1_active') ?? '' }} h6 show">Proses Pembuatan SK</a></li>
                  <li class="nav-item"><a data-toggle="tab" href="#proses_bakhum" class="nav-link font-weight-bold {{ session('bakhum_active') ?? '' }} h6 show">Proses Tagihan BAKHUM</a></li>
                  <li class="nav-item"><a data-toggle="tab" href="#selesai" class="nav-link font-weight-bold {{ session('selesai') ?? '' }} h6 show">Selesai</a></li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane {{ session('ajuan_active') ?? '' }} show" id="ajuan" role="tabpanel">
                    @include('penerimaDispensasi.parts.pengajuan')
                  </div>
                  <div class="tab-pane {{ session('dekan_active') ?? '' }} show" id="approve_dekan" role="tabpanel">
                    @include('penerimaDispensasi.parts.approval_dekanat')
                  </div>
                  <div class="tab-pane {{ session('wr2_active') ?? '' }} show" id="approve_wr2" role="tabpanel">
                    @include('penerimaDispensasi.parts.approval_wr2')
                  </div>
                  <div class="tab-pane {{ session('wr1_active') ?? '' }} show" id="approve_wr1" role="tabpanel">
                    @include('penerimaDispensasi.parts.approval_wr1')
                  </div>
                  <div class="tab-pane {{ session('bakhum_active') ?? '' }} show" id="proses_bakhum" role="tabpanel">
                      @include('penerimaDispensasi.parts.bakhum_proses')
                  </div>
                  <div class="tab-pane {{ session('selesai') ?? '' }} show" id="selesai" role="tabpanel">
                      @include('penerimaDispensasi.parts.finish_proses')
                  </div>
                </div>
              </div>
          </div>
      {{-- </div> --}}
      
        {{-- <div class="mb-4">
          Semester : <br>
          Pencarian : <br>
        </div> --}}
        
  </section>
@endsection

@section('script')
  
  <script>
    $(function() {
            
      // $("#dataTabel1").DataTable({
      //   "responsive": true,
      //   "lengthChange": false,
      //   "autoWidth": false,
      //   "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      // }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      // new DataTable('table.table')
      // $('#table').DataTable();
      // $('#dataTabel').DataTable({
      //   // "paging": true,
      //   // "lengthChange": false,
      //   // "searching": true,
      //   // "ordering": true,
      //   // "info": true,
      //   // "autoWidth": false,
      //   // "responsive": true,
      //   "layout": {
      //     "bottomEnd": {
      //         "paging": {
      //             "type": 'simple'
      //         }
      //     }
      //   },
      //   // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      // })
      //.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      
      $('#dataTabelDekan').DataTable({
        // "paging": true,
        // "lengthChange": false,
        // "searching": true,
        // "ordering": true,
        // "info": true,
        // "autoWidth": false,
        // "responsive": true,
        "layout": {
          "bottomEnd": {
              "paging": {
                  "type": 'simple'
              }
          }
        }
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      })
      //.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      
      $('#dataTabelWR2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      })
      //.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      
      $('#dataTabelWR1').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      })
      //.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      
      $('#dataTabelBAKH').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      })
      //.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      
      $('#dataTabelFinish').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      })
      //.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    
    });
    
    
  </script>
@endsection
