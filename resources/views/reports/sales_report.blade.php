@extends('reports.report')

@section('report_content')
<br><br>
<span>توضیحات: اطلاعات موجود در مورد فروشات اجناس و خدمات</span><br>
<span>تاریخ ابتداء: {{ $data->frdate }}</span><br>
<span>تاریخ انتهاء: {{ $data->todate }}</span><br>
<span>واحد پولی: {{ $data->currency == 'usd'? 'دالر' : 'افغانی'}}</span><br>
@if (!$data->report)
<p>هیچ سفارشی موجود نیست.</p>
@endif
<br>
@if ($data->report)
<table class="table table-striped report-table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">سریال نمبر</th>
      <th scope="col">مشتری</th>
      <th scope="col">اجناس</th>
      <th scope="col">تعداد/مقدار</th>
      <th scope="col">فیات</th>
      <th scope="col">قیمت مجموعی </th>
    </tr>
  </thead>
  <tbody>
    @foreach ($data->report as $i => $sale)
    <tr>
      <td>{{ $i+1 }}</td>
      <td>{{ @$sale->type }} - {{ @$sale->serial_no }}</td>
      <td>{{ @$sale->sales->project->pro_data->client->name }}</td>
      <td>
        <ul class="list-unstyled">
          @foreach ($sale->sales->pro_items as $item)
          <li>
            {{ @$item->item->type->type }} - 
            {{ @$item->item->name }}
          </li>
          @endforeach
        </ul>
      </td>
      <td>
        <ul class="list-unstyled">
          @foreach ($sale->sales->pro_items as $item)
          <li>
            {{ @$item->decrement_equiv }} {{ @$item->item->measurment_unites_sub->acronym }}
          </li>
          @endforeach
        </ul>
      </td>
      <td>
        <ul class="list-unstyled">
          @foreach ($sale->sales->pro_items as $item)
          <li>
            {{ @$item->unit_price }}
          </li>
          @endforeach
        </ul>
      </td>
      <td>
        <ul class="list-unstyled">
          @php
              $x = 0;
          @endphp
          @foreach ($sale->sales->pro_items as $item)
          @php
              $x += $item->total_price;
          @endphp
          @endforeach
          <li>
            {{ $x }} {{ $data->currency == 'usd'? 'دالر' : 'افغانی'}}
          </li>
        </ul>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endif

@endsection