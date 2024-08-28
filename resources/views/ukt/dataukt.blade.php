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
        <h3 class="card-title">Data Kelompok UKT</h3>

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
          {{-- <button class="btn btn-outline-success" onclick="#"><i class="ace-icon fa fa-download"></i> Surat Pernyataan</button> --}}

          <button class="btn btn-outline-primary" id="btnTambahDataUKT" data-toggle="modal" data-target="#modal-dataUKT"><i class="ace-icon fa fa-plus"></i> Tambah Data UKT</button>

          @if ($errors->any())
            <div class="alert alert-danger mt-2">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>
        <div class="mt-4 table-responsive">
          <table id="dataTabel" class="table table-hover">
            <thead>
              <tr>
                <th scope="col" rowspan="2">No</th>
                <th scope="col" rowspan="2">Program Studi</th>
                <th scope="col" rowspan="2">Angkatan</th>
                <th scope="col" colspan="10" class="text-center">Nominal Kelompok UKT</th>
                <th scope="col" rowspan="2">Aksi</th>
              </tr>
              <tr>
                <th scope="col">I</th>
                <th scope="col">II</th>
                <th scope="col">III</th>
                <th scope="col">IV</th>
                <th scope="col">V</th>
                <th scope="col">VI</th>
                <th scope="col">VII</th>
                <th scope="col">VIII</th>
                <th scope="col">Beasiswa</th>
                <th scope="col">Kerjasama</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($list_ukt as $item)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $item->namaprodi }}</td>
                  <td>{{ $item->angkatan }}</td>
                  <td>{{ $item->ukt_1 }}</td>
                  <td>{{ $item->ukt_2 }}</td>
                  <td>{{ $item->ukt_3 }}</td>
                  <td>{{ $item->ukt_4 }}</td>
                  <td>{{ $item->ukt_5 }}</td>
                  <td>{{ $item->ukt_6 }}</td>
                  <td>{{ $item->ukt_7 }}</td>
                  <td>{{ $item->ukt_8 }}</td>
                  <td>{{ $item->ukt_beasiswa }}</td>
                  <td>{{ $item->ukt_kerjasama }}</td>
                  <td class="btn-group text-center">
                    <button type="button" data-toggle="tooltip" data-placement="top" title="Edit Data" class="btn btn-sm btn-outline-warning" onclick="editDataUKT({{ $item->id }})"><i class="fas fa-edit"></i> </button>

                    <form action="{{ route('dataUKT.delete', ['id' => $item->id]) }}" method="POST">
                      @csrf
                      @method('delete')
                      <button type="submit" data-toggle="tooltip" data-placement="top" title="Hapus Data" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin akan menghapus data ini ?')"><i class="fas fa-trash"></i> </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

    </div>

    <div id="modal-dataUKT" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header py-2">
            <h5 class="modal-title">Form Data UKT</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body py-2">
            <div class="preloader text-center" style="display: none">
              <img src="{{ asset('img/preloader.gif') }}" alt="Loading" width="50">
            </div>

            <form action="{{ route('dataUKT.simpan') }}" method="POST" id="formDataUKT" enctype="multipart/form-data">
              @csrf
              <div class="form-body">
                <div class="form-group">
                  <label for="kode_prodi">Program Studi</label>
                  <select name="kode_prodi" id="kode_prodi" class="form-control form-control-border required autocomplete="off"">
                    <option value="">Pilih Jenis Dispensasi</option>
                    @foreach ($listProdi->isi as $kd)
                      <option value="{{ $kd->kodeProdi }}">{{ $kd->kodeProdi }} - {{ $kd->jenjangProdi }} {{ $kd->namaProdi }}</option>
                    @endforeach
                  </select>
                  <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                </div>

                <div class="form-group">
                  <label for="angkatan">Angkatan</label>
                  <input type="number" name="angkatan" class="form-control form-control-border" id="angkatan" placeholder="Angkatan">
                </div>
                <div class="form-group">
                  <label for="ukt_1">Kelompok I</label>
                  <input type="number" name="ukt_1" class="form-control form-control-border" id="ukt_1" placeholder="0">
                </div>
                <div class="form-group">
                  <label for="ukt_2">Kelompok II</label>
                  <input type="number" name="ukt_2" class="form-control form-control-border" id="ukt_2" placeholder="0">
                </div>
                <div class="form-group">
                  <label for="ukt_3">Kelompok III</label>
                  <input type="number" name="ukt_3" class="form-control form-control-border" id="ukt_3" placeholder="0">
                </div>
                <div class="form-group">
                  <label for="ukt_4">Kelompok IV</label>
                  <input type="number" name="ukt_4" class="form-control form-control-border" id="ukt_4" placeholder="0">
                </div>
                <div class="form-group">
                  <label for="ukt_5">Kelompok V</label>
                  <input type="number" name="ukt_5" class="form-control form-control-border" id="ukt_5" placeholder="0">
                </div>
                <div class="form-group">
                  <label for="ukt_6">Kelompok VI</label>
                  <input type="number" name="ukt_6" class="form-control form-control-border" id="ukt_6" placeholder="0">
                </div>
                <div class="form-group">
                  <label for="ukt_7">Kelompok VII</label>
                  <input type="number" name="ukt_7" class="form-control form-control-border" id="ukt_7" placeholder="0">
                </div>
                <div class="form-group">
                  <label for="ukt_8">Kelompok VIII</label>
                  <input type="number" name="ukt_8" class="form-control form-control-border" id="ukt_8" placeholder="0">
                </div>
                <div class="form-group">
                  <label for="ukt_beasiswa">Kelompok Beasiswa</label>
                  <input type="number" name="ukt_beasiswa" class="form-control form-control-border" id="ukt_beasiswa" placeholder="0">
                </div>
                <div class="form-group">
                  <label for="ukt_kerjasama">Kelompok Kerjasama</label>
                  <input type="number" name="ukt_kerjasama" class="form-control form-control-border" id="ukt_kerjasama" placeholder="0">
                </div>

              </div>
          </div>
          <div class="modal-footer py-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          </div>
        </div>
        </form>
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
        // "paging": false,
        // "lengthChange": false,
        // "searching": false,
        // "ordering": true,
        // "info": true,
        // "autoWidth": false,
        "responsive": true,
        "layout": {
          "bottomEnd": {
              "paging": {
                  "type": 'simple'
              }
          }
        }
      });
    });

    function editDataUKT(id) {
      //alert(id);
      //Ajax Load data from ajax
      $.ajax({
        url: "/dataUKT/edit/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
          console.log(data);
          var nom = data.nominal_ukt;
          $('[name="kode_prodi"]').val(data.kode_prodi);
          $('[name="angkatan"]').val(data.angkatan);
          $('[name="ukt_1"]').val(data.ukt_1.toFixed(0));
          $('[name="ukt_2"]').val(data.ukt_2.toFixed(0));
          $('[name="ukt_3"]').val(data.ukt_3.toFixed(0));
          $('[name="ukt_4"]').val(data.ukt_4.toFixed(0));
          $('[name="ukt_5"]').val(data.ukt_5.toFixed(0));
          $('[name="ukt_6"]').val(data.ukt_6.toFixed(0));
          $('[name="ukt_7"]').val(data.ukt_7.toFixed(0));
          $('[name="ukt_8"]').val(data.ukt_8.toFixed(0));
          $('[name="ukt_beasiswa"]').val(data.ukt_beasiswa.toFixed(0));
          $('[name="ukt_kerjasama"]').val(data.ukt_kerjasama.toFixed(0));


          $("#modal-dataUKT").modal('show');
          $('.modal-title').text('Edit Data UKT'); // Set title to Bootstrap modal title

        },
        error: function(jqXHR, textStatus, errorThrown) {
          alert('Error get data from ajax');
        }
      });
    }
  </script>
@endsection
