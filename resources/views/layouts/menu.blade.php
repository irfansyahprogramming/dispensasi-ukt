<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/home" class="brand-link">
      <img src="{{ asset('img/Logo-unj.png') }}" alt="Labschool-Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Dispensasi UKT</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex justify-content-center"">
        <!--<div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>-->
        <div class="info">
          <a href="#" class="d-block">Mode : [ {{ $mode }} ]</a>
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
                        Manajemen Data Dispensasi
                        <!--<span class="right badge badge-danger">New</span>-->
                    </p>
                    </a>
                </li>

            @elseif (session('user_cmode') == '3')
           
                <li class="nav-item">
                    <a href="/verifikasi_dispensasi" class="nav-link {{ $dispen_active }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Verifikasi Dispensasi UKT
                            <span class="badge bg-warning text-right">{{ ($badges->where('status_pengajuan', 0)->count('id') == 0) ? '' : $badges->where('status_pengajuan', 0)->count('id')}}</span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                  <a href="/penerima_dispensasi" class="nav-link {{ $penerima_active }}">
                      <i class="nav-icon fas fa-user"></i>
                      <p>
                          Penerima Dispensasi
                          <!--<span class="right badge badge-danger">New</span>-->
                      </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/laporan" class="nav-link {{ $laporan_active }}">
                      <i class="nav-icon fas fa-user"></i>
                      <p>
                          Laporan Verifikasi Dispen
                          <!--<span class="right badge badge-danger">New</span>-->
                      </p>
                  </a>
                </li>
            
            @elseif (session('user_cmode') == '4')
           
                <li class="nav-item">
                  <a href="/penerima_dispensasi" class="nav-link {{ $penerima_active }}">
                      <i class="nav-icon fas fa-user"></i>
                      <p>
                          Penerima Dispensasi
                          <!--<span class="right badge badge-danger">New</span>-->
                      </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/laporan" class="nav-link {{ $laporan_active }}">
                      <i class="nav-icon fas fa-user"></i>
                      <p>
                          Laporan Verifikasi Dispen
                          <!--<span class="right badge badge-danger">New</span>-->
                      </p>
                  </a>
                </li>
            
            @elseif (session('user_cmode') == '14')
           
                <li class="nav-item">
                    <a href="/verifikasiDekan_dispensasi" class="nav-link {{ $dispen_active }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Verifikasi Dekan <span class="badge bg-warning text-right">{{ ($badges->where('status_pengajuan', 1)->count('id') == 0) ? '' : $badges->where('status_pengajuan', 1)->count('id')}}</span>
                            <!--<span class="right badge badge-danger">New</span>-->
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                  <a href="/penerima_dispensasi" class="nav-link {{ $penerima_active }}">
                      <i class="nav-icon fas fa-user"></i>
                      <p>
                          Penerima Dispensasi
                          <!--<span class="right badge badge-danger">New</span>-->
                      </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/laporan" class="nav-link {{ $laporan_active }}">
                      <i class="nav-icon fas fa-user"></i>
                      <p>
                          Laporan Verifikasi Dispen
                          <!--<span class="right badge badge-danger">New</span>-->
                      </p>
                  </a>
                </li>

            @elseif (session('user_cmode') == '20')
           
                <li class="nav-item">
                    <a href="/periode" class="nav-link {{ $periode_active }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Buka Periode
                            <!--<span class="right badge badge-danger">New</span>-->
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                  <a href="/verifikasiWR2_dispensasi" class="nav-link {{ $dispen_active }}">
                      <i class="nav-icon fas fa-user"></i>
                      <p>
                          Verifikasi WR2
                          <span class="badge bg-warning text-right">{{ ($badges->where('status_pengajuan', 2)->count('id') == 0) ? '' : $badges->where('status_pengajuan', 2)->count('id')}}</span>
                          <!--<span class="right badge badge-danger">New</span>-->
                      </p>
                  </a>
              </li>
                <li class="nav-item">
                  <a href="/penerima_dispensasi" class="nav-link {{ $penerima_active }}">
                      <i class="nav-icon fas fa-user"></i>
                      <p>
                          Penerima Dispensasi
                          <!--<span class="right badge badge-danger">New</span>-->
                      </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/laporan" class="nav-link {{ $laporan_active }}">
                      <i class="nav-icon fas fa-user"></i>
                      <p>
                          Laporan Verifikasi Dispen
                          <!--<span class="right badge badge-danger">New</span>-->
                      </p>
                  </a>
                </li>

            @elseif (session('user_cmode') == '9')
           
                <li class="nav-item">
                    <a href="/dispensasi" class="nav-link {{ $dispen_active }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                        Dispensasi UKT
                            <!--<span class="right badge badge-danger">New</span>-->
                        </p>
                    </a>
                </li>

            @else
                
                <li class="nav-item">
                    <a href="/pengajuan_dispensasi" class="nav-link {{ $dispen_active }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                        Data Dispensasi UKT
                            <!--<span class="right badge badge-danger">New</span>-->
                        </p>
                    </a>
                </li>
                
            @endif
          <li class="nav-item">
            <a href="/logout" class="nav-link">
              <i class="nav-icon far fa-file"></i>
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