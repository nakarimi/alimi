<!DOCTYPE html>
<html>
<?php function calcTotalItemPrices($data)
{
$x = 0;
foreach ($data as $key => $value) {
$x += $value->total_price;
}
return $x;
} ?>

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <link rel="stylesheet" media="print" href="{{ URL::asset('css/print.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('css/print.css') }}" />
</head>

<body onload="window.print()">

  {{-- <body> --}}
  <div class="container rep_header">
    <header class="rep_header">
      <div class="rep_header_logo"><img src="/img/default/logo.png"></div>
      <div class="rep_header_title">
        <h2>شرکت تجارتی شایق علیمی لمیتد</h2>
      </div>
      <div class="inv_header_extra"><span>بل فروشات</span><br>شمارۀ بل: <span class="invoice_number"
          dir="ltr"></span><br>تاریخ صادره: <span class="curdate"></span></div>
    </header>
  </div>

  <div class="container mt-5">
    <h2 class="right-border">بل فروشات</h2>
    <div class="row justify-content-between">
      <table class="cu_in_table pull-left">
        <thead>
          <tr>
            <td style="width:20%;">شمارۀ بل: </td>
            <td style="width:80%;">{{ @$data['type'] . '-' . $data['sales']['serial_no'] }}</td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>مقصد:</td>
            <td><span class="cu_name">{{ @$data['sales']['destination'] }}</span></td>
          </tr>
          <tr>
            <td>نهاد:</td>
            <td><span class="" dir="ltr" style="text-align:left;">{{ @$data->client }}</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <br>
  <div class="container">
    <h2 class="right-border">لیست محصولات</h2>
    <div class="row justify-content-between">
      <div class="invtablediv">
        <table class="table-invoice">
          <thead>
            <tr>
              <th style="width:4%; text-align: center !important;">#</th>
              <th style="width:14%; text-align: center !important;">نام محصول</th>
              <th style="width:15%; text-align: center !important;">مقدار</th>
              <th style="width:10%; text-align: center !important;">فی واحد</th>
              <th style="width:15%; text-align: center !important;">مجموع</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($data['stock_records'] as $key => $strec)
            <tr>
              <td>{{ $key + 1 }}</td>
              <td>{{ $strec->item->type ? $strec->item->type->type : ''}} - {{ $strec->item->name }}</td>
              <td>{{ $strec->decrement_equiv }}</td>
              <td>{{ $strec->unit_price }}</td>
              <td>{{ $strec->total_price }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div>
          <br>
          <p>تفصیلات فروشات</p>
          <p>{{ @$data['sales']['description'] }}</p>

        </div>
        <br>
        @if (count($data['stock_records']) > 3)
        <div class="print-page-break" style="page-break-after: always;"></div>
        @endif
        <div class="table-prices-note">
          <table class="table-prices text-right float-left">
            <thead>
              <tr>
                <th>قیمت مجموعی اجناس :</th>
                <th style="width:142px;">{{ calcTotalItemPrices($data['stock_records']) }} AFN</th>
              </tr>
              {{-- <tr>
                <th style="text-align:left !important;">خدمات :</th>
                <th>{{ @$data['sales']['service_cost'] }} AFN</th>
              </tr> --}}
              <tr>
                <th style="text-align:left !important;">مبلغ قابل تادیه :</th>
                {{-- <th>{{ @$data['sales']['total'] }} AFN</th> --}}
                <th>{{ @calcTotalItemPrices($data['stock_records']) }} AFN</th>
              </tr>
              <tr>
                <th colspan="6" style="background-color: #fff; line-height: 3px !important;">&nbsp;</th>
              </tr>
              <tr>
                <th style="text-align:left !important;">مبلغ پرداخت شده :</th>
                <th>0 AFN</th>
              </tr>
            </thead>
          </table>
          <div class="inv_stamp pull-left margin-top-30" style="width:50%;">
            <div class="inv_desc">
              ملاحضات:<br>
              <div class="inv_desc_detail">
                به تعداد ({{ count($data['stock_records']) }}) قلم جنس برای محترم/محترمه
                "{{ @$data['sales']['client']['name'] }}" به ارزش "{{ @$data['sales']['total'] }} افغانی" به
                فروش رسید.
              </div>
            </div>
            <div class="inv_verify margin-top-30">
              مهر و امضاء:<br>
              <div class="inv_desc_detail">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer">
    <div class="container">
      <span class="hidespan seller" style="float: left;padding-left: 2%;">چاپ شده توسط
        {{ $data['printname'] }}</span>
    </div>
  </div>
</body>
<script>
  window.onafterprint = function() {
    window.close();
  }

</script>

</html>