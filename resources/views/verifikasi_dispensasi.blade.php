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
          <h3 class="card-title">Verifikasi Dispensasi UKT Semester {{ $semester }}</h3>

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
            <div class="mt-4 table-responsive">
                <table id="dataTabel" class="table table-hover">
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
                        <th scope="col">Proses Dispensasi ke WD2/Dekan</th>
                        <th scope="col">Nominal Ditagihkan</th>
                        <th scope="col">Potongan</th>
                        <th scope="col">Angsuran</th>
                        <th scope="col">Hapus Data</th>
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
                            <td>{{ number_format($item->nominal_ukt,0) }}</td>
                            <td>
                                @if ($item->file_pernyataan)
                                <a href="{{ asset('storage/' . $item->file_pernyataan) }}" target="_blank" title="Surat Pernyataan Kebenaran Dokumen">Surat Pernyataan</a><br/>
                                @endif
                                @if ($item->file_keterangan)
                                <a href="{{ asset('storage/' . $item->file_keterangan) }}" target="_blank" title="Surat Keterangan dari kelurahan untuk yang terdampak">Surat Keterangan</a><br/>
                                @endif
                                @if ($item->file_penghasilan)
                                <a href="{{ asset('storage/' . $item->file_penghasilan) }}" target="_blank" title="Slip Gaji/Surat Keterangan Penghasilan yang disahkan oleh Lurah/Kepala Desa">Slip Gaji</a><br/>
                                @endif
                                @if ($item->file_pailit)
                                <a href="{{ asset('storage/' . $item->file_pailit) }}" target="_blank" title="Keputusan Pengadilan yang bersifat tetap untuk yang mengalami pailit/Surat Keterangan dari Kelurahan tentang usaha yang mengalami kebangkrutan">Surat Keterangan Pailit</a><br/>
                                @endif
                                @if ($item->file_phk)
                                <a href="{{ asset('storage/' . $item->file_phk) }}" target="_blank" title="Surat Keterangan Kematian/Surat Keterangan PHK/SK Pensiun/Keterangan Dokter jika sakit permanen">Surat PHK/Kematian</a><br/>
                                @endif
                                @if ($item->file_pratranskrip)
                                <a href="{{ asset('storage/' . $item->file_pratranskrip) }}" target="_blank">[Pra Transkrip]</a>
                                @endif
                            </td>
                            <td>{{ $item->status ?? '' }}</td>
                            <td>Rp. {{ number_format($item->ditagihkan,0) }}</td>
                            <td>Rp. {{ number_format($item->potongan,0) }}</td>
                            <td>
                              Angsuran 1 : Rp. {{ number_format( $item->angsuran1,0) }} <br>
                              Angsuran 2 : Rp. {{ number_format( $item->angsuran2,0) }}
                            </td>
                            <td class="text-center">
                                @if ($item->status_pengajuan == 1 || $item->status_pengajuan == 21)
                                    <button type="button" data-toggle="tooltip" data-placement="top" title="Verifikasi Data" class="btn btn-outline-success" onclick="verifData({{ $item->id }})"><i class="fas fa-edit"></i> Edit Status </button>
                                @elseif ($item->status_pengajuan > 1)
                                    <button type="button" class="btn btn-outline-success"></i> Lock </button>
                                @else
                                    <button type="button" data-toggle="tooltip" data-placement="top" title="Verifikasi Data" class="btn btn-outline-info" onclick="verifData({{ $item->id }})"><i class="fas fa-edit"></i> Proses</button>
                                @endif
                            </td>
                            <td class="btn-group text-center">
                                @if ($item->status_pengajuan == 0)
                                    <form action="{{ route('verifikasi_dispensasi.delete', ['id' => $item->id]) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" data-toggle="tooltip" data-placement="top" title="Hapus Data" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin akan menghapus data ini ?')"><i class="fas fa-trash"></i> Hapus</button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-outline-warning disabled"><i class="fas fa-trash"></i> Hapus</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
        </div>
      </div>

      <div id="modal-verifikasi-pengajuan" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title">Form Verifikasi Data Dispensasi UKT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body py-2">  
                <div class="form-body">
                    <div class="alert alert-info">Verifikasi data pengajuan dengan data SIAKAD</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Verifkasi Data Pengajuan Dispensasi UKT Semester {{ $semester }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                      <table class="table table-sm table-responsive">
                        <thead>
                          <tr>
                            <th style="width: 10px">#</th>
                            <th>Keterangan</th>
                            <th>Pengajuan</th>
                            <th style="width: 40px">Data Siakad</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>1.</td>
                            <td>No. Induk Mahaasiswa</td>
                            <td><div class="text-left" id="nim">1234567890</div></td>
                            <td><span class="badge bg-success" id="nim_siakad"><i class="fas fa-check"></i> OK</span></td>
                          </tr>
                          <tr>
                            <td>2.</td>
                            <td>Nama Mahasiswa</td>
                            <td><div class="text-left" id="nama">Nama</div></td>
                            <td><span class="badge bg-success" id="nama_siakad"><i class="fas fa-check"></i> OK</span></span></td>
                          </tr>
                          <tr>
                            <td>3.</td>
                            <td>Program Studi</td>
                            <td><div class="text-left" id="prodi"></div></div></td>
                            <td><span class="badge bg-success" id="prodi_siakad"><i class="fas fa-check"></i> OK</span></span></td>
                          </tr>
                          <tr>
                            <td>4.</td>
                            <td>Kontak dan Email</td>
                            <td><div class="text-left" id="kontak"></div></td>
                            <td><span class="badge bg-success" id="kontak_siakad"><i class="fas fa-check"></i> OK</span></span></td>
                          </tr>
                          <tr>
                            <td>5.</td>
                            <td>Alamat</td>
                            <td><div class="text-left" id="alamat"></div></td>
                            <td><span class="badge bg-success text-left" id="alamat_siakad"><i class="fas fa-check"></i> OK</span></span></td>
                          </tr>
                          <tr>
                            <td>6.</td>
                            <td>Kelompok UKT / Biaya UKT</td>
                            <td><div class="text-left" id="ukt"></div></td>
                            <td><span class="badge bg-success" id="nom_ukt_siakad"><i class="fas fa-check"></i> OK</span></span></td>
                          </tr>
                          <tr>
                            <td>7.</td>
                            <td>Pekerjaan Pihak yang membiayai</td>
                            <td><div class="text-left" id="pekerjaan"></div></td>
                            <td><span class="badge bg-success">-</span></td>
                          </tr>
                          <tr>
                            <td>8.</td>
                            <td>Jabatan Pihak yang membiayai</td>
                            <td><div class="text-left" id="jabatan_kerja"></div></td>
                            <td><span class="badge bg-success">-</span></td>
                          </tr>
                          <tr>
                            <td>9.</td>
                            <td>Jenis Dispensasi</td>
                            <td><div class="text-left" id="jenis_dispensasi"></div></td>
                            <td><span class="badge bg-success">-</span></td>
                          </tr>
                          <tr>
                            <td>10.</td>
                            <td>File Pendukung yang dibutuhkan</td>
                            <td colspan="2"><div class="text-left" id="file_pendukung"></div></td>
                          </tr>
                          <tr>
                            <td colspan="2">Kelayakan Berkas Dokumen</td>
                            <td colspan="2">
                              <div class="row">
                                <div class="col-sm-12">
                                <form action="{{ route('verifikasi_dispensasi.simpan') }}" class="form-horizontal" method="POST">
                                  @csrf
                                  <div class="form-group">
                                    <label class="col-form-label" for="pengalihan">Pengalihan Jenis Dispensasi</label>
                                    <div class="custom-control custom-radio">
                                      <input class="custom-control-input" type="radio" id="hideMe" name="pengalihan" value="0">
                                      <label for="hideMe" class="custom-control-label">Tidak Ada Pengalihan</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                      <input class="custom-control-input" type="radio" id="showMe" name="pengalihan" value="1">
                                      <label for="showMe" class="custom-control-label">Pengalihan Dispensasi</label>
                                    </div>
                                  </div>
                                  <div id="ifYes" style="display: none;">
                                    <div class="form-group" id="alihan" style="display: none;">
                                      <label class="col-form-label" for="jenis_dispensasi_peralihan"><i class="fas fa-check"></i>Jenis Dispensasi Pengalihan</label>
                                      <select class="form-control" id="jenis_dispensasi_peralihan" name="jenis_dispensasi_peralihan" onchange="resetLayak(this.value)">
                                          <option value="0">Pilih Dispensasi Pengalihan</option>
                                          @foreach ($list_dispensasi as $item)
                                             <option value="{{ $item->id }}">{{ $item->jenis_dispensasi }}</option>
                                          @endforeach
                                      </select>
                                    </div>
                                    <div class="form-group" id="kelUKT" style="display: none;">
                                      <label for="kelompok_ukt">Kelompok UKT</label>
                                      <select name="kelompok_ukt" id="kelompok_ukt" class="form-control form-control-border required autocomplete="off">
                                        <option value="">Pilih Kelompok UKT</option>
                                        @foreach ($kelompok_ukt as $kel)
                                          <option value="{{ $kel->id }}">{{ $kel->kelompok }}</option>
                                        @endforeach
                                      </select>
                                      <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label" for="pengalihan">Kelayakan Dispensasi</label>
                                        <input type="hidden" name="id" id="id">
                                        <input type="hidden" name="nim" id="nim">
                                        <input type="hidden" name="kelompok" id="kelompok">
                                        <input type="hidden" name="semester" id="semester">
                                        <input type="hidden" name="jenis_dispensasi_awal" id="jenis_dispensasi_awal">
                                        <input type="hidden" name="biayaKuliah" id="biayaKuliah">
                                        <input type="hidden" name="prodi_mhs" id="prodi_mhs">
                                        <input type="hidden" name="angkatan" id="angkatan">
                                        <select class="form-control" id="sellayak" name="sellayak" onchange="sumNominal();">
                                            <option value="0">Pilih Kelayakan Berkas</option>
                                            <option value="1">Layak</option>
                                            <option value="2">Tidak Layak</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-form-label" for="nominal">Nominal Pembayaran</label>
                                      <input type="text" name="nominal" id="nominal" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-form-label" for="potongan">Nominal Potongan</label>
                                      <input type="text" name="potongan" id="potongan" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-form-label" for="angsuran1">Nominal Angsuran 1</label>
                                      <input type="text" name="angsuran1" id="angsuran1" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-form-label" for="angsuran2">Nominal Angsuran 2</label>
                                      <input type="text" name="angsuran2" id="angsuran2" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-form-label" for="txtAlasan">Keterangan</label>
                                      <textarea class="form-control" rows="3" cols="50" id="txtAlasan" name="txtAlasan" placeholder="Alasan Bila Tidak Layak"></textarea>
                                    </div>
                                    <div class="form-group text-right">
                                      <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Verifikasi Data" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin dengan status terpilih ?')"><i class="fas fa-arrow"></i> Proses</button>
                                    </div>
                                  </div>
                                </form>
                                </div>
                              </div>
                                
                            </td>
                          </tr>
                          
                        </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
          </div>
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

    $('#showMe').click(function() {
        reset();
        $('#ifYes').slideDown();
        $('#alihan').slideDown();
        $('#kelUKT').slideUp();
    });
    $('#hideMe').click(function() {
        reset();
        $('#ifYes').slideDown();
        $('#alihan').slideUp();

        var jenisDispensasiUKTAwal = $('#jenis_dispensasi_awal').val();
        if (jenisDispensasiUKTAwal == '7'){
          $('#kelUKT').slideDown();
        }else{
          $('#kelUKT').slideUp();
        }
    });

    function reset(){
        var mySelect = document.getElementById('sellayak');
        mySelect.selectedIndex = 0;
        var alihan = document.getElementById('jenis_dispensasi_peralihan');
        alihan.selectedIndex = 0;
        $('#nominal').val('0');
    }
    function resetLayak(id){
        var layak = document.getElementById('sellayak');
        layak.selectedIndex = 0;
        var kel_ukt = document.getElementById('kelompok_ukt');
        kel_ukt.selectedIndex = 0;
        $('#nominal').val('0');
        
        if (id == '7'){
          $('#kelUKT').slideDown();
        }else{
          $('#kelUKT').slideUp();
        }

        $('#potongan').val('0');
        $('#nominal').val('0') ;
        $('#angsuran1').val('0');
        $('#angsuran2').val('0') ;
    }

    function sumNominal(){
      
      let biayaKuliah = $('#biayaKuliah').val();
      if ($('#sellayak').val() == '2'){
          $('#potongan').val('0');
          $('#nominal').val(biayaKuliah) ;
          $('#angsuran1').val('0');
          $('#angsuran2').val('0') ;
          exit;
      }
      
      let potongan = 0;
      let nominal = 0;
      let angsuran1 = 0;
      let angsuran2 = 0;
      
      var alihkan = $("input[type='radio'][name='pengalihan']:checked").val();
      var kelompok = $('#kelompok').val();
      var kelompokbe4 = kelompok - 1; 
      var prodi = $('#prodi_mhs').val();
      var angkatan = $('#angkatan').val();
      var DataUKT = dataUKT(prodi,angkatan);
      
      var jenisDispensasiUKTAlihan = $('#jenis_dispensasi_peralihan').val();
      var jenisDispensasiUKTAwal = $('#jenis_dispensasi_awal').val();
      
      if (DataUKT.status == false){
          alert(DataUKT.pesan);
          $("#modal-verifikasi-pengajuan").modal('hide');
          exit();
      }

      const ukt = [];
      ukt[0] = 0;
      ukt[1] = DataUKT.ukt_1;
      ukt[2] = DataUKT.ukt_2;
      ukt[3] = DataUKT.ukt_3;
      ukt[4] = DataUKT.ukt_4;
      ukt[5] = DataUKT.ukt_5;
      ukt[6] = DataUKT.ukt_6;
      ukt[7] = DataUKT.ukt_7;
      ukt[9] = DataUKT.ukt_8;
      ukt[10] = DataUKT.ukt_beasiswa;
      ukt[11] = DataUKT.ukt_kerjasama;

      var nom_ukt = parseFloat(biayaKuliah);     
      var nom_uktbe4 = 0;
      let selisih = 0;
      
      if (alihkan == '1'){
        if (jenisDispensasiUKTAlihan == '1'){
            potongan = nom_ukt * 0.5;
            nominal = biayaKuliah - potongan;
        }else if (jenisDispensasiUKTAlihan == '2'){
            nom_uktbe4 = parseFloat(ukt[kelompokbe4]);
            selisih = nom_ukt - nom_uktbe4;
            potongan = selisih * 0.8;
            nominal = biayaKuliah - potongan;
        }else if (jenisDispensasiUKTAlihan == '3' || jenisDispensasiUKTAlihan == '6'){
            potongan = biayaKuliah;
        }else if (jenisDispensasiUKTAlihan == '4'){
            angsuran1 = biayaKuliah * 0.5;
            angsuran2 = biayaKuliah * 0.5;
        }else if (jenisDispensasiUKTAlihan == '5'){
            potongan = potongan;
            nominal = biayaKuliah;
        }else if (jenisDispensasiUKTAlihan == '7'){
            var keluktbaru = $('#kelompok_ukt').val();
            var nom_ukt_baru = ukt[keluktbaru];
            nominal = nom_ukt_baru;
        }else{
            potongan = potongan;
            nominal = biayaKuliah;
        }

      }else{
        if (jenisDispensasiUKTAwal == '1'){
            potongan = nom_ukt * 0.5;
            nominal = biayaKuliah - potongan;
        }else if (jenisDispensasiUKTAwal == '2'){
            nom_uktbe4 = parseFloat(ukt[kelompokbe4]);
            selisih = nom_ukt - nom_uktbe4;
            potongan = selisih * 0.8;
            nominal = biayaKuliah - potongan;
        }else if (jenisDispensasiUKTAwal == '3' || jenisDispensasiUKTAwal == '6'){
            potongan = biayaKuliah;
        }else if (jenisDispensasiUKTAwal == '4'){
            angsuran1 = biayaKuliah * 0.5;
            angsuran2 = biayaKuliah * 0.5;
        }else if (jenisDispensasiUKTAwal == '5'){
            potongan = potongan;
            nominal = biayaKuliah;
        }else if (jenisDispensasiUKTAwal == '7'){
            var keluktbaru = $('#kelompok_ukt').val();
            var nom_ukt_baru = ukt[keluktbaru];
            nominal = nom_ukt_baru;
        }else{
            potongan = potongan;
            nominal = biayaKuliah;
        }
      }

      $('#potongan').val(potongan);
      $('#nominal').val(nominal) ;
      $('#angsuran1').val(angsuran1);
      $('#angsuran2').val(angsuran2) ;
      
    }
    
    function dataUKT(prodi,angkatan){
        var radiusServer = "/verifikasi_dispensasi/dataukt/" +prodi+"/"+angkatan;
        var asdf = null;
        //Ajax Load data from ajax
        $.ajax({
            url : radiusServer,
            type: "GET",
            dataType: "JSON",
            async: false,
            success: function(res){
              asdf = res;
              console.log(asdf);
              // alert(asdf.pesan);
            },
            error: function(res){
              console.log(res);
            }
        });
        return asdf;
    }

    function uploadBukti(id){
        
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

    function verifData(id){
        //alert(id);
        // $("#modal-VerifikasiData").modal('show');
        
        //Ajax Load data from ajax
        $.ajax({
            url : "/verifikasi_dispensasi/detil/" +id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                console.log (data);
                var nom = data.nominal_ukt;
                $('[name="id"]').val(data.id);
                $('[name="nim"]').val(data.nim);
                $('[name="kelompok"]').val(data.kelompok_ukt);
                $('[name="semester"]').val(data.semester);
                $('[name="jenis_dispensasi_awal"]').val (data.jenis_dispensasi);
                $('[name="biayaKuliah"]').val(data.nominal_ukt);
                $('[name="prodi_mhs"]').val(data.kode_prodi);
                $('[name="angkatan"]').val(data.angkatan_siakad);
                
                
                $('#nim').html (data.nim);
                $('#nim_siakad').html (data.nim_siakad);
                $('#nama').html (data.nama);
                $('#nama_siakad').html (data.nama_siakad);
                $('#prodi').html (data.jenjang_prodi + ' ' + data.nama_prodi);
                $('#prodi_siakad').html (data.prodi_siakad);
                $('#kontak').html (data.no_hp + '/' + data.email);
                $('#kontak_siakad').html (data.kontak_siakad);
                $('#alamat').html (data.alamat);
                $('#alamat_siakad').html (data.alamat_siakad);
                $('#jenis_dispensasi').html(data.jenis);
                $('#ukt').html(data.kelompok + ' / Rp. ' + data.nom_ukt);
                $('#nom_ukt_siakad').html(data.nom_ukt_siakad);
                $('#pekerjaan').html(data.pekerjaan);
                $('#jabatan_kerja').html(data.jabatan_kerja);
                $('#file_pendukung').html(data.file_pendukung);

                if (data.status_pengajuan == '1'){
                  document.getElementById('sellayak').value = '1';
                }else if (data.status_pengajuan == '21'){
                  document.getElementById('sellayak').value = '2';
                }else{
                  document.getElementById('sellayak').value = '0';
                }
                document.getElementById('txtAlasan').value = data.alasan_verif;

                $("#modal-verifikasi-pengajuan").modal('show');
                $('.modal-title').text('Verifikasi Berkas Pengajuan Dispensasi UKT'); // Set title to Bootstrap modal title
                

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }
  </script>
@endsection
