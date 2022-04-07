<template>
<div>
  <vs-tabs :value="$route.query.tab">
    <vs-tab v-if="user_permissions.includes('purchase_add')" label="ثبت خریداری">
      <ProcurmentaddForm></ProcurmentaddForm>
    </vs-tab>
    <vs-tab v-if="user_permissions.includes('purchase_view')" label="لیست خریداری">
      <Procurmentlist></Procurmentlist>
    </vs-tab>
  </vs-tabs>
</div>
</template>

<script>
import Procurmentlist from './Procurmentlist.vue'
import ProcurmentaddForm from './ProcurmentaddForm.vue'

export default {
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
    }
  },
  components: {
    Procurmentlist,
    ProcurmentaddForm
  },
}
</script>
