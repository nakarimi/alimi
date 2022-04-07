<template>
<div>
  <Clients :isSidebarActive="addNewDataSidebar" @closeSidebar="toggleDataSidebar" @customEvent="getNewClient($event)" :data="sidebarData" />

  <vs-tabs :value="$route.query.tab">
    <vs-tab v-if="user_permissions.includes('contract_add')" label=" ثبت قرارداد جدید" style="padding:2px 0px 0px 0px !important;">
      <vx-card class="height-vh-80">
        <div class="header">
          <vs-row vs-w="12">
            <vs-col class="flex items-right justify-right project-label" vs-lg="3" vs-sm="6" vs-xs="12">
              <div class="">
                <h3 class="white-space-no-wrap pt-1 pr-5 mr-5 ml-4 w-full">
                  فورم ثبت پروژه قرار دادی
                </h3>
              </div>
            </vs-col>
            <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="7" vs-sm="3" vs-xs="1"></vs-col>
            <vs-col vs-type="flex" vs-justify="left" vs-align="left" vs-lg="2" vs-sm="3" vs-xs="12">
              <div class="w-full">
                <vs-button v-if="user_permissions.includes('client_add')"  type="filled" @click="addNewData" class="white-space-no-wrap" icon="add">ثبت نهاد جدید </vs-button>
              </div>
            </vs-col>
          </vs-row>
          <br>
          <hr>
        </div>
        <project-form :clients="clients" :newClient="newClient"></project-form>
      </vx-card>
    </vs-tab>
    <vs-tab v-if="user_permissions.includes('contract_view')" label=" لست قرار دادها">
      <div class="vx-row">
        <project-list></project-list>
      </div>
    </vs-tab>
  </vs-tabs>
</div>
</template>

<script>
import vSelect from "vue-select";
import Clients from "./proposals/Clients.vue";
import ProjectList from "./ProjectList.vue";
import ProjectForm from "./ProjectForm.vue";
import Ekmalat from "../shared/Ekmalat";
import {
  FormWizard,
  TabContent
} from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'

export default {
  components: {
    Clients,
    ProjectList,
    "v-select": vSelect,
    FormWizard,
    TabContent,
    Ekmalat,
    ProjectForm
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      // Data Sidebar
      addNewDataSidebar: false,
      sidebarData: {},
      newClient: [],
      statusFa: {
        on_hold: "درجریان",
        delivered: "تکمیل",
        canceled: "نا موفق",
      },
      clients: [],
      itemsPerPage: 4,
      isMounted: false,
      addNewDataSidebar: false,
      sidebarData: {},
    };
  },
  created() {
    this.getAllClients();
  },
  computed: {
    currentPage() {
      if (this.isMounted) {
        return this.$refs.table.currentx;
      }
      return 0;
    },
    queriedItems() {
      return this.$refs.table ?
        this.$refs.table.queriedResults.length :
        this.products.length;
    },
  },
  mounted() {
    this.isMounted = false;
    this.$validator.validate();
  },
  methods: {

    addNewData() {
      this.sidebarData = {};
      this.toggleDataSidebar(true);
    },
    toggleDataSidebar(val = false) {
      this.addNewDataSidebar = val;
      this.getAllClients();
    },
    // for Organs that implement the ad
    getAllClients() {
      this.axios.get('/api/clients')
        .then((response) => {
          this.clients = response.data;
        })
    },
    goTo(data) {
      this.$router
        .push({
          path: "/projects/project/${data.id}",
          name: "project-view",
          params: {
            id: data.id,
            dyTitle: data.name
          },
        })
        .catch(() => {document.getElementById("loading-bg").style.display = "none";});
    },
    viewProject(id) {
      // Vue.$forceUpdate();
      this.$router.push("/projects/project/" + id).catch(() => {document.getElementById("loading-bg").style.display = "none";});
    },
    // End Custom
    addNewData() {
      this.sidebarData = {};
      this.toggleDataSidebar(true);
    },
    deleteData(id) {
      this.$store.dispatch("dataList/removeItem", id).catch((err) => {
        console.error(err);
      });
    },
    editData(data) {
      // this.sidebarData = JSON.parse(JSON.stringify(this.blankData))
      this.sidebarData = data;
      this.toggleDataSidebar(true);
    },
    getNewClient(client) {
      // 
    },
  },
};
</script>

<style lang="scss">
#data-list-thumb-view {
  .vs-con-table {
    .product-name {
      max-width: 23rem;
    }

    .vs-table--header {
      display: flex;
      flex-wrap: wrap-reverse;
      margin-left: 1.5rem;
      margin-right: 1.5rem;

      >span {
        display: flex;
        flex-grow: 1;
      }

      .vs-table--search {
        padding-top: 0;

        .vs-table--search-input {
          padding: 0.9rem 2.5rem;
          font-size: 1rem;

          &+i {
            left: 1rem;
          }

          &:focus+i {
            left: 1rem;
          }
        }
      }
    }

    .vs-table {
      border-collapse: separate;
      border-spacing: 0 1.3rem;
      padding: 0 1rem;

      tr {
        box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);

        td {
          padding: 10px;

          &:first-child {
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
          }

          &:last-child {
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
          }

          &.img-container {

            // width: 1rem;
            // background: #fff;
            span {
              display: flex;
              justify-content: flex-start;
            }

            .product-img {
              height: 110px;
            }
          }
        }

        td.td-check {
          padding: 20px !important;
        }
      }
    }

    .vs-table--thead {
      th {
        padding-top: 0;
        padding-bottom: 0;

        .vs-table-text {
          text-transform: uppercase;
          font-weight: 600;
        }
      }

      th.td-check {
        padding: 0 15px !important;
      }

      tr {
        background: none;
        box-shadow: none;
      }
    }

    .vs-table--pagination {
      justify-content: center;
    }
  }
}
</style>
