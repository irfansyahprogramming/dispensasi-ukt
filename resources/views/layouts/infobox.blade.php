<div class="row">
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Pengajuan</span>
        <span class="info-box-number">{{ $pengajuan->where('mode', 3)->count('id') }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Disetujui Dekan</span>
        <span class="info-box-number">{{ $pengajuan->where('mode', 14)->count('id') }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Disetujui WR2</span>
        <span class="info-box-number">{{ $pengajuan->where('mode', 20)->count('id') }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-danger"><i class="far fa-star"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Pembebasan UKT</span>
        <span class="info-box-number">{{ $pengajuan->where('mode', 20)->where('status_ajuan','1')->where('id_jenis_dispensasi','6')->count('id') }}</span>
      </div>
    </div>
  </div>
</div>
