<template>
  <p style="height:40px;margin:20px;" id="success-load">
  </p>
</template>

<script>
export default {
  name: 'vx-table-loading',
  mounted() {
    this.$vs.loading({
      container: '#success-load',
      type: 'sound',
      text: "درحال بارگیری...."
    })
  }

}
</script>
