<template>
<div>
  <Godamsadd :isSidebarActive="addNewDataSidebar" @closeSidebar="toggleDataSidebar" :data="sidebarData" />
  <div class="vx-row">
    <vs-card>
      <div class="vx-row p-4">
        <div class="vx-col w-1/3">
          <h5 class="">
            <v-select v-model="selectedStock" label="name" :options="stocks" :dir="$vs.rtl ? 'rtl' : 'ltr'">
              <span slot="no-options">
                {{$t('WhoopsNothinghere')}}
              </span>
            </v-select>
          </h5>
        </div>
        <div class="vx-col w-1/3"></div>
        <div class="vx-col w-1/3 float-left ">
          <vs-button color="primary" type="filled" class="float-right ml-3" @click="addNewData" icon="add">
            ثبت گدام جدید</vs-button>
        </div>
      </div>
      <hr class="m-3" />
      <ListOfGodam v-if="islist"></ListOfGodam>
      <h3 v-if="!islist">دیتا موجود نیست</h3>
    </vs-card>
  </div>
</div>
</template>

<script>
import Godamsadd from "./Godams_add.vue";
import ListOfGodam from "./ListOfGodam.vue";
import vSelect from "vue-select";
export default {
  name: "vx-archive",
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),

      stocks: [],
      selectedStock: {},
      islist: false,
      addNewDataSidebar: false,
      sidebarData: {},
    }

  },
  created() {
    this.getFirstStock();
    this.getStockList();
  },
  components: {
    "v-select": vSelect,
    Godamsadd,
    ListOfGodam,
  },

  methods: {
    getStockList() {
      this.axios.get('/api/godam')
        .then((response) => {
          this.stocks = response.data;
          // document.getElementById("loading-bg").style.display = "none";;
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
        })
    },
    getFirstStock() {
      this.axios.get('/api/firstgodam')
        .then((response) => {
          this.selectedStock = response.data;
          // document.getElementById("loading-bg").style.display = "none";;
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
        })
    },
    addNewData() {
      this.sidebarData = {};
      this.toggleDataSidebar(true);
    },
    toggleDataSidebar(val = false) {
      this.addNewDataSidebar = val;
    },
  },
};
</script>
