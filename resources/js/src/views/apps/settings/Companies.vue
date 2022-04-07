<template>
<vs-row vs-w="12" class="mb-4">
  <div class="w-full">
    <div class="vx-card">
      <div class="vx-card__header">
        <div class="vx-card__title">
          <h4 class="">لست کمپنی ها</h4>
        </div>
        <!--<vs-button type="border" @click="blue=true"></vs-button>-->
      </div>
      <component :is="scrollbarTag" :key="$vs.rtl" class="setting-height-scroll">
        <div class="pt-6 pr-6 pl-6 pb-6">
          <div class="vx-card__body">
            <vs-table :data="companies" stripe>
              <template slot="thead">
                <vs-th>#</vs-th>
                <vs-th>لیبل</vs-th>
                <vs-th>علامت</vs-th>
                <vs-th>بررسی</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="tr.id">
                    {{ ++indextr }}
                  </vs-td>
                  <vs-td :data="tr.id">
                    {{ tr.label }}
                  </vs-td>
                  <vs-td :data="tr.id">
                    {{ tr.sign }}
                  </vs-td>
                  <vs-td :data="tr.id">
                    <feather-icon icon="EyeIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click.stop="showDetailModal(tr.id)" />
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
            <vs-popup class="holamundo" title="جزییات کمپنی" :active.sync="showmodal">
              <div :key="indextr" v-for="(tr, indextr) in company">
                <div class="con-expand-clients w-full">
                  <div class="con-btns-client flex items-center justify-between">
                    <div class="con-clientx flex items-center justify-start">
                      <vs-avatar size="60px" :src="'/images/img/company/'+tr.logo" />
                      <span><strong>{{ tr.label }}</strong></span>
                    </div>

                    <div class="flex">
                    </div>
                  </div>
                  <vs-list>
                    <vs-list-item icon-pack="feather" icon="icon-user" color="success" :title="tr.bossname"></vs-list-item>
                    <vs-list-item icon-pack="feather" icon="icon-globe" :title="tr.sign"></vs-list-item>
                    <vs-list-item icon-pack="feather" icon="icon-file-text" :title="tr.TIN"></vs-list-item>
                    <vs-list-item icon-pack="feather" icon="icon-file-text" :title="tr.lisense"></vs-list-item>
                  </vs-list>
                </div>
              </div>
            </vs-popup>
          </div>
        </div>
      </component>
    </div>
  </div>
</vs-row>
</template>

<script>
export default {
  name: "companies",
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      // cForm:new Form({
      //   label,sign,bossname,logo
      // });
      showmodal: false,
      companies: [],
      company: [],
    }
  },
  computed: {
    scrollbarTag() {
      return this.$store.getters.scrollbarTag;
    },
  },
  components: {

  },
  created() {
    this.getCompanies();
  },
  methods: {
    showDetailModal(id) {
      this.axios.get('/api/companies/' + id)
        .then((response) => {
          this.company = response.data;
          this.showmodal = true;
          
          this.$Progress.set(100);
          // document.getElementById("loading-bg").style.display = "none";;
const appLoading = document.getElementById("loading-bg");
if (appLoading) {
  appLoading.style.display = "none";
}
        })
    },
    getCompanies() {
      this.axios.get('/api/companies')
        .then((response) => {
          this.companies = response.data;
          this.$Progress.set(100);
          // document.getElementById("loading-bg").style.display = "none";;
const appLoading = document.getElementById("loading-bg");
if (appLoading) {
  appLoading.style.display = "none";
}
        })
    },
    editCompany(row) {
      alert("edit triggered");
    },
    deleteCompany(id) {
      alert("delete clicked")
    }
  }
}
</script>
