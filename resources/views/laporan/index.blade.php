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
        <h3 class="card-title">Laporan Dispensasi UKT Semester {{ $semester }}</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="mt-4">
          <button class="btn btn-outline-primary" id="btnFilter" data-toggle="modal" data-target="#modal-FilterData"><i class="ace-icon fa fa-list"></i> Filter</button>
        </div>

        <div class="mt-4 table-responsive">
          <table id="dataTabel" class="table table-hover">
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">NIM</th>
                <th scope="col">Nama</th>
                <th scope="col">Program Studi</th>
                <th scope="col">Jenis Dispensasi</th>
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
                  <td>{{ $item->status ?? '' }}</td>
                </tr>
              @endforeach

            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer">
      </div>
    </div>

    <div id="modal-FilterData" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header py-2">
            <h5 class="modal-title">Filter Daftar Dispensasi UKT</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="filter-laporan-pengajuan" action="{{ route('laporan.index') }}" method="GET">
              <div class="modal-body py-2">
                <div class="form-body">
                  <div class="form-group">
                    <label for="semester">Semester</label>
                    <select class="form-control" id="semester" name="semester">
                      <option value="All">Semua Semester</option>
                      @foreach ($listSemester as $sms)
                        <option value="{{ $sms->semester }}" {{ $sms->id == old('semester') ? 'selected' : '' }}>{{ $sms->semester }} / {{ $sms->des_semester }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="prodi">Program Studi</label>
                  <select class="form-control" id="prodi" name="prodi">
                    <option value="All">Semua Prodi</option>
                    @foreach ($listProdi->isi as $prd)
                      <option value="{{ $prd->kodeProdi }}" {{ $prd->kodeProdi == old('prodi') ? 'selected' : '' }}>{{ $prd->jenjangProdi }} {{ $prd->namaProdi }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="jenis">Jenis Dispensasi</label>
                  <select class="form-control" id="jenis" name="jenis">
                    <option value="All">Semua Jenis Dispensasi</option>
                    @foreach ($listJenis as $jns)
                      <option value="{{ $jns->id }}" {{ $jns->id == old('jenis') ? 'selected' : '' }}>{{ $jns->jenis_dispensasi }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="status">Status Pengajuan</label>
                  <select class="form-control" id="status" name="status">
                    <option value="All">Semua Status Pengajuan Dispensasi</option>
                    @foreach ($listStatus as $sts)
                      <option value="{{ $sts->id }}" {{ $sts->id == old('status') ? 'selected' : '' }}>{{ $sts->status_ajuan }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </form>

          </div>
          <div class="modal-footer py-2">
            <button type="button" class="btn btn-primary" onclick="document.getElementById('filter-laporan-pengajuan').submit();"><i class="fas fa-solid fa-filter"></i> Filter</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          </div>
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
