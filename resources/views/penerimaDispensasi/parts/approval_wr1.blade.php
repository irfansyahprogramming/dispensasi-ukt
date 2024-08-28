{{-- <button class="btn btn-outline-primary" id="btnCetakPenerima" data-toggle="modal" data-target="#modal-Cetak"><i class="ace-icon fa fa-plus"></i> Cetak Pengajuan </button> --}}
<div><h3>Proses Pembuatan SK dari Hutalak</h3></div>
<div class="mt-4table-responsive">
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
        </tr>
    </thead>
    <tbody>
        
        @foreach ($verval_wr1 as $row)
            {{-- @dd($row->semester) --}}
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $row->semester }}</td>
            <td>{{ $row->nim }}</td>
            <td>{{ $row->nama }}</td>
            <td>{{ $row->jenjang_prodi }} {{ $row->nama_prodi }}</td>
            <td>{{ $row->jenis }}</td>
            <td>{{ $row->kelompok }}</td>
            <td>{{ number_format($row->nominal_ukt, 0) }}</td>
            <td>
            @if ($row->file_pernyataan)
                <a href="{{ asset('storage/' . $row->file_pernyataan) }}" target="_blank" title="Surat Pernyataan Kebenaran Dokumen">Surat Pernyataan</a><br />
            @endif
            @if ($row->file_keterangan)
                <a href="{{ asset('storage/' . $row->file_keterangan) }}" target="_blank" title="Surat Keterangan dari kelurahan untuk yang terdampak">Surat Keterangan</a><br />
            @endif
            @if ($row->file_penghasilan)
                <a href="{{ asset('storage/' . $row->file_penghasilan) }}" target="_blank" title="Slip Gaji/Surat Keterangan Penghasilan yang disahkan oleh Lurah/Kepala Desa">Slip Gaji</a><br />
            @endif
            @if ($row->file_pailit)
                <a href="{{ asset('storage/' . $row->file_pailit) }}" target="_blank" title="Keputusan Pengadilan yang bersifat tetap untuk yang mengalami pailit/Surat Keterangan dari Kelurahan tentang usaha yang mengalami kebangkrutan">Surat Keterangan Pailit</a><br />
            @endif
            @if ($row->file_phk)
                <a href="{{ asset('storage/' . $row->file_phk) }}" target="_blank" title="Surat Keterangan Kematian/Surat Keterangan PHK/SK Pensiun/Keterangan Dokter jika sakit permanen">Surat PHK/Kematian</a><br />
            @endif
            @if ($row->file_pratranskrip)
                <a href="{{ asset('storage/' . $row->file_pratranskrip) }}" target="_blank">[Pra Transkrip]</a>
            @endif
            </td>
            <td><div class="alert alert-success">{{ $row->status ?? '' }}</div> </td>
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
            {{-- <form id="cetak-penerima-dispensasi" action="{{ route('penerima_dispensasi.print', ['semester' => $semester, 'kode_prodi' => $unit]) }}" method="get" target="_blank"> --}}
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
            {{-- </form> --}}

        </div>
        </div>
        <div class="modal-footer py-2">
        <button type="button" class="btn btn-primary" onclick="document.getElementById('cetak-penerima-dispensasi').submit()"><i class="fas fa-solid fa-print"></i> Cetak</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
    </div>
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
    // "responsive": true,
    // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
  });
})
</script>
@endsection
