<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
  <title>Aplikasi Keringanan UKT | {{ $mode }}</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">
  <link rel="stylesheet" href="{{ asset('css/my.css') }}">

  <style>
    .cinzel {
      font-family: 'Cinzel', serif !important;
    }
  </style>
  @yield('style')
</head>

<body class="hold-transition sidebar-mini layout-footer-fixed layout-navbar-fixed">
  <div class="wrapper">
    @include('layouts.navbar')
    @include('layouts.menu')

    <div class="content-wrapper">
      <div class="container-fluid">
        <div class="row">
          <div class="col">
            @yield('contain')

          </div>
        </div>
      </div>
    </div>

    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <strong>AdminLTE.io</a></strong>
      </div>
      <strong>Copyright &copy; 2022 <a href="https://pustikom.unj.ac.id/">UPT.TIK-UNJ</a>.</strong> <b>Version</b> 1.0.0
    </footer>

    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
  </div>

  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
  <script src="{{ asset('dist/js/demo.js') }}"></script>
  <script>
    $(function() {

      $('.select2').select2();
      var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
      });

      @if (Session::has('toast_success'))
        Toast.fire({
          icon: 'success',
          title: "{!! session('toast_success') !!}"
        })
      @endif

      @if (Session::has('toast_error'))
        Toast.fire({
          icon: 'error',
          title: "{!! session('toast_error') !!}"
        })
      @endif

      @if (Session::has('toast_info'))
        Toast.fire({
          icon: 'info',
          title: "{!! session('toast_info') !!}"
        })
      @endif

      @if (Session::has('toast_warning'))
        Toast.fire({
          icon: 'warning',
          title: "{!! session('toast_warning') !!}"
        })
      @endif

      @if (Session::has('toast_question'))
        Toast.fire({
          icon: 'warning',
          title:
        })
        Toast.fire({
          icon: 'question',
          title: "{!! session('toast_warning') !!}"
        })
      @endif

    });

    $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() {
        formatCurrency($(this), "blur");
      }
    });


    function formatNumber(n) {
      // format number 1000000 to 1,234,567
      return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    }


    function formatCurrency(input, blur) {
      // appends $ to value, validates decimal side
      // and puts cursor back in right position.

      // get input value
      var input_val = input.val();

      // don't validate empty input
      if (input_val === "") {
        return;
      }

      // original length
      var original_len = input_val.length;

      // initial caret position
      var caret_pos = input.prop("selectionStart");

      // check for decimal
      if (input_val.indexOf(",") >= 0) {

        // get position of first decimal
        // this prevents multiple decimals from
        // being entered
        var decimal_pos = input_val.indexOf(",");

        // split number by decimal point
        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);

        // add commas to left side of number
        left_side = formatNumber(left_side);

        // validate right side
        right_side = formatNumber(right_side);

        // On blur make sure 2 numbers after decimal
        if (blur === "blur") {
          right_side += "00";
        }

        // Limit decimal to only 2 digits
        right_side = right_side.substring(0, 2);

        // join number by .
        input_val = "Rp " + left_side + "," + right_side;

      } else {
        // no decimal entered
        // add commas to number
        // remove all non-digits
        input_val = formatNumber(input_val);
        input_val = "Rp " + input_val;

        // final formatting
        if (blur === "blur") {
          input_val += "";
        }
      }

      // send updated string to input
      input.val(input_val);

      // put caret back in the right position
      var updated_len = input_val.length;
      caret_pos = updated_len - original_len + caret_pos;
      input[0].setSelectionRange(caret_pos, caret_pos);
    }
  </script>
  @yield('script')
</body>

</html>
