<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ $title ?? 'Cetak Dokumen' }}</title>

  <style>
    body {
      font-family: 'Arial Narrow', Helvetica, sans-serif;
      font-size: 10pt;
      margin: 1cm 1cm 1cm 1cm;
    }

    table {
      font-family: 'Arial Narrow', Helvetica, sans-serif;
      border-collapse: collapse;
      color: 1px solid #000;
      font-size: 10px;
    }

    table.outline {
      border: 1px solid black;
    }

    td.border-top {
      border-top: 1px solid black;
    }

    td.border-right {
      border-right: 1px solid black;
    }

    td.border-left {
      border-left: 1px solid black;
    }

    td.align-top {
      vertical-align: top;
    }

    table.content td {
      padding-bottom: 0.5em;
    }

    .text-center {
      text-align: center;
    }

    .text-bold {
      font-weight: bold;
    }

    .text-right {
      text-align: right;
    }

  </style>
  @yield('style')
</head>

<body>

  @yield('content')

</body>

</html>
