<template>
<div>
  <vs-tabs :value="$route.query.tab">
    <vs-tab v-if="user_permissions.includes('sales1_add') ||
     user_permissions.includes('sales2_add') ||
     user_permissions.includes('sales3_add')||
     user_permissions.includes('sales4_add')" label="فورم">
      <SalesFormAdd></SalesFormAdd>
    </vs-tab>
    <vs-tab v-if="user_permissions.includes('sales1_view') ||
     user_permissions.includes('sales2_view') ||
     user_permissions.includes('sales3_view')||
     user_permissions.includes('sales4_view')" label="لیست">
      <SalesList></SalesList>
    </vs-tab>
    <!--<vs-tab label="بازرسی" id="evaluate">
      <SalesInside></SalesInside>
    </vs-tab>-->
  </vs-tabs>
</div>
</template>

<script>
import SalesFormAdd from './SalesFormAdd'
import SalesList from './SalesList'
import SalesInside from './SalesInside'
export default {
  name: 'vx-sale-tabes',
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
    }
  },
  components: {
    SalesFormAdd,
    SalesList,
    SalesInside
  },
  created() {
    const appLoading = document.getElementById("loading-bg");
    if (appLoading) {
      appLoading.style.display = "none";
    }
  },
}
</script>
