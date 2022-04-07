<template>
<div>
  <vs-tabs :value="$route.query.tab">
    <vs-tab v-if="user_permissions.includes('transaction_add')"  label="ثبت معاملات">
      <TransactionAdd></TransactionAdd>
    </vs-tab>
    <vs-tab v-if="user_permissions.includes('account_view')"  label="لیست معاملات">
      <Transactionlist></Transactionlist>
    </vs-tab>
  </vs-tabs>
</div>
</template>

<script>
import Transactionlist from "./Transactionlist.vue";
import TransactionAdd from "./Transactionadd.vue";

export default {
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
    }
  },
  components: {
    Transactionlist,
    TransactionAdd,
  },
  created() {
    // this.$vs.loading();
    setTimeout(() => {
      document.getElementById("loading-bg").style.display = "none";
    }, 2000);
  }
};
</script>
