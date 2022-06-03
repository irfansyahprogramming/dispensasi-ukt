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
          <h3 class="card-title">Pengajuan Dispensasi UKT</h3>

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
                <button class="btn btn-outline-success" onclick="#"><i class="ace-icon fa fa-download"></i> Surat Pernyataan</button>

                @if ($tombol == '')
                    @if ($kipk == 'no' && $kerjasama == 'no')
                        <button class="btn btn-outline-primary" id="btnTambahPengajuan" data-toggle="modal" data-target="#modal-Dispensasi"><i class="ace-icon fa fa-plus"></i> Pengajuan Dispensasi UKT</button>
                    @else
                        <button class="btn btn-danger"><i class="ace-icon fa fa-ban"></i> Anda sedang berstatus Beasiswa atau mendapatkan Bantuan Pendidikan</button>
                    @endif
                @else
                    <button class="btn btn-danger"><i class="ace-icon fa fa-ban"></i> Periode Sudah Ditutup</button>
                @endif


                
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
                        <th scope="col">No</th>
                        <th scope="col">Semester</th>
                        <th scope="col">Jenis Dispensasi</th>
                        <th scope="col">Kelompok UKT</th>
                        <th scope="col">Nominal UKT</th>
                        <th scope="col">Pekerjaan Yang Membiayai</th>
                        <th scope="col">Jabatan Pekerjaan Yang Membiayai</th>
                        <th scope="col">Status Pengajuan Dispensasi</th>
                        <th scope="col">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuan as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->semester }}</td>
                            <td>{{ $item->jenis }}</td>
                            <td>{{ $item->kelompok }}</td>
                            <td>{{ $item->nom_ukt }}</td>
                            <td>{{ $item->pekerjaan }}</td>
                            <td>{{ $item->jabatan_kerja }}</td>
                            <td>{{ $item->status ?? '' }}</td>
                            <td class="btn-group text-center">
                                @if ($tombol == '' && $item->status_pengajuan == 0)
                                    <button type="button" data-toggle="tooltip" data-placement="top" title="Edit Data" class="btn btn-sm btn-outline-warning" onclick="editPengajuan({{ $item->id }})"><i class="fas fa-edit"></i> </button>
                                    <form action="{{ route('dispensasi.delete', ['id' => $item->id]) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" data-toggle="tooltip" data-placement="top" title="Hapus Data" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin akan menghapus data ini ?')"><i class="fas fa-trash"></i> </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-outline-danger disabled"><i class="ace-icon fa fa-edit"></i></button>
                                    <button type="button" class="btn btn-outline-warning disabled"><i class="ace-icon fa fa-trash"></i></button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    <!--    
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            <td>@mdo</td>
                            <td>
                                <button type="button" class="btn btn-outline-danger"><i class="ace-icon fa fa-edit"></i> Edit</button>
                                <button type="button" class="btn btn-outline-warning"><i class="ace-icon fa fa-trash"></i> Hapus</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                            <td>@fat</td>
                            <td>
                                <button type="button" class="btn btn-outline-danger">Edit</button>
                                <button type="button" class="btn btn-outline-warning">Hapus</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Jacob</td>
                            <td>Larry</td>
                            <td>the Bird</td>
                            <td>@twitter</td>
                            <td>@twitter</td>
                            <td>
                                <button type="button" class="btn btn-outline-danger">Edit</button>
                                <button type="button" class="btn btn-outline-warning">Hapus</button>
                            </td>
                        </tr>
                    -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
        </div>
      </div>

      <div id="modal-Dispensasi" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title">Form Pengajuan Dispensasi UKT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body py-2">
                <div class="preloader text-center" style="display: none">
                    <img src="{{ asset('img/preloader.gif') }}" alt="Loading" width="50">
                </div>
    
                <form action="{{ route('dispensasi.simpan') }}" method="POST" id="formPengajuan" enctype="multipart/form-data">
                    @csrf
                    <div class="form-body">
                        <div class="alert alert-info text-center">
                            <ul class="list-unstyled text-left">Catatan :
                                <li>Data Mahasiswa diambil dari SIAKAD, bila ada perbedaan data Mahasiswa anda, silakan perbaiki biodata anda di SIAKAD</li>
                                <li>Permohonan Dispensasi Berlaku hanya sekali permohonan</li>
                            </ul> 
                        </div>
                        <div class="row form-group">
                            <div class="col">
                                <label for="nim">NIM</label>
                                <p>{{ $nim }} </p>
                                <label for="prodi">Program Studi</label>
                                <p>{{ $nama_prodi }}</p>
                                <label for="hp">No HP</label>
                                <p>{{ $hp }}</p>
                            </div>
                            <div class="col">
                                <label for="nama">Nama Lengkap</label>
                                <p>{{ $nama_lengkap }}</p>
                                <label for="jenjang">Jenjang</label>
                                <p>{{ $jenjang }}</p>
                                <label for="email">Email</label>
                                <p class="font-italic">{{ $email }}</p>
                            </div>
                            <input type="hidden" class="form-control" name="semester" value="{{ $semester }}" id="semester">
                            <input type="hidden" class="form-control" name="nim" value="{{ $nim }}" id="nim">
                            <input type="hidden" class="form-control" name="nama" value="{{ $nama_lengkap }}" id="nama">
                            <input type="hidden" class="form-control" name="prodi" value="{{ $kodeProdi }}" id="prodi">
                            <input type="hidden" class="form-control" name="namaprodi" value="{{ $nama_prodi }}" id="namaprodi">
                            <input type="hidden" class="form-control" name="jenjang" value="{{ $jenjang }}" id="jenjang">
                            <input type="hidden" class="form-control" name="hp" value="{{ $hp }}" id="hp">
                            <input type="hidden" class="form-control" name="email" value="{{ $email }}" id="email">
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat Sekarang</label>
                            <textarea name="alamat" id="alamat" class="form-control form-control-border required" cols="20" rows="3" placeholder="Alamat Sekarang"></textarea>
                            <!--<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">-->
                            <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                        </div>
                        <div class="form-group">
                            <label for="jenis_dispensasi">Jenis Dispensasi</label>
                            <!--<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">-->
                            <select name="jenis_dispensasi" id="jenis_dispensasi" class="form-control form-control-border required autocomplete="off" onchange="uploadBukti(this.value)">
                              <option value="">Pilih Jenis Dispensasi</option>
                              @foreach ($list_dispensasi as $item)
                                <option value="{{ $item->id }}">{{ $item->jenis_dispensasi }}</option>
                              @endforeach
                            </select>
                            <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                        </div>
                        <div id="ques" class="row form-group" style="display: none;">
                            <div class="col">
                                <label for="semesterke">Saat ini semester berapa?</label>
                                <input type="number" name="semesterke" class="form-control form-control-border" id="semesterke" placeholder="Saat ini semester ke berapa?">
                            </div>
                            <div class="col">
                                <label for="sks">SKS Belum Lulus (Harus lebih kecil sama dengan 6 sks)</label>
                                <input type="number" name="sks_belum" class="form-control form-control-border" id="sks_belums" placeholder="SKS yang belum belum lulus">
                            </div>
                            
                        </div>
                        <div class="form-group">
                            <label for="kelompok_ukt">Kelompok UKT</label>
                            <!--<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">-->
                            <select name="kelompok_ukt" id="kelompok_ukt" class="form-control form-control-border required autocomplete="off">
                              <option value="">Pilih Kelompok UKT</option>
                              @foreach ($kelompok_ukt as $kel)
                                <option value="{{ $kel->id }}">{{ $kel->kelompok }}</option>
                              @endforeach
                            </select>
                            <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                        </div>
                        <div class="form-group">
                          <label for="nominal_ukt">Nominal UKT</label>
                          <input type="text" name="nominal_ukt" class="form-control form-control-border" id="nominal_ukt" pattern="^\Rp\d{1,3}(,\d{3})*(\.\d+)?Rp" value="" data-type="currency" placeholder="Nominal UKT anda saat ini">
                        </div>
                        <div class="form-group">
                            <label for="pekerjaan">Pekerjaan orang tua/pihak lain yang membiayai</label>
                            <input type="text" class="form-control form-control-border" name="pekerjaan" id="pekerjaan" placeholder="Pekerjaan orang tua/pihak lain yang membiayai">
                        </div>
                        <div class="form-group">
                            <label for="jabatan">Jabatan Pekerjaan yang membiayai</label>
                            <input type="text" class="form-control form-control-border" name="jabatan" id="jabatan" placeholder="Jabatan Pekerjaan yang membiayai">
                        </div>
                        <div class="form-group">
                            <label for="jabatan">File Pendukung Pengajuan Dispensasi UKT</label>
                            <br/>
                            <b>Surat Pernyataan <div id="nama_file_pernyataan"></div> </b>
                            <input type="file" class="form-control form-control-border" name="file_pernyataan" id="file_pernyataan">
                            
                            <div id="bukti1" style="display: none;">
                                <b>Pra Transkrip (dari BAKH) <div id="nama_file_pra_transkrip"></div></b>
                                <input type="file" class="form-control form-control-border" name="file_pra_transkrip" id="file_pra_transkrip">
                                
                            </div>
                            <div id="bukti2" style="display: none;">
                                <b>Surat Keterangan dari Kelurahan untuk yang terdampak <div id="nama_file_keterangan"></div></b>
                                <input type="file" class="form-control form-control-border" name="file_keterangan" id="file_keterangan">
                                <b>Slip Gaji/Surat Keterangan Penghasilan yang disahkan oleh Lurah/Kepala Desa <div id="nama_file_penghasilan"></div></b>
                                <input type="file" class="form-control form-control-border" name="file_penghasilan" id="file_penghasilan">
                                
                            </div>
                            <div id="bukti3" style="display: none;">
                                <b>Surat Keputusan Pengadilan yang bersifat tetap untuk yang mengalami pailit/Surat Keterangan dari Kelurahan tentang usaha yang mengalami kebangkrutan <div id="nama_file_bukti_pailit"></div></b>
                                <input type="file" class="form-control form-control-border" name="file_bukti_pailit" id="file_bukti_pailit">
                                
                            </div>
                            <div id="bukti4" style="display: none;">
                                <b>Surat Keterangan Kematian/Surat Keterangan PHK/SK Pensiun/Keterangan Dokter jika sakit permanen <div id="nama_file_kurang_penghasilan"></div></b>
                                <input type="file" class="form-control form-control-border" name="file_kurang_penghasilan" id="file_kurang_penghasilan">
                                
                            </div>                            
                            
                            
                        </div>
                        <div class="form-check mt-5 alert alert-warning">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                  <input type="checkbox" id="checkboxPrimary1"  name="cekSetuju" id="cekSetuju" checked>
                                  <label for="checkboxPrimary1">Klik Kotak Checklist Tanda Setuju
                                  </label>
                                  <br/>
                                    Data ini saya isi dengan sebenar-benarnya, bila ada kesalahan maka saya siap menanggung resiko mendapatkan sanksi dari Universitas Negeri Jakarta.
                                </div>
                            </div>
                        </div>   
                    </div>
            </div>
            <div class="modal-footer py-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Ajukan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              </div>
          </div>
        </form> 
        </div>
      </div>
    </section>
