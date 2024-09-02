{{-- <div class=""> --}}
  @if ($errors->any())
    <div class="alert alert-danger mt-2">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif  

  <button class="btn btn-outline-primary" id="btnCetakPenerima" data-toggle="modal" data-target="#modal-Cetak"><i class="ace-icon fa fa-plus"></i> Cetak Pengajuan </button>
    {{-- @dd($mode); --}}
    @if($semester <> '' && $mode == "Program Studi")
        <button class="btn btn-outline-success {{ $tombol }}" id="btnTambahPengajuan" data-toggle="modal" data-target="#modal-Pengajuan" {{ $tombol }}><i class="ace-icon fa fa-edit"></i> Tambah Pengajuan </button>
    @endif
        
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
            @if($semester <> '' && $mode == "Program Studi")
            <th scope="col">Aksi</th>
            @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($pengajuan as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->semester }}</td>
                <td>{{ $item->nim }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->jenjang_prodi }} {{ $item->nama_prodi }}</td>
                <td>{{ $item->jenis }}</td>
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
                    <a href="{{ asset('storage/' . $item->file_pratranskrip) }}" target="_blank">[Pra Transkrip]</a>
                @endif
                </td>
                <td><div class="alert alert-success">{{ $item->status ?? '' }}</div> </td>
                @if($semester <> '' && $mode == "Program Studi")
                <td>
                  <div class="btn-group">
                    <button type="button" id="btnEdit" class="btn btn-outline-warning" onclick="edit({{ $item->id }})"><i class="fas fa-edit"></i> Edit</button>

                    <form action="{{ route('penerima_dispensasi.delete', ['id' => $item->id]) }}" method="POST">
                      {{ csrf_field() }}
                      {{ method_field('delete') }}
                      <button type="submit" data-toggle="tooltip" data-placement="top" title="Hapus Pengajuan" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin akan menghapus pengajuan mahasiswa ini ?')"><i class="fas fa-trash"></i> Hapus </button>
                    </form>

                </td>
                @endif
            </tr>
            @endforeach

        </tbody>
        </table>
    </div>

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
              <form id="cetak-penerima-dispensasi" action="{{ route('penerima_dispensasi.print', ['semester' => $semester, 'kode_prodi' => $unit]) }}" method="get" target="_blank">
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

    <div id="modal-Pengajuan" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
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

            <form action="{{ route('penerima_dispensasi.simpan') }}" method="POST" id="formPengajuan" enctype="multipart/form-data">
              @csrf
              <div class="form-body">
                <div class="alert alert-info text-center">
                  <ul class="list-unstyled text-left">Catatan :
                    <li>Pengisian Form ini harus sesuai dengan data manual yang telah diisikan oleh mahasiswa.</li>
                    <li>Permohonan keringanan UKT hanya berlaku sekali pengajuan.</li>
                  </ul>
                </div>
                  
                <div class="form-group">
                  <label for="nim">Mahasiswa</label>
                  <input type="hidden" name="semester" id="semester" value="{{ $semester }}">
                  <input type="hidden" name="id" id="id">
                  <select name="nim" id="nim" class="form-control select2" onchange="showIdentitas(this.value)">
                    <option value="0">Pilih Mahasiswa</option>
                    @for ($mhs=0;$mhs<=$countMhs-1;$mhs++)
                      <option value="{{ $arrMhs[$mhs]['nim'] }}">{{ $arrMhs[$mhs]['nim'] .' '.$arrMhs[$mhs]['namaLengkap'] }}</option>
                    @endfor
                  </select>
                </div>
                <div id="identitas" class="row form-group" style="display: none;">
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
                <div class="form-group">
                  <label for="pekerjaan">Pekerjaan orang tua/pihak lain yang membiayai</label>
                  <input type="text" class="form-control form-control-border" name="pekerjaan" id="pekerjaan" placeholder="Pekerjaan orang tua/pihak lain yang membiayai">
                </div>
                <div class="form-group">
                  <label for="jabatan">Jabatan Pekerjaan yang membiayai</label>
                  <input type="text" class="form-control form-control-border" name="jabatan" id="jabatan" placeholder="Jabatan Pekerjaan yang membiayai">
                </div>
                <div class="form-group">
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

                <div id="ques" class="row form-group" style="display: none;">
                  <div class="col">
                    <label for="sks">SKS Belum Lulus (Harus lebih kecil sama dengan 6 sks)</label>
                    <input type="number" name="sks_belum" class="form-control form-control-border" id="sks_belum" placeholder="SKS yang belum belum lulus">
                  </div>
                </div>

                <div class="form-group">
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
          </div>
          <div class="modal-footer py-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Ajukan</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          </div>
        </div>
        </form>
      </div>
    </div>
{{-- </div> --}}
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
$(function(){
  $('#table').dataTable({
    // "paging": true,
    "lengthChange": true,
    // "searching": true,
    "ordering": true,
    // "info": true,
    // "autoWidth": false,
    "responsive": true,
    // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
  });
  $('#table1').dataTable();
})
function edit(id){
  
  // document.getElementById("identitas").style.display = "block";
  $.ajax({
    url: "/penerima_dispensasi/dataEdit/" + id,
    type: "GET",
    dataType: "JSON",
    success: function(data) {
      // console.log(data);
      // alert(data[0].nim);
      $('[name="nim"]').val(data[0].nim).change();
      $('[name="nim"]').trigger('chosen:updated');
      $('[name="id"]').val(data[0].id);
      $('[name="pekerjaan"]').val(data[0].pekerjaan);
      $('[name="jabatan"]').val(data[0].jabatan_kerja);
      $('[name="kode_program_studi"]').val(data[0].kodeProdi);
      $('[name="sks_belum"]').val(data[0].sks_belum);
      $('[name="jenis_dispensasi"]').val(data[0].jenis_dispensasi).change();

      $('#nama_file_permohonan').html(data[0].file_permohonan);
      $('#nama_file_pernyataan').html(data[0].file_pernyataan);
      $('#nama_file_penghasilan').html(data[0].file_penghasilan);
      $('#nama_file_bukti_pailit').html(data[0].file_failit);
      $('#nama_file_pra_transkrip').html(data[0].file_pratranskrip);
      $('#nama_file_kurang_penghasilan').html(data[0].file_phk);

    },
    error: function(jqXHR, textStatus, errorThrown) {
      alert('Error get data from ajax');
    }
  });
  $('#id').val(id);
  $("#modal-Pengajuan").modal("show");
}
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

      if (id === '1') {
        document.getElementById("bukti1").style.display = "block";
        document.getElementById("ques").style.display = "block";
      } else {
        document.getElementById("bukti1").style.display = "none";
        document.getElementById("bukti2").style.display = "block";
        if (id === '2') {
          document.getElementById("bukti3").style.display = "block";
        } else if (id === '7') {
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