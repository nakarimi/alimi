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
@foreach ($data->report as $i => $acc_type)
<div class="independent-table">
  <h5>{{ $acc_type->title }}</h5>
@if (count($acc_type->accounts))    
  <table class="table table-striped report-table border">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">عنوان حساب</th>
        <th scope="col">کردیت</th>
        <th scope="col">دیبت</th>
        <th scope="col">بیلانس</th>
        <th scope="col">حالت</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($acc_type->accounts as $ii => $account)
      <tr>
        <td>{{ $ii+1 }}</td>
        <td>{{ $account->name }}</td>
        <td>{{ $account->fr[$data->currency.'_credit'] }} {{ $data->currency == 'usd'? 'دالر' : 'افغانی'}}</td>
        <td>{{ $account->fr[$data->currency.'_debit'] }} {{ $data->currency == 'usd'? 'دالر' : 'افغانی'}}</td>
        <td>{{ $account->fr[$data->currency.'_credit'] - $account->fr[$data->currency.'_debit'] }}
          {{ $data->currency == 'usd'? 'دالر' : 'افغانی'}}</td>
        <td>{{ $account->status == 1 ? 'فعال' :'غیرفعال' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <p>حساب موجود نمیباشد.</p>    
  @endif    
</div>

@endforeach
@endif
@endsection