@endsection

@section ('script')
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
    $(function () {
      
        $("#dataTabel1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
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
    function uploadBukti(id){
        alert(id);

        document.getElementById("bukti1").style.display = "none";
        document.getElementById("bukti2").style.display = "none";
        document.getElementById("bukti3").style.display = "none";
        document.getElementById("bukti4").style.display = "none";
        document.getElementById("ques").style.display = "none";
        
        if (id === '1'){
            document.getElementById("bukti1").style.display = "block";
            document.getElementById("ques").style.display = "block";
        }else{
            document.getElementById("bukti1").style.display = "none";
            document.getElementById("bukti2").style.display = "block";
            if (id === '2'){
                document.getElementById("bukti3").style.display = "block";
            }else if (id === '7'){
                document.getElementById("bukti4").style.display = "block";
            }else{}
            //..
        }
    }

    function editPengajuan(id){
        //alert(id);
        //Ajax Load data from ajax
        $.ajax({
            url : "/dispensasi/edit/" +id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                console.log (data);
                var nom = data.nominal_ukt;
                $('[name="alamat"]').val(data.alamat);
                $('[name="jenis_dispensasi"]').val(data.jenis_dispensasi);
                $('[name="kelompok_ukt"]').val(data.kelompok_ukt);
                $('[name="nominal_ukt"]').val(nom.toFixed(0));
                $('[name="kelompok_ukt"]').val(data.kelompok_ukt);
                $('[name="pekerjaan"]').val(data.pekerjaan);
                $('[name="jabatan"]').val(data.jabatan_kerja);

                
                if (data.file_pernyataan != null){
                    $('#nama_file_pernyataan').html('<font class="text-success"><i class="fa fa-check"></i> Sudah Terisi</font> [Silakan pilih file untuk menggantikan file yang sudah ada]')
                }
                if (data.file_keterangan != null){
                    $('#nama_file_keterangan').html('<font class="text-success"><i class="fa fa-check"></i> Sudah Terisi</font> [Silakan pilih file untuk menggantikan file yang sudah ada]')
                }
                if (data.file_pailit != null){
                    $('#nama_file_bukti_pailit').html('<font class="text-success"><i class="fa fa-check"></i> Sudah Terisi</font> [Silakan pilih file untuk menggantikan file yang sudah ada]')
                }
                if (data.file_penghasilan != null){
                    $('#nama_file_penghasilan').html('<font class="text-success"><i class="fa fa-check"></i> Sudah Terisi</font> [Silakan pilih file untuk menggantikan file yang sudah ada]')
                }
                if (data.file_phk != null){
                    $('#nama_file_phk').html('<font class="text-success"><i class="fa fa-check"></i> Sudah Terisi</font> [Silakan pilih file untuk menggantikan file yang sudah ada]')
                }
                if (data.file_pratranskrip != null){
                    $('#nama_file_pra_transkrip').html('<font class="text-success"><i class="fa fa-check"></i> Sudah Terisi</font> [Silakan pilih file untuk menggantikan file yang sudah ada]')
                }
                
                
                $("#modal-Dispensasi").modal('show');
                $('.modal-title').text('Edit Pengajuan Dispensasi UKT'); // Set title to Bootstrap modal title
                
                document.getElementById("bukti1").style.display = "none";
                document.getElementById("bukti2").style.display = "none";
                document.getElementById("bukti3").style.display = "none";
                document.getElementById("bukti4").style.display = "none";
                document.getElementById("ques").style.display = "none";
                
                if (data.jenis_dispensasi == '1'){
                    document.getElementById("bukti1").style.display = "block";
                    document.getElementById("ques").style.display = "block";
                }else if (data.jenis_dispensasi == '2'){
                    document.getElementById("bukti2").style.display = "block";
                    document.getElementById("bukti3").style.display = "block";
                }else if (data.jenis_dispensasi == '7'){
                    document.getElementById("bukti2").style.display = "block";
                    document.getElementById("bukti4").style.display = "block";
                }else{
                    document.getElementById("bukti2").style.display = "block";
                }

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }
  </script>
@endsection
