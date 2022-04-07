<!DOCTYPE html>
<html>

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <link rel="stylesheet" media="print" href="{{ URL::asset('css/print.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('css/print.css') }}" />
</head>

{{-- <body onload="window.print()"> --}}

<body style="opacity: 1 !important">
  <div class="container rep_header">
    <header class="rep_header">
      <div class="rep_header_logo"><img src="/img/default/logo.png"></div>
      <div class="rep_header_title">
        <h2>شرکت تجارتی شایق علیمی لمیتد</h2>
      </div>
      <div class="inv_header_extra"><span>بل قرارداد</span><br>شمارۀ بل: <span class="invoice_number"
          dir="ltr"></span><br>تاریخ صادره: <span class="curdate"></span></div>
    </header>
  </div>

  <div class="container">
    @yield('report_content')
    <p>راپور ها گرفته شده از تاریخ ({{ $data->todate }}) الی ({{ $data->frdate }}) است.</p>
    <p class="text-center">-------------------- پــــایـــان --------------------</p>
  </div>

  
  <div class="footer">
    <div class="container">
      <span class="hidespan seller" style="float: left;padding-left: 2%;">چاپ شده توسط
        {{ $data['printname'] ? $data['printname'] : 'سیستم' }}</span>
    </div>
  </div>
</body>
<script>
  window.onafterprint = function() {
    window.close();
  }

</script>

</html>