@extends('reports.report')

@section('report_content')
<br><br>
<span>توضیحات: اطلاعات موجود در مورد فروشات اجناس و خدمات</span><br>
<span>تاریخ ابتداء: {{ $data->frdate }}</span><br>
<span>تاریخ انتهاء: {{ $data->todate }}</span><br>
<span>واحد پولی: {{ $data->currency == 'usd'? 'دالر' : 'افغانی'}}</span><br>
@if (!$data->report)
<p>هیچ عایدی موجود نیست.</p>
@endif
<br>
@if ($data->report)
<table class="table table-striped report-table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">تاریخ</th>
      <th scope="col">مجموع</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($data->report as $i => $income)
    <tr>
      <td>{{ $i+1 }}</td>
      <td>{{ $income['date'] }}</td>
      <td>{{ $income['ammount'] }} {{ $data->currency == 'usd'? 'دالر' : 'افغانی'}}</td>
    </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <th scope="col" colspan="2">مجموع کل</th>
      <th scope="col">{{ $data->income_data_total }}  {{ $data->currency == 'usd'? 'دالر' : 'افغانی'}}</th>
    </tr>
  </tfoot>
</table>
@endif
@endsection