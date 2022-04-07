<template>
<div id="data-list-thumb-view" class="w-full data-list-container">
  <vs-table class="w-full" ref="table" :data="projects">
    <div slot="header" class="flex flex-wrap-reverse items-center flex-grow justify-between">
      <h4 class="projectListDashboard">لست آخرین پروژه ها</h4>
    </div>
    <template slot="thead">
      <vs-th>#</vs-th>
      <vs-th>نهاد</vs-th>
      <vs-th>قرارداد</vs-th>
      <vs-th>تضمین حسن اجرا</vs-th>
      <vs-th>ارزش قرارداد</vs-th>
      <vs-th>قیمت</vs-th>
      <vs-th>تاریخ ختم</vs-th>
      <vs-th>تاریخ قرارداد</vs-th>
      <!--<vs-th>تنظیمات</vs-th> -->
    </template>

    <template slot-scope="{data}">
      <tbody>
        <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
          <vs-td class="pl-2 text-center">
            {{ indextr + 1 }}
          </vs-td>
          <vs-td>
            <div v-if="tr.pro_data" class="font-medium truncate">
              <!-- <img :src="tr.img" class="product-img" /> -->
              <p>{{ findClient(tr.pro_data.client_id) }}</p>
            </div>
          </vs-td>
          <vs-td>
            <div v-if="tr.pro_data" class="font-medium truncate">
              <p> {{ tr.pro_data.title }}</p>

            </div>
          </vs-td>
          <vs-td>
            <!-- <vs-progress :percent="Number(tr.popularity)" :color="getPopularityColor(Number(tr.popularity))" class="shadow-md" /> -->
            <p>{{ formatToEnPriceSimple(tr.project_guarantee) }} افغانی</p>
          </vs-td>
          <vs-td>
            <p v-if="tr.pro_data">{{ formatToEnPriceSimple(tr.pro_data.pr_worth) }}</p>
          </vs-td>
          <vs-td>
            <p v-if="tr.pro_data">{{ formatToEnPriceSimple(tr.pro_data.total_total_price) }} افغانی</p>
          </vs-td>
          <vs-td>
            <p>{{ tr.contract_end_date }}</p>
          </vs-td>
          <vs-td>
            <p>{{ tr.contract_date }}</p>
          </vs-td>
       </vs-tr>
      </tbody>
    </template>
  </vs-table>
</div>
</template>

<script>
export default {
  name: "vx-project-list",
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      projects: [],
      clients: [],
    };
  },
  components: {},
  created() {
    this.getLatestProject();
    this.getAllClients();
  },
  computed: {

  },
  methods: {
    findClient(id) {
      let name = '';
      Object.keys(this.clients).some(key => (this.clients[key].id == id) ? name = this.clients[key].name : null);
      return name;
    },
    // for Organs that implement the ad
    getAllClients() {
      this.axios.get('/api/clients')
        .then((response) => {
          this.clients = response.data;
        })
    },
    getLatestProject() {
      // Start the Progress Bar
      this.$Progress.start()
      this.axios.get('/api/lastest-projects').then((response) => {
          this.projects = response.data;
        // 
          // Finish the Progress Bar
          this.$Progress.set(100)
        })
        .catch(() => {document.getElementById("loading-bg").style.display = "none";});
    },

    addNewData() {
      this.sidebarData = {};
      this.toggleDataSidebar(true);
    },
  },
  mounted() {
    this.isMounted = false;
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
          padding: 20px;

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
