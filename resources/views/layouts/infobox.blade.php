{{-- @dd($pengajuan) --}}
<div class="row">
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Pengajuan</span>
        <span class="info-box-number">{{ $total_ajuan ?? 0 }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-primary"><i class="far fa-edit"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Verifikasi Fakultas</span>
        <span class="info-box-number">{{ $total_fakultas ?? 0 }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Disetujui Dekan</span>
        <span class="info-box-number">{{ $total_dekan ?? 0 }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Disetujui WR2</span>
        <span class="info-box-number">{{ $total_wr2 ?? 0 }}</span>
      </div>
    </div>
  </div>
  {{-- <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-info"><i class="fa fa-list"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Penerbitan SK</span>
        <span class="info-box-number">{{ $total_hutalak ?? 0 }}</span>
      </div>
    </div>
  </div> --}}
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-danger"><i class="far fa-star"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Selesai</span>
        <span class="info-box-number">{{ $total_upttik ?? 0 }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-primary"><i class="fa fa-bars"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Potongan 50% <small><strong>(disetujui)</strong></small></span>
        <span class="info-box-number">{{ $total_50 ?? 0 }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-success"><i class="fa fa-star"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Penundaan UKT <small><strong>(disetujui)</strong></small></span>
        <span class="info-box-number">{{ $total_tangguh ?? 0 }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-danger"><i class="fa fa-lock"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Angsuran UKT <small><strong>(disetujui)</strong></small></span>
        <span class="info-box-number">{{ $total_cicilan ?? 0 }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-warning"><i class="fa fa-heart"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Perubahan UKT <small><strong>(disetujui)</strong></small></span>
        <span class="info-box-number">{{ $total_turun ?? 0 }}</span>
      </div>
    </div>
  </div>
</div>
