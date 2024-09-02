<aside class="main-sidebar sidebar-dark-warning bg-unj elevation-4">
  <!-- Brand Logo -->
  <a href="/home" class="brand-link border-bottom border-warning">
    <img src="{{ asset('img/favicon-unj-ptnbh.png') }}" alt="Logo UNJ" class="brand-image img-circle elevation-3 mt-0 mr-2" height="80" width="auto">
    <span class="brand-text font-weight-bold cinzel" style="font-size: 90%">Keringanan UKT</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex justify-content-center"">
      <!--<div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>-->
      <div class="info">
        <span class="d-block text-white">Mode : [ {{ $mode }} ]</span>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="/home" class="nav-link {{ $home_active }}">
            <i class="nav-icon fas fa-home"></i>
            <p>
              Halaman Utama
            </p>
          </a>
        </li>

        @if (session('user_cmode') == '1')
          <li class="nav-item">
            <a href="/data_dispensasi" class="nav-link {{ $dispen_active }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Manajemen Data Keringanan UKT
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
        @elseif (session('user_cmode') == '2')
          <li class="nav-item">
            <a href="/penerima_dispensasi" class="nav-link {{ $penerima_active }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Pengajuan
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/laporan" class="nav-link {{ $laporan_active }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Rekapitulasi
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
        @elseif (session('user_cmode') == '3')
          <li class="nav-item" title="Database Kelompok UKT">
            <a href="/dataUKT" class="nav-link {{ $dataukt_active }}">
              <i class="nav-icon fas fa-database"></i>
              <p>
                DataBase UKT
              </p>
            </a>
          </li>
          <li class="nav-item" title="Verifikasi Pengajuan Keringanan UKT yang masuk">
            <a href="/verifikasi_dispensasi" class="nav-link {{ $dispen_active }}">
              <i class="nav-icon fas fa-check"></i>
              <p>
                Data Pengajuan
                <span class="badge bg-danger ml-2 right">{{ $badges->where('status_pengajuan', 0)->count('id') == 0 ? '' : $badges->where('status_pengajuan', 0)->count('id') }}</span>
              </p>
            </a>
          </li>
          <li class="nav-item" title="Daftar Penerima Keringanan UKT">
            <a href="/penerima_dispensasi" class="nav-link {{ $penerima_active }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Daftar Penerima
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
          <li class="nav-item" title="Cetak Laporan Penerima Keringanan UKT">
            <a href="/laporan" class="nav-link {{ $laporan_active }}">
              <i class="nav-icon fas fa-list-alt"></i>
              <p>
                Laporan Keringanan UKT
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
        @elseif (session('user_cmode') == '22')
          <li class="nav-item" title="Daftar Penerima Keringanan UKT">
            <a href="/penerbitan_sk" class="nav-link {{ $penerbitan_active }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Penerbitan SK
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/laporan" class="nav-link {{ $laporan_active }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Laporan Penerbitan SK
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
        @elseif (session('user_cmode') == '4')
        <li class="nav-item" title="Daftar Penerima Keringanan UKT">
          <a href="/penerima_dispensasi" class="nav-link {{ $penerima_active }}">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Daftar Penerima
              <!--<span class="right badge badge-danger">New</span>-->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/laporan" class="nav-link {{ $laporan_active }}">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Laporan Keringanan UKT
              <!--<span class="right badge badge-danger">New</span>-->
            </p>
          </a>
        </li>
      @elseif (session('user_cmode') == '11')
          <li class="nav-item" title="Daftar Penerima Keringanan UKT">
            <a href="/penerima_dispensasi" class="nav-link {{ $penerima_active }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Daftar Penerima
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/laporan" class="nav-link {{ $laporan_active }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Laporan Keringanan UKT
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
        @elseif (session('user_cmode') == '13')
          <li class="nav-item" title="Daftar Penerima Keringanan UKT">
            <a href="/penerima_dispensasi" class="nav-link {{ $penerima_active }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Daftar Penerima
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/laporan" class="nav-link {{ $laporan_active }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Laporan Keringanan UKT
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
        @elseif (session('user_cmode') == '14')
          <li class="nav-item">
            <a href="/verifikasiDekan_dispensasi" class="nav-link {{ $dispen_active }}">
              <i class="nav-icon fas fa-check"></i>
              <p>
                Verifikasi Dekan <span class="badge bg-danger ml-2 right">{{ $badges->where('status_pengajuan', 1)->count('id') == 0 ? '' : $badges->where('status_pengajuan', 1)->count('id') }}</span>
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
          <li class="nav-item" title="Daftar Penerima Keringanan UKT">
            <a href="/penerima_dispensasi" class="nav-link {{ $penerima_active }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Daftar Penerima
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/laporan" class="nav-link {{ $laporan_active }}">
              <i class="nav-icon fas fa-list-alt"></i>
              <p>
                Laporan Keringanan UKT
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
        @elseif (session('user_cmode') == '20')
          <li class="nav-item">
            <a href="/periode" class="nav-link {{ $periode_active }}">
              <i class="nav-icon fas fa-clock"></i>
              <p>
                Buka Periode
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/verifikasiWR2_dispensasi" class="nav-link {{ $dispen_active }}">
              <i class="nav-icon fas fa-check"></i>
              <p>
                Verifikasi WR II
                <span class="badge bg-danger ml-2 right">{{ $badges->where('status_pengajuan', 2)->count('id') == 0 ? '' : $badges->where('status_pengajuan', 2)->count('id') }}</span>
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
          <li class="nav-item" title="Daftar Penerima Keringanan UKT">
            <a href="/penerima_dispensasi" class="nav-link {{ $penerima_active }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Daftar Penerima
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/laporan" class="nav-link {{ $laporan_active }}">
              <i class="nav-icon fas fa-list-alt"></i>
              <p>
                Laporan Keringanan UKT
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
        @elseif (session('user_cmode') == '9')
          <li class="nav-item">
            <a href="/dispensasi" class="nav-link {{ $dispen_active }}">
              <i class="nav-icon fas fa-list"></i>
              <p>
                Keringanan UKT
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
        @else
          <li class="nav-item" title="Daftar Penerima Keringanan UKT">
            <a href="/pengajuan_dispensasi" class="nav-link {{ $dispen_active }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Daftar Penerima
                <!--<span class="right badge badge-danger">New</span>-->
              </p>
            </a>
          </li>
        @endif
        <hr>
        <li class="nav-item">
          <a href="/logout" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>
              Logout
            </p>
          </a>
        </li>
      </ul>
    </nav>


    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
