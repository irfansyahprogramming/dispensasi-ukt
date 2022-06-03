<div class="row">
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Pengajuan</span>
        <span class="info-box-number">{{ $pengajuan->count('id') }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Disetujui Dekan</span>
        <span class="info-box-number">{{ $pengajuan->where('status_pengajuan', 2)->count('id') }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Disetujui WR2</span>
        <span class="info-box-number">{{ $pengajuan->where('status_pengajuan', 4)->count('id') }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-danger"><i class="far fa-star"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Pembebasan UKT</span>
        <span class="info-box-number">{{ $pengajuan->where('status_pengajuan', 7)->count('id') }}</span>
      </div>
    </div>
  </div>
</div>
