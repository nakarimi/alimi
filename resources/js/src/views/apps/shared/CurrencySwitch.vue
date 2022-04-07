<template>
<div class="balance_list_currency_toggle sm:w-auto md:w-1/2 lg:w-1/4 float-right xl:w-1/4">
  <div class="radio-group w-full">
    <div class="w-1/2">
      <input type="radio" v-model="currency_display" value="afn" id="struct" name="curency" />
      <label for="struct" class="w-full text-center">افغانی</label>
    </div>
    <div class="w-1/2">
      <input type="radio" v-model="currency_display" value="usd" id="specific" name="curency" />
      <label for="specific" class="w-full text-center">دالر</label>
    </div>
  </div>
</div>
</template>

<script>
export default {
  props:['currency_display']

}
</script>

<style>

</style>
