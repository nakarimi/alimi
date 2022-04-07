<template>
<div>
  <div class="vx-row">
    <div class="vx-col w-full md:w-3/3 mb-2">
      <vx-card>
        <div class="vx-row">
          <div class="md:w-1/2 lg:w-1/3 xl:w-1/3">
            <div class="vx-col w-full">
              <label for>
                <small>اکونت مربوط را انتخاب نماید!</small>
              </label>
              <v-select label="name" :options="allAccount" :dir="$vs.rtl ? 'rtl' : 'ltr'" @input="setVendordata" />
            </div>
          </div>
        </div>
      </vx-card>
    </div>
  </div>
  <div class="vx-row">
    <div class="vx-col w-full md:w-2/3">
      <vx-card title="لست آرشیف" style="height:527px;">
        <vs-table max-items="7" pagination :data="archieve" stripe>
          <template slot="header">
            <h4 class="p-4">
              <b>{{ fruit }}</b>
            </h4>
          </template>
          <template slot="thead">
            <vs-th>عنوان</vs-th>
            <vs-th>ریفرینس</vs-th>
            <vs-th>حساب</vs-th>
            <vs-th>نوع فایل</vs-th>
            <vs-th>تغییرات</vs-th>
          </template>
          <template slot-scope="{ data }">
            <vs-tr :key="indextr" v-for="(tr, indextr) in data">
              <vs-td :data="data[indextr].id">{{ data[indextr].id }}</vs-td>
              <vs-td :data="data[indextr].email">{{
                  data[indextr].email
                }}</vs-td>
              <vs-td :data="data[indextr].username">{{
                  data[indextr].username
                }}</vs-td>
              <vs-td :data="data[indextr].id">{{
                  data[indextr].website
                }}</vs-td>
            </vs-tr>
          </template>
        </vs-table>
      </vx-card>
    </div>
    <div class="vx-col w-full md:w-1/3">
      <vx-card title="افزودن به آرشیف">
        <div class="mb-3">
          <vs-input class="w-full" name="title" label="عنوان فایل ها" v-model="form.title" />
        </div>
        <div class="mb-3">
          <vs-input type="number" class="w-full" name="refcode" label="ریفرینس کود" v-model="form.refcode" />
        </div>
        <div class="mb-3">
          <vs-input type="text" class="w-full" name="type" label="نوع" v-model="form.type" />
        </div>
        <div id="scroll">
          <!-- <div> -->
          <vs-upload multiple @on-success="successUpload" />
        </div>
        <div class="flex flex-wrap items-center p-6" slot="footer">
          <vs-button class="mr-6" @click="submitData">انجام</vs-button>
          <vs-button type="border" color="danger" @click="isSidebarActiveLocal = false">لغو</vs-button>
        </div>
      </vx-card>
    </div>
  </div>
</div>
</template>

<script>
import vSelect from "vue-select";
export default {
  props: {
    data: {
      type: Object,
      default: () => {},
    },
  },
  name: "vx-archive",
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      allAccount: [],
      form: new Form({
        id: '',
        title: '',
        refcode: '',
        account_id: '',
        note: '',
        user_id: '',
        type: ''
      }),
      settings: {
        // perfectscrollbar settings
        maxScrollbarLength: 60,
        wheelSpeed: 0.6,
      },
      archievs: [],
    };
  },

  methods: {
    loadAccount() {
      // this.$vs.loading();
      this.$Progress.start();
      this.axios.get('/api/accoutload')
        .then((resp) => {
          this.allAccount = resp.data;
          this.$Progress.set(100);
          // document.getElementById("loading-bg").style.display = "none";;
const appLoading = document.getElementById("loading-bg");
if (appLoading) {
  appLoading.style.display = "none";
}
        });
    },
    successUpload() {
      this.$vs.notify({
        color: "success",
        title: "Upload Success",
        text: "Lorem ipsum dolor sit amet, consectetur",
      });
    },
    submitData() {},
    loadArchives() {}
  },

  computed: {
    isSidebarActiveLocal: {
      get() {
        return this.isSidebarActive;
      },
      set(val) {
        if (!val) {
          this.$emit("closeSidebar");
        }
      },
    },

    scrollbarTag() {
      return this.$store.getters.scrollbarTag;
    },
  },

  created() {
    this.loadAccount();
    this.loadArchives();
  },
  components: {
    "v-select": vSelect,
  },
}
</script>

<style>
[dir] .vx-row>.vx-col {
  padding: 0 0.4rem !important;
}

#scroll {
  height: 35vh;
  overflow-y: scroll;
}
</style>
