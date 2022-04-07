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
  <div class="container rep_header">
    <header class="rep_header">
      <div class="rep_header_logo"><img src="/img/default/logo.png"></div>
      <div class="rep_header_title">
        <h2>شرکت تجارتی شایق علیمی لمیتد</h2>
      </div>
      <div class="inv_header_extra"><span>بل آقر</span><br>شمارۀ بل: <span class="invoice_number"
          dir="ltr"></span><br>تاریخ صادره: <span class="curdate"></span></div>
    </header>
  </div>

  <div class="container mt-5">
    <h2 class="right-border">معلومات آقر</h2>
    <div class="row justify-content-between">
      <table class="cu_in_table pull-left">
        <thead>
          <tr>
            <td style="width:20%;">سریال نمبر: </td>
            <td style="width:80%;">
              {{ $data['pro_data']['company'] ? $data['pro_data']['company']['sign'] : '' }}-{{ $data['serial_no'] }}
            </td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>نام قرارداد:</td>
            <td><span class="cu_name">{{ $data['pro_data']['title'] ? $data['pro_data']['title'] : '-' }}</span>
            </td>
          </tr>
          <tr>
            <td>تاریخ نشر اعلان: </td>
            <td><span class="" dir="ltr" style="text-align:left;">{{ @$data->publish_date }}</span></td>
          </tr>
          <tr>
            <td>آدرس نشر اعلان: </td>
            <td><span class="" dir="ltr" style="text-align:left;">{{ @$data->publish_address }}</span></td>
          </tr>
          <tr>
            <td>تاریخ آفرگشایی:</td>
            <td><span class="" dir="ltr" style="text-align:left;">{{ @$data->bidding_date }}</span></td>
          </tr>
          <tr>
            <td>آدرس آفرگشایی: </td>
            <td><span class="" dir="ltr" style="text-align:left;">{{ @$data->bidding_address }}</span></td>
          </tr>
          <tr>
            <td>تاریخ ختم پیشنهاد:</td>
            <td><span class="" dir="ltr" style="text-align:left;">{{ @$data->submission_date }}</span></td>
          </tr>
          <tr>
            <td>شماره شناسایی آقر:</td>
            <td><span class="" dir="ltr" style="text-align:left;">{{ @$data->pro_data->reference_no }}</span></td>
          </tr>
          <tr>
            <td>مرجع مربوطه:</td>
            <td><span class="" dir="ltr" style="text-align:left;">{{ @$data->pro_data->client->name }}</span></td>
          </tr>
          <tr>
            <td>تضمین آفر:</td>
            <td><span class="" dir="ltr" style="text-align:left;">{{ @$data->offer_guarantee }}</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="container">
    <h2 class="right-border">لیست محصولات</h2>
    <div class="row justify-content-between">
      <div class="invtablediv dontsplit">
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
            @foreach ($data['pro_items'] as $key => $strec)
              <tr>
                <td>{{ $key + 1 }}</td>
                                  <td>{{ $strec->item->type ? $strec->item->type->type : ''}} - {{ $strec->item->name }}</td>
                <td>{{ $strec->equivalent }}</td>
                <td>{{ $strec->unit_price }}</td>
                <td>{{ $strec->total_price }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <br>
        @if (count($data['pro_items']) > 3)
          <div class="print-page-break" style="page-break-after: always;"></div>
        @endif
        <div class="table-prices-note">
          <table class="table-prices text-right float-left">
            <thead>
              <tr>
                <th>قیمت مجموعی اجناس :</th>
                <th style="width:142px;">{{ calcTotalItemPrices($data['pro_items']) }} AFN</th>
              </tr>
              <tr>
                <th style="text-align:left !important;">ارزش آقر :</th>
                <th>{{ @$data->pro_data->pr_worth }} AFN</th>
              </tr>
              <tr>
                <th style="text-align:left !important;">تامینات :</th>
                <th>{{ @$data->pro_data->deposit }} %</th>
              </tr>
              <tr>
                <th style="text-align:left !important;">مالیات :</th>
                <th>{{ @$data->pro_data->tax }} %</th>
              </tr>
              <tr>
                <th style="text-align:left !important;">انتقالات :</th>
                <th>{{ @$data->pro_data->transit }} AFN</th>
              </tr>
              {{-- <tr>
                <th style="text-align:left !important;">خدمات :</th>
                <th>{{ @$data->pro_data->others }} AFN</th>
              </tr> --}}
              <tr>
                <th style="text-align:left !important;">تخفیف :</th>
                <th>{{ @$data->pro_data->discount }} AFN</th>
              </tr>
              <tr>
                <th style="text-align:left !important;">قیمت نرخ دهی :</th>
                <th>{{ @$data->pro_data->total_price }} AFN</th>
              </tr>
            </thead>
          </table>
          <div class="inv_stamp pull-left margin-top-30" style="width:50%;">
            <div class="inv_desc">
              ملاحضات:<br>
              <div class="inv_desc_detail">
                به تعداد ({{ count($data['pro_items']) }}) قلم جنس برای محترم/محترمه
                "{{ @$data->pro_data->client->name }}" به ارزش "{{ @$data->pro_data->total_price }} افغانی" به
                آفر تهیه شده است.
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
