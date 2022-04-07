<template>
<div>
  <Goodadd :key="goodAddKey" :isSidebarActive="addNewDataSidebar" @closeSidebar="toggleDataSidebar" :data="sidebarData" />
  <div class="vx-row">
    <vs-card>
      <div class="vx-row mb-3">
        <div class="vx-col w-1/2">
          <h4 class="mt-4">
            <b> اجناس </b>
          </h4>
        </div>
        <div v-if="user_permissions.includes('product_add')" class="vx-col w-1/2 float-left">
          <vs-button color="primary" type="filled" class="float-right ml-3" @click="addNewData">ثبت جنس جدید</vs-button>
        </div>
      </div>
      <hr />
      <br />
      <Itemlist v-if="user_permissions.includes('product_view')" ref="goodList" @openGoodModal="editGoodData"></Itemlist>
    </vs-card>
  </div>
  <!-- <vs-card>
      <div class="vx-row"></div>
    </vs-card> -->
</div>
</template>

<script>
import Goodadd from "./Goods_add.vue";
import Itemlist from "./Item_list.vue";
import vSelect from "vue-select";
export default {
  name: "vx-godams",
  data: () => ({
    // Data Sidebar
    user_permissions: localStorage.getItem('user_permissions'),
    addNewDataSidebar: false,
    sidebarData: {},
    currentx: 14,
    goodAddKey: 0,
  }),
  created() {
    if(this.$refs.goodList){
      this.$refs.goodList.loaditem();
    }
  },
  components: {
    "v-select": vSelect,
    Goodadd,
    Itemlist,
  },
  methods: {
    editGoodData(data) {
      this.sidebarData = data;
      this.goodAddKey += 1;
      this.toggleDataSidebar(true);
    },
    addNewData() {
      this.sidebarData = {};
      this.goodAddKey += 1;
      this.toggleDataSidebar(true);
    },
    toggleDataSidebar(val = false, loading = false) {
      if(loading){
        this.$refs.goodList.loaditem();
      }
      this.addNewDataSidebar = val;
    },
  },
};
</script>

<style lang="stylus">
.vs-input--icon {
  top: 12px;
}

.customstyle {
  border-right: solid;
  border-right-width: initial;
  border-right-style: solid;
  border-right-color: initial;
}
</style>
