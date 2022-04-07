<template>
<vx-card class="no-shadow">
  <vs-tabs :position="isSmallerScreen ? 'top' : 'left'" class="tabs-shadow-none" id="profile-tabs" :key="isSmallerScreen">
    <!-- GENERAL -->
    <vs-tab v-if="user_permissions.includes('sales1_add') || user_permissions.includes('sales2_add')" label="عمده">
      <div class="tab-general md:ml-4 md:mt-0 mt-4 ml-0">
        <vs-tabs>
          <vs-tab v-if="user_permissions.includes('sales1_add')" label="قراردادی">
            <omde-qarardadi />
          </vs-tab>
          <vs-tab v-if="user_permissions.includes('sales2_add')" label="عادی">
            <omde-standard />
          </vs-tab>
        </vs-tabs>
      </div>
    </vs-tab>
    <vs-tab v-if="user_permissions.includes('sales3_add') || user_permissions.includes('sales4_add')" label="پرچون">
      <div class="tab-general md:ml-4 md:mt-0 mt-4 ml-0">
        <vs-tabs>
          <vs-tab v-if="user_permissions.includes('sales3_add')" label="قراردادی">
            <parchon-qarardadi />
          </vs-tab>
          <vs-tab v-if="user_permissions.includes('sales4_add')" label="عادی">
            <parchon-standard />
          </vs-tab>
        </vs-tabs>
      </div>
    </vs-tab>
  </vs-tabs>
</vx-card>
</template>

<script>
import OmdeStandard from './forms/OmdeStandard'
import ParchonStandard from './forms/ParchonStandard'
import OmdeQarardadi from './forms/OmdeQarardadi'
import ParchonQarardadi from './forms/ParchonQarardadi'
export default {
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
    }
  },
  computed: {
    isSmallerScreen() {
      return this.$store.state.windowWidth < 768
    }
  },
  components: {
    OmdeStandard,
    ParchonStandard,
    OmdeQarardadi,
    ParchonQarardadi
  }
}
</script>

<style lang="scss">
#profile-tabs {
  .vs-tabs--content {
    padding: 0;
  }
}
</style>
