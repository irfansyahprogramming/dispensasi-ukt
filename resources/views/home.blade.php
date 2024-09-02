@extends('layouts.main')
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
    @if ($cmode == '2' || $cmode == '3' || $cmode == '4' || $cmode == '11' || $cmode == '13' || $cmode == '14' || $cmode == '20')
      @include('layouts.infobox')
    @endif
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Informasi </h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
          {{-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
          </button> --}}
        </div>
      </div>
      <div class="card-body">
        Selamat Datang di Aplikasi Keringanan UKT Mahasiswa Universitas Negeri Jakarta
      </div>

    </div>
  </section>
@endsection
