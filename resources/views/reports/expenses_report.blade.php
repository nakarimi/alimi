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
<table class="table table-striped report-table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">عنوان مصرف</th>
      <th scope="col">مقدار</th>
      <th scope="col">حساب کردیت</th>
      <th scope="col">حساب دیبت</th>
      <th scope="col">زمان</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($data->report as $i => $expense)
    <tr>
      <td>{{ $i+1 }}</td>
      <td>{{ $expense->title }}</td>
      <td>{{ $expense->ammount }} {{$expense->currency->sign_fa}}</td>
      <td>{{ $expense->credit_account->name }}</td>
      <td>{{ $expense->debit_account->name }}</td>
      <td>{{ $expense->datetime }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
@endif
@endsection