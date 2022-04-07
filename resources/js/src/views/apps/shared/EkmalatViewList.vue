<template lang="">
<div>
  <vs-table :data="items">
    <template slot="thead">
      <vs-th>جنس / محصول</vs-th>
      <vs-th>مقدار</vs-th>
      <!-- <vs-th>عملیه</vs-th> -->
      <vs-th>قیمت فی واحد</vs-th>
      <vs-th>قیمت مجموعی</vs-th>
    </template>
    <template slot-scope="{data}">
      <vs-tr v-for="(tr, i) in data" :key="i">
        <vs-td :data="tr.item_id">
          <p> {{ (tr.item_id && tr.item_id.type) ? tr.item_id.type.type : '' }} - {{ tr.item_id.name }} </p>
        </vs-td>
        <vs-td :data="tr.equivalent">
          {{tr.equivalent | NumThreeDigit}} {{ (tr.item_id.uom_equiv_id) ? tr.item_id.uom_equiv_id.title : '' }}
        </vs-td>
        <!-- <vs-td :data="tr.operation_id">
            <p> {{ tr.operation_id.formula }} </p>
          </vs-td> -->
        <vs-td :data="tr.unit_price">
          {{tr.unit_price | NumThreeDigit}} <small style="color:#42b983;"><b>افغانی </b></small>
        </vs-td>
        <vs-td :data="tr.total_price">
          {{tr.total_price | NumThreeDigit}} <small style="color:#42b983;"><b>افغانی </b></small>
        </vs-td>
      </vs-tr>
    </template>
  </vs-table>
</div>
</template>

<script>
export default {
  props: ["items"],
}
</script>

<style lang="">
  
</style>
