<template>
<div>
  <Clients :isSidebarActive="addNewDataSidebar" @closeSidebar="toggleDataSidebar" :data="sidebarData" />
  <!--<vs-tabs :value="$route.query.tab">
    <vs-tab label="ثبت اعلان" style="padding:2px 0px 0px 0px !important;">-->
  <vx-card>
    <div class="header">
      <vs-row vs-w="12">
        <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="2" vs-sm="4" vs-xs="4">
          <div class="">
            <h3 class="pt-1 pr-5 mr-5 ml-4 w-full">
              فورم ثبت اعلانات
            </h3>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="8" vs-sm="4" vs-xs="1"></vs-col>
        <vs-col vs-type="flex" vs-justify="left" vs-align="left" vs-lg="2" vs-sm="4" vs-xs="7">
          <div class="w-full">
            <vs-button v-if="user_permissions.includes('client_add')" type="filled" @click="addNewData" class="white-space-no-wrap"  icon="add">ثبت نهاد جدید </vs-button>
          </div>
        </vs-col>
      </vs-row>
      <br>
      <hr>
    </div>
    <add-proposal :clients="clients"></add-proposal>
  </vx-card>
  <!--</vs-tab>
  </vs-tabs>-->
</div>
</template>

<script>
import AddProposal from './AddProposal.vue'
import Clients from './Clients.vue'

export default {
  components: {
    AddProposal,
    Clients,
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      // Data Sidebar
      addNewDataSidebar: false,
      sidebarData: {},
      edit_id: 0,
      clients: [],
    }
  },
  computed: {
    isFormValid() {
      return Object.keys(this.fields).some(key => this.fields[key].validated) && Object.keys(this.fields).some(key => this.fields[key].valid);
    }
  },
  mounted() {
    this.isMounted = false;
    this.$validator.validate();
  },
  created() {
    this.getAllClients();
  },
  methods: {
    getAllClients() {
      this.axios.get('/api/clients')
        .then((response) => {
          this.clients = response.data;
        })
    },
    addNewData() {
      this.sidebarData = {}
      this.toggleDataSidebar(true)
    },

    toggleDataSidebar(val = false) {
      this.addNewDataSidebar = val
      this.getAllClients();
    }

  },
}
</script>

<style scoped>
.vs-icon {
  color: inherit;
  text-align: center;
  font-size: 2rem;
}
</style>
