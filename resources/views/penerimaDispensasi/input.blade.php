@extends('layouts.main')

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('contain')
<section class="content-header">
  <form action="{{ route('penerima_dispensasi.simpan_wr2') }}" method="POST" id="formPengajuan" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">
          <h3 class="card-title">Input Data Keringanan UKT</h3>

          <!-- <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button>
          </div> -->
          <br/>
          <br/>
          <div class="alert alert-info text-center">
            <ul class="list-unstyled text-left">Catatan :
                <li>Pengisian Form ini harus sesuai dengan data manual yang telah diisikan oleh mahasiswa.</li>
                <li>Permohonan keringanan UKT hanya berlaku sekali pengajuan.</li>
                <li>Perhatikan Nominal UKT mahasiswa</li>
                <li>Kelompok UKT mahasiswa harus sesuai dengan nominal yang muncul</li>
            </ul>
          </div> 
        </div>
          
        
        <div class="col">
              <label for="prodi">Semester</label>
              <input type="text" class="form-control-plaintext" name="semester" readonly id="semester" value="{{ $semester }}">
              <input type="hidden" name="id" id="id">
        </div>
        <div class="col">
            <label for="prodi">Program Studi</label>
              <select name="prodi" id="prodi" class="form-control form-control-border select2" onchange="showMahasiswa(this.value)">
                <option value="0">Pilih Program Studi</option>
                <!-- <option value="1">1</option> -->
                @for ($pd=0;$pd<=$countProdi-1;$pd++)
                    <option value="{{ $arrProdi[$pd]['kodeProdi'] }}">{{ $arrProdi[$pd]['jenjangProdi'] .' '.$arrProdi[$pd]['namaProdi'] }}</option>
                @endfor
              </select>
        </div>
        <div class="col">
          <label for="nim">Mahasiswa</label>
          <select name="nim" id="nim" class="form-control form-control-border select2" onchange="showIdentitas(this.value)">
            <option value="0">Pilih Mahasiswa</option>
          </select>
        </div>
        <div id="identitas" class="form-group" style="display: none;">
          <div class="col">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control form-control-border" id="nama_lengkap">
          </div>
          <div class="col">
            <label for="program_studi">Nama Program Studi</label>
            <input type="hidden" name="kode_program_studi" class="form-control form-control-border" id="kode_program_studi">
            <input type="text" name="program_studi" class="form-control form-control-border" id="program_studi">
          </div>
          <div class="col">
            <label for="jenjang">Jenjang</label>
            <input type="text" name="jenjang" class="form-control form-control-border" id="jenjang">
          </div>
          <div class="col">
            <label for="semester_ke">Semester</label>
            <input type="text" name="semester_ke" class="form-control form-control-border" id="semester_ke">
          </div>
          <div class="col">
            <label for="alamat">Alamat</label>
            <input type="text" name="alamat" class="form-control form-control-border" id="alamat">
          </div>
          <div class="col">
            <label for="nomor_hp"> Nomor HP</label>
            <input type="text" name="nomor_hp" class="form-control form-control-border" id="nomor_hp">
          </div>
          <div class="col">
            <label for="email"> Email</label>
            <input type="text" name="email" class="form-control form-control-border" id="email">
          </div>
          <div class="col">
            <label for="kelompok_ukt">Kelompok UKT</label>
            <!--<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">-->
            <select name="kelompok_ukt" id="kelompok_ukt" class="form-control form-control-border required autocomplete="off">
              <option value="">Pilih Kelompok UKT</option>
              @foreach ($kelompok_ukt as $kel)
                <option value="{{ $kel->id }}">{{ $kel->kelompok }}</option>
              @endforeach
            </select>
          </div>
          <div class="col">
            <label for="nominal_ukt"> Nominal UKT</label>
            <input type="text" name="nominal_ukt" class="form-control form-control-border" id="nominal_ukt" pattern="^\Rp\d{1,3}(,\d{3})*(\.\d+)?Rp" value="" data-type="currency" placeholder="Nominal UKT anda saat ini">
          </div>
        </div>
        <div class="col">
          <label for="pekerjaan">Pekerjaan orang tua/pihak lain yang membiayai</label>
          <input type="text" class="form-control form-control-border" name="pekerjaan" id="pekerjaan" placeholder="Pekerjaan orang tua/pihak lain yang membiayai">
        </div>
        <div class="col">
          <label for="jabatan">Jabatan Pekerjaan yang membiayai</label>
          <input type="text" class="form-control form-control-border" name="jabatan" id="jabatan" placeholder="Jabatan Pekerjaan yang membiayai">
        </div>
        <div class="col">
          <label for="jenis_dispensasi">Jenis Keringanan UKT</label>
          <!--<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">-->
          <select name="jenis_dispensasi" id="jenis_dispensasi" class="form-control form-control-border required autocomplete="off" onchange="uploadBukti(this.value)">
            <option value="">Pilih Jenis Keringanan UKT</option>
            @foreach ($list_dispensasi as $item)
              <option value="{{ $item->id }}">{{ $item->jenis_dispensasi }}</option>
            @endforeach
          </select>
          <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
        </div>
        <div id="ques" class="form-group" style="display: none;">
          <div class="col">
            <label for="sks">SKS Belum Lulus (Harus lebih kecil sama dengan 6 sks dan mhs semester 9)</label>
            <input type="number" name="sks_belum" class="form-control form-control-border" id="sks_belum" placeholder="SKS yang belum belum lulus">
          </div>
        </div>
        <div id="ques7" class="form-group" style="display: none;">
          <div class="col">
            <label for="kel_ukt_baru">Kelompok UKT Baru</label>
            <select name="kel_ukt_baru" id="kel_ukt_baru" class="form-control form-control-border required autocomplete="off">
              <option value="">Pilih Kelompok UKT Baru</option>
              @foreach ($kelompok_ukt as $kel)
                <option value="{{ $kel->id }}">{{ $kel->kelompok }}</option>
              @endforeach
            </select>
          </div>
          <div class="col">
            <label for="nominal_ukt_baru">Nominal UKT Baru</label>
            <input type="text" name="nominal_ukt_baru" class="form-control form-control-border" id="nominal_ukt_baru" pattern="^\Rp\d{1,3}(,\d{3})*(\.\d+)?Rp" value="" data-type="currency" placeholder="Nominal UKT Baru">
          </div>
        </div>
        <div class="col">
          <label for="jabatan">File Pendukung Pengajuan Keringanan UKT</label>
          <br />
          <b>Surat Permohonan <div id="nama_file_permohonan"></div> </b>
          <input type="file" class="form-control form-control-border" name="file_permohonan" id="file_permohonan">
          <b>Surat Pernyataan <div id="nama_file_pernyataan"></div> </b>
          <input type="file" class="form-control form-control-border" name="file_pernyataan" id="file_pernyataan">

          <div id="bukti1" style="display: none;">
            <b>Pra Transkrip (dari BAKH) <div id="nama_file_pra_transkrip"></div></b>
            <input type="file" class="form-control form-control-border" name="file_pra_transkrip" id="file_pra_transkrip">

          </div>
          <div id="bukti2" style="display: none;">
            {{-- <b>Surat Keterangan dari Kelurahan untuk yang terdampak <div id="nama_file_keterangan"></div></b>
            <input type="file" class="form-control form-control-border" name="file_keterangan" id="file_keterangan"> --}}
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
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Ajukan</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    </div>
  </form>
