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
                            <td class="text-center">
                                @if ($item->status_pengajuan == 1 || $item->status_pengajuan == 21)
                                    <button type="button" data-toggle="tooltip" data-placement="top" title="Verifikasi Data" class="btn btn-sm btn-outline-success" onclick="verifData({{ $item->id }})"></i> Edit Status </button>
                                @elseif ($item->status_pengajuan > 1)
                                    <button type="button" class="btn btn-sm btn-outline-success"></i> Lock </button>
                                @else
                                    <button type="button" data-toggle="tooltip" data-placement="top" title="Verifikasi Data" class="btn btn-sm btn-outline-info" onclick="verifData({{ $item->id }})"><i class="fas fa-edit"></i> Proses</button>
                                @endif
                            </td>
                            <td class="btn-group text-center">
                                @if ($item->status_pengajuan == 0)
                                    <form action="{{ route('verifikasi_dispensasi.delete', ['id' => $item->id]) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" data-toggle="tooltip" data-placement="top" title="Hapus Data" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin akan menghapus data ini ?')"><i class="fas fa-trash"></i> Hapus</button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-outline-warning disabled"><i class="ace-icon fa fa-trash"></i> Hapus</button>
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
                                <form action="{{ route('verifikasi_dispensasi.simpan') }}" class="row g-3" method="POST">
                                    @csrf
                                    <div class="col-auto">
                                        <input type="hidden" name="id" id="id">
                                        <input type="hidden" name="nim" id="nim">
                                        <input type="hidden" name="semester" id="semester">
                                        <select class="form-control col" id="sellayak" name="sellayak">
                                            <option value="0">Pilih Kelayakan Berkas</option>
                                            <option value="1">Layak</option>
                                            <option value="2">Tidak Layak</option>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                      <textarea class="form-control col" rows="3" cols="50" id="txtAlasan" name="txtAlasan" placeholder="Alasan Bila Tidak Layak"></textarea>
                                  </div>
                                    <div class="col-auto text-right">
                                        <button type="submit" class="btn btn-primary col" data-toggle="tooltip" data-placement="top" title="Verifikasi Data" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin dengan status terpilih ?')"><i class="fas fa-arrow"></i> Proses</button>
                                    </div>
                                </form>
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
      
      <div id="modal-verifikasi-pengajuan" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-body">
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
                $('[name="semester"]').val(data.semester);
                
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
