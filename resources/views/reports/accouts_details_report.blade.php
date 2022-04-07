@extends('reports.report')
@section('report_content')
<br><br>
<span>توضیحات: اطلاعات موجود در مورد فروشات اجناس و خدمات</span><br>
<span>تاریخ ابتداء: {{ $data->frdate }}</span><br>
<span>تاریخ انتهاء: {{ $data->todate }}</span><br>
<span>واحد پولی: {{ $data->currency == 'usd'? 'دالر' : 'افغانی'}}</span><br>
@if (!$data->report)
<p>هیچ مصرفی موجود نیست.</p>
@endif
<br>
@if ($data->report)
<div class="independent-table">
  <h5>حساب {{ $data->account->name }}</h5>
  <table class="table table-striped report-table border">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">عنوان</th>
        <th scope="col">کردیت</th>
        <th scope="col">دیبت</th>
        <th scope="col">بیلانس</th>
        <th scope="col">زمان فعالیت</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data->report as $i => $fr)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $fr->description }}</td>
        <td>{{ $fr[$data->currency.'_credit'] }} {{ $data->currency == 'usd'? 'دالر' : 'افغانی'}}</td>
        <td>{{ $fr[$data->currency.'_debit'] }} {{ $data->currency == 'usd'? 'دالر' : 'افغانی'}}</td>
        <td>{{ $fr[$data->currency.'_credit'] - $fr[$data->currency.'_debit'] }}
          {{ $data->currency == 'usd'? 'دالر' : 'افغانی'}}</td>
        <td>{{ $fr->created_fa }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif
@endsection 