</section>
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
$(function() {
  $('.select2').select2({
    // dropdownParent: $('#modal-Pengajuan')
  });
})
function showIdentitas(nim){
      document.getElementById("identitas").style.display = "block";
      $.ajax({
          url: "/penerima_dispensasi/data/" + nim,
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            console.log(data);
            $('[name="nama_lengkap"]').val(data[0].namaLengkap);
            $('[name="program_studi"]').val(data[0].namaProdi);
            $('[name="kode_program_studi"]').val(data[0].kodeProdi);
            $('[name="jenjang"]').val(data[0].jenjangProdi);
            $('[name="alamat"]').val(data[0].alamat);
            $('[name="nomor_hp"]').val(data[0].hpm);
            $('[name="email"]').val(data[0].email);
            $('[name="nominal_ukt"]').val(rupiah(data[0].biayaKuliah));
            var akhir = {{ ($semester == "")?0:$semester }};
            var awal = data[0].smsMasuk;
            var selisih = 0;
            if (parseInt(akhir) > 0){
              selisih = (parseInt(akhir) - parseInt(awal)) + 1; 
            }
            $('[name="semester_ke"]').val(selisih);
            $('[name="kelompok_ukt"]').val(data[0].kelompok_ke);
            
          },
          error: function(jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
          }
        });
    }
function showMahasiswa(prodi){
      // document.getElementById("identitas").style.display = "block";
      $.ajax({
          url: "/penerima_dispensasi/getMahasiswa/" + prodi,
          type: "GET",
          dataType: "JSON",
          success: function(data) {
            $('#nim').html("<option value=0>Pilih Mahasiswa</option>  ");
            $.each(data, function(key, value){
                $("#nim").append('<option  value='+value.nim+'>'+value.nim+' '+value.nama+'</option>');
            });
          },
          error: function(jqXHR, textStatus, errorThrown) {
            alert('Tidak ada data');
          }
        });
    }

    const rupiah = (number)=>{
      return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR"
      }).format(number);
    }

    function uploadBukti(id) {
      // alert(id);
      document.getElementById("bukti1").style.display = "none";
      document.getElementById("bukti2").style.display = "none";
      document.getElementById("bukti3").style.display = "none";
      document.getElementById("bukti4").style.display = "none";
      document.getElementById("ques").style.display = "none";
      document.getElementById("ques7").style.display = "none";

      if (id === '1') {
        document.getElementById("bukti1").style.display = "block";
        document.getElementById("ques").style.display = "block";
      } else {
        document.getElementById("bukti1").style.display = "none";
        document.getElementById("bukti2").style.display = "block";
        if (id === '2') {
          document.getElementById("bukti3").style.display = "block";
        } else if (id === '7') {
          document.getElementById("ques7").style.display = "block";
          document.getElementById("bukti4").style.display = "block";
        } else {}
        //..
      }
    }

    function hapus(id)
    {
      alert("Hapus");
    }
</script>
@endsection