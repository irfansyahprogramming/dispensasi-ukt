<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">

  <!--====================================================================================================================================-->
  <title>Dispensasi UKT</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    html,
    body {
      /* background-image: url('img/black.jpg'); */
      /* background-image: url('https://source.unsplash.com/1600x900/?nature'); */
      background: rgb(0, 143, 145);
      background: linear-gradient(90deg, rgba(0, 143, 145, 1) 0%, rgba(0, 143, 145, 1) 0%, rgba(43, 152, 162, 1) 43%, rgba(0, 143, 145, 1) 100%, rgba(0, 143, 145, 1) 100%);
      background-size: cover;
      background-repeat: no-repeat;
      height: 100vh;

    }

    .cinzel {
      font-family: 'Cinzel', serif !important;
    }

    .container {
      height: 100%;
      align-content: center;
    }

    .card {
      /* height: 340px; */
      margin-top: auto;
      margin-bottom: auto;
      width: 740px;
      background-color: rgba(245, 245, 245, .925) !important;
    }

    .unj-color {
      color: #006f45;
    }

    .card-header h3 {
      color: white;
    }

    .social_icon {
      position: absolute;
      right: 20px;
      top: -45px;
    }

    .input-group-prepend span {
      width: 50px;
      background-color: #FFC312;
      color: black;
      border: 0 !important;
    }

    input {
      font-size: .825rem !important;
    }

    input:focus {
      outline: 0 0 0 0 !important;
      box-shadow: 0 0 0 0 !important;

    }

    .remember {
      color: white;
    }

    .remember input {
      width: 20px;
      height: 20px;
      margin-left: 15px;
      margin-right: 5px;
    }

    .login_btn {
      color: black;
      background-color: #FFC312;
      width: 100px;
    }

    .login_btn:hover {
      color: black;
      background-color: white;
    }

    .links {
      color: white;
    }

    .links a {
      margin-left: 4px;
    }

    @media(max-height: 420px) {
      .social_icon span {
        display: none;
      }


    }
  </style>
</head>

<body class="small">
  <div class="container">
    <div class="d-flex justify-content-center h-100">
      <div id="login-box" class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 d-flex flex-column justify-content-center align-items-center mb-2">
              <img src="{{ asset('img/logo_unj_green_small.png') }}" width="auto" height="250" />
            </div>
            <div class="col-md-6">
              <form action="{{ route('login.attemptLogin') }}" method="post">
                @csrf

                <div class="form-group mb-4">
                  <h5 class="text-center font-weight-bold unj-color cinzel">Aplikasi Dispensasi UKT</h5>
                  <h5 class="text-center font-weight-bold unj-color cinzel">Universitas Negeri Jakarta</h5>

                </div>

                <div class="form-group">
                  <label for="username" class="font-weight-bold unj-color">Username</label>
                  <div class="input-group mb-3">
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username Siakad yang anda gunakan" required>
                    <div class="input-group-append">
                      <div class="input-group-text">
                        <span class="fas fa-key"></span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group mb-4">
                  <label for="password" class="font-weight-bold unj-color">Password</label>
                  <div class="input-group mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password Siakad yang anda gunakan" required>
                    <div class="input-group-append">
                      <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-8">
                      <div class="text-sm text-danger" style="font-size: 12px">
                        @if (session('login_msg'))
                          {!! session('login_msg') !!}
                        @endif
                      </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                      <button type="submit" class="btn btn-success btn-block ">Masuk</button>
                    </div>
                    <!-- /.col -->
                  </div>
                </div>


              </form>
            </div>
          </div>

        </div>
        <div class="card-footer">
          <div class="d-flex flex-column">
            <h6 class="text-center unj-color cinzel">Copyright &copy; <?= date('Y') == 2022 ? '2022' : '2022 - ' . date('Y') ?> : Universitas Negeri Jakarta (v.1.0)</h6>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript
================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>
