@extends('layouts.main')

@section('style')
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  {{-- <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}"> --}}
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
        <h3 class="card-title">Periode Pengajuan Keringanan UKT</h3>

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
        {{-- <button class="btn btn-outline-primary" id="btnCetakPenerima" data-toggle="modal" data-target="#modal-Cetak"><i class="ace-icon fa fa-plus"></i> Cetak Penerima </button> --}}
        @if ($errors->any())
          <div class="alert alert-danger mt-2">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        <button class="btn btn-outline-primary" id="btnTambahPeriode" data-toggle="modal" data-target="#modal-Periode"><i class="ace-icon fa fa-plus"></i> Periode Baru</button>

        <div class="mt-4 table-responsive">
          <table id="dataTabel" class="table table-hover">
            <thead>
              <tr>
                <th scope="col" class="text-center" style="width: 1%">No</th>
                <th scope="col" class="text-center" style="width: 5%">Aktif</th>
                <th scope="col">Kode Semester</th>
                <th scope="col">Semester</th>
                <th scope="col">Mulai Pembukaan</th>
                <th scope="col">Akhir Pembukaan</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($periode as $item)
                <tr>
                  <td class="text-center align-top">{{ $loop->iteration }}</td>
                  <td class="text-center align-top">
                    <div class="form-check form-switch">
                      <form id="formCheck_{{ $item->id }}" action="{{ route('periode.aktifin') }}" method="POST">
                        @csrf
                        @method('post')
                        <input type="hidden" id="id_periode" name="id_periode" value="{{ $item->id }}">
                        <input type="hidden" id="aktifCheck" name="aktifCheck" value="{{ $item->aktif }}">
                        <input class="form-check-input" type="checkbox" role="switch" value="{{ $item->aktif }}" {{ $item->aktif == '1' ? 'checked' : '' }} onclick="document.getElementById('formCheck_{{ $item->id }}').submit()">
                      </form>

                    </div>
                  </td>
                  <td>{{ $item->semester }}</td>
                  <td>{{ $item->des_semester }}</td>
                  <td>{{ $item->start_date }}</td>
                  <td>{{ $item->end_date }}</td>
                  <td class="btn-group text-center">
                    <button type="button" data-toggle="tooltip" data-placement="top" title="Edit Data" class="btn btn-sm btn-outline-warning mr-2" onclick="editPeriode({{ $item->id }})"><i class="fas fa-edit"></i> </button>
                    <form action="{{ route('periode.delete', ['id' => $item->id]) }}" method="POST">
                      @csrf
                      @method('delete')
                      <button type="submit" data-toggle="tooltip" data-placement="top" title="Hapus Data" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin akan menghapus Periode ini ?')"><i class="fas fa-trash"></i> </button>
                    </form>

                  </td>
                </tr>
              @endforeach

            </tbody>
          </table>
        </div>
      </div>

    </div>

    <div id="modal-Periode" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header py-2">
            <h5 class="modal-title">Cetak Penerima Dispensasi UKT</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="modal-body py-2">
              <form id="formAddPeriode" action="{{ route('periode.simpan') }}" method="POST">
                @csrf
                <div class="form-body">
                  <div class="form-group">
                    <label for="semester">Kode Semester</label>
                    <input type="text" class="form-control form-control-border" name="semester" id="semester" placeholder="Kode Semester yang digunakan UNJ misal. 117" required>
                  </div>
                  <div class="form-group">
                    <label for="des_semester">Semester</label>
                    <input type="text" class="form-control form-control-border" name="des_semester" id="des_semester" placeholder="Semester misal. 20221" required>
                  </div>
                  <div class="form-group">
                    <label>Mulai Pembukaan</label>
                    <div class="input-group date" id="start_date" data-target-input="nearest">
                      <input type="text" class="form-control form-control-border datetimepicker-input" data-target="#start_date" id="start_date" name="start_date" required />
                      <div class="input-group-append" data-target="#start_date" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Akhir Pembukaan</label>
                    <div class="input-group date" id="end_date" data-target-input="nearest">
                      <input type="text" class="form-control form-control-border datetimepicker-input" data-target="#end_date" id="end_date" name="end_date" required />
                      <div class="input-group-append" data-target="#end_date" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Aktif</label>
                    <div class="input-group date" data-target-input="nearest">
                      <input type="checkbox" class="form-control" id="aktif" name="aktif"/>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="modal-footer py-2">
            <button type="submit" class="btn btn-primary" onclick="document.getElementById('formAddPeriode').submit()"><i class="fas fa-solid fa-print"></i> Simpan</button>
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
  <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
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

      //Date and time picker
      $('#start_date').datetimepicker({
        icons: {
          time: 'far fa-clock'
        }
      });
      $('#end_date').datetimepicker({
        icons: {
          time: 'far fa-clock'
        }
      });

    });

    function editPeriode(id) {
      //alert(id);
      $("#aktif").prop("checked", false);
      //Ajax Load data from ajax
      $.ajax({
        url: "/periode/edit/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
          console.log(data);
          $('[name="semester"]').val(data.semester);
          $('[name="des_semester"]').val(data.des_semester);
          $('[name="start_date"]').val(convertDate(data.start_date));
          $('[name="end_date"]').val(convertDate(data.end_date));
          // $('[name="aktif"]').val(data.aktif);
          if(data.aktif === "1"){
            // alert(data.aktif);
            $("#aktif").prop("checked", true);
            // $('[name="aktif"]').checked = true;
          }

          $("#modal-Periode").modal('show');
          $('.modal-title').text('Edit Periode Pengajuan Keringanan UKT');
        },
        error: function(jqXHR, textStatus, errorThrown) {
          alert('Error get data from ajax');
        }
      });
    }

    function convertDate(date) {
      var momentDate = moment(date).format('MM/DD/y hh:ss A');
      return momentDate;
    }
  </script>
@endsection
