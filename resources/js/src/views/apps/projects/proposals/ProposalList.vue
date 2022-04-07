<template>
<div>
  <vs-col v-if="isdata" class="pr-5 m-4 proposaladdbtn" vs-lg="2" vs-sm="3" vs-xs="12">
    <router-link v-if="user_permissions.includes('proposal_add')" to="/projects/proposal">
      <vs-button icon-pack="feather" icon="icon-chevrons-left" icon-after class="shadow-md lg:w-full lg:mt-0 lg:mt-4">ثبت اعلان جدید</vs-button>
    </router-link>
  </vs-col>
  <div id="data-list-thumb-view" class="flex w-full data-list-container">
    <div v-if="!isdata">
      <TableLoading></TableLoading>
    </div>
    <vs-table v-if="isdata" class="proposaltable w-full" ref="table" pagination :max-items="itemsPerPage" search :data="proposals">
      <div slot="header" class="flex flex-wrap-reverse items-center flex-grow justify-between" id="proposalTable">
        <!-- ITEMS PER PAGE -->
        <vs-dropdown vs-trigger-click class="cursor-pointer mb-4 mr-4">
          <div class="px-4 py-2 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
            <span class="mr-2">{{ currentPage * itemsPerPage - (itemsPerPage - 1) }} - {{ proposals.length - currentPage * itemsPerPage > 0 ? currentPage * itemsPerPage : proposals.length }} از {{ queriedItems }}</span>
            <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
          </div>
          <vs-dropdown-menu>
            <vs-dropdown-item @click="itemsPerPage=4">
              <span>۴</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage=10">
              <span>۱۰</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage=15">
              <span>۱۵</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage=20">
              <span>۲۰</span>
            </vs-dropdown-item>
          </vs-dropdown-menu>
        </vs-dropdown>
      </div>
      <template slot="thead">
        <vs-th>نمبر</vs-th>
        <vs-th>نهاد</vs-th>
        <vs-th sort-key="product-name">پروژه</vs-th>
        <vs-th sort-key="offer_guarantee">تاریخ ختم اعلان</vs-th>
        <vs-th sort-key="status">وضعیت</vs-th>
        <!--<vs-th sort-key="price">قیمت</vs-th>
      <vs-th sort-key="bidding_address">آدرس</vs-th>
      <vs-th sort-key="bidding_date">تاریخ پیشنهاد</vs-th>-->
        <vs-th>تنظیمات</vs-th>
      </template>
      <template slot-scope="{data}">
        <tbody>
          <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
            <vs-td class="pl-2 text-center">
              {{ indextr + 1 }}
            </vs-td>
            <vs-td class="img-container">
              <p v-if="tr.pro_data" class="product-name font-medium truncate">
                <!-- <img :src="tr.img" class="product-img" /> -->
                <span @click.stop="$router.push({ path: `/proposal/${tr.id}` })">{{ findClient(tr.pro_data.client_id) }}</span>
              </p>
            </vs-td>

            <vs-td>
              <div v-if="tr.pro_data">
                <p class="product-name font-medium truncate" @click.stop="$router.push({ path: `/proposal/${tr.id}` })">
                  {{ tr.pro_data.title }}</p>
              </div>
            </vs-td>

            <vs-td>
              <!-- <vs-progress :percent="Number(tr.popularity)" :color="getPopularityColor(Number(tr.popularity))" class="shadow-md" />-->
              <p class="offer_guarantee">{{ tr.submission_date }} </p>
            </vs-td>

            <vs-td>
              <vs-chip :color="getOrderStatusColor(tr.status)" class="product-order-status">{{ statusFa[tr.status] }}</vs-chip>
            </vs-td>
            <!--
          <vs-td>
            <p v-if="tr.pro_data" class="product-price">{{ tr.pro_data.total_price }} افغانی</p>
          </vs-td>
          <vs-td>
            <p class="bidding_address">{{ tr.bidding_address }}</p>
          </vs-td>
          <vs-td>
            <p class="bidding_date">{{ tr.bidding_date }}</p>
          </vs-td>-->
            <vs-td class="whitespace-no-wrap notupfromall">
              <feather-icon v-if="user_permissions.includes('proposal_steps')" icon="CheckSquareIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current" class="ml-2" @click.stop="showCheckModal(tr.id)" />
              <feather-icon v-if="user_permissions.includes('proposal_view')" icon="PrinterIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current" class="ml-2" @click.stop="showPrintData(tr.id)" />
              <feather-icon v-if="user_permissions.includes('proposal_edit')" icon="EditIcon" svgClasses="w-6 h-6 hover:text-primary stroke-current" @click.stop="$router.push({path: `/projects/proposal/${tr.id}/edit`})" />
              <feather-icon v-if="user_permissions.includes('proposal_remove')" icon="TrashIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current" class="ml-2" @click.stop="deleteData(tr.id)" />
            </vs-td>
          </vs-tr>
        </tbody>
      </template>
    </vs-table>
    <vs-popup class="holamundo" title="پیشرفت آفر/ اعلان" :active.sync="popupModalActive">
      <ProposalSteps @closesteps="closeModel" ref="wizardModal1" :participators="participators" :proposal="proposal"></ProposalSteps>
    </vs-popup>
    <vs-popup fullscreen title="fullscreen" :active.sync="popupActive">
      <vs-button size="small" type="gradient" icon="print" id="printBTN" @click="printProposal">چاپ</vs-button>
      <!-- <vue-easy-print tableShow ref="easyPrint"> -->
      <!-- <ProposalStepsTable :proposals="proposals"></ProposalStepsTable> -->
      <!-- </vue-easy-print> -->
    </vs-popup>
  </div>
</div>
</template>

<script>
import TableLoading from './../../shared/TableLoading.vue'
import DataViewSidebar from './../DataViewSidebar.vue'
import vueEasyPrint from "vue-easy-print";
import ProposalSteps from "./ProposalSteps.vue"
import ProposalStepsTable from "./ProposalStepsTable.vue"

export default {
  name: 'vx-proposal-list',
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      isdata: false,
      popupModalActive: false,
      popupActive: false,
      statusFa: {
        normal: 'درجریان',
        delivered: 'تکمیل',
        canceled: 'نا موفق',
        income: 'تاخیر'
      },
      selected: [],
      proposals: [],
      proposal: null,
      participators: [],
      proposalstep: [],
      proposalsteps: new Form({
        step: 0,
        proposal_id: 0,
        res_person: '',
        is_recieved_cont: 0,
        is_participated: 0,
        prop_recieved_or_allow: 0,
        winner: ''
      }),
      itemsPerPage: 4,
      isMounted: false,
      addNewDataSidebar: false,
      sidebarData: {},
      clients: [],
    }
  },
  components: {
    ProposalSteps,
    TableLoading,
    DataViewSidebar,
    vueEasyPrint,
    ProposalStepsTable
  },
  created() {
    this.getProposals();
    this.getAllClients();
  },
  computed: {
    currentPage() {
      if (this.isMounted) {
        return this.$refs.table.currentx
      }
      return 1
    },
    queriedItems() {
      return this.$refs.table ? this.$refs.table.queriedResults.length : this.proposals.length
    }
  },
  methods: {
    closeModel() {
      this.popupModalActive = false;
    },
    async getProposlStep(id) {
      await this.$refs.wizardModal1.setItemsList(this.proposal);
      return new Promise(resolve => {
        this.axios.get('/api/proposalstep/' + id)
          .then((response) => {
            this.proposalstep = response.data;
            if (response.data.status == 'no') {
              this.$refs.wizardModal1.setWizardStep1(1, this.proposalsteps);
            } else {
              this.proposalstep.forEach(item => {
                this.$refs.wizardModal1.setWizardStep1(item.step, item);
              })
            }
            this.$Progress.set(100);
            resolve('resolved');
          }).catch(() => {
            resolve('rejected');
          });
      })
    },
    getProposal(id) {
      return new Promise(resolve => {
        this.axios
          .get("/api/proposal/" + id)
          .then((response) => {
            this.proposal = [];
            this.proposal = response.data;
            resolve('resolved');
          })
          .catch(() => {
            document.getElementById("loading-bg").style.display = "none";
            resolve('rejected');
          });
      })
    },
    getParticipators(proposalid) {
      return new Promise(resolve => {
        this.axios
          .get("/api/participators/" + proposalid)
          .then((response) => {
            this.participators = response.data;
            resolve('resolved');
          })
          .catch(() => {
            this.participators = [];
            resolve('rejected');
          });
      })
    },
    async showCheckModal(id, open = true) {
      this.proposalstep = [];
      await this.getProposal(id);
      await this.getParticipators(id);
      await this.getProposlStep(id);
      if (open) {
        this.popupModalActive = true;
      }

    },
    showPrintData(id) {
      window.open(`/proposal/print?pid=${id}&name=${localStorage.getItem("name")} ${localStorage.getItem("lastname")}`, '_blank');
    },
    getAllClients() {
      this.axios.get('/api/clients')
        .then((response) => {
          this.clients = response.data;
          document.getElementById("loading-bg").style.display = "none";
        })
    },
    findClient(id) {
      let name = '';
      Object.keys(this.clients).some(key => (this.clients[key].id == id) ? name = this.clients[key].name : null);
      return name;
    },
    getProposals() {
      this.$Progress.start()
      this.axios.get('/api/proposal')
        .then((response) => {
          this.proposals = response.data;
          this.isdata = true;
          this.showCheckModal(this.proposals[0].id, open = false);
          this.$Progress.set(100)
          document.getElementById("loading-bg").style.display = "none";
        })
    },
    // Start Custom
    goTo(data) {
      this.$router.push({
        path: '/projects/project/${data.id}',
        name: 'project-view',
        params: {
          id: data.id,
          dyTitle: data.name
        },
      }).catch(() => {})
    },
    // End Custom
    addNewData() {
      this.sidebarData = {}
      this.toggleDataSidebar(true)
    },
    deleteData(id) {
      swal.fire({
        title: 'آیا  مطمئن هستید؟',
        text: "پیشنهاد حذف خواهد شد",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'rgb(54 34 119)',
        cancelButtonColor: 'rgb(229 83 85)',
        confirmButtonText: '<span>بله، حذف شود!</span>',
        cancelButtonText: '<span>خیر، لغو عملیه!</span>'
      }).then((result) => {
        if (result.isConfirmed) {
          this.axios.delete('/api/proposal/' + id).then((id) => {
              swal.fire({
                title: 'عملیه موفقانه انجام شد.',
                text: "پیشنهاد از سیستم پاک شد!",
                icon: 'success',
              })
              this.getProposals();
            })
            .catch(() => {
              swal.fire(
                'ناموفق !',
                'سیستم قادر به حذف نیست. وابستگی های آیتم را بررسی نمایید!',
                'error'
              )
              document.getElementById("loading-bg").style.display = "none";
            });
        }
      })
    },
    editData(data) {
      // 
      // this.sidebarData = JSON.parse(JSON.stringify(this.blankData))
      // this.sidebarData = data
      // this.toggleDataSidebar(true)
    },
    getOrderStatusColor(status) {
      if (status === 'normal') return 'success'
      // if (status === 'delivered') return 'success'
      // if (status === 'canceled') return 'danger'
      return 'primary'
    },
    getPopularityColor(num) {
      if (num > 90) return 'success'
      if (num > 70) return 'primary'
      if (num >= 50) return 'warning'
      if (num < 50) return 'danger'
      return 'primary'
    },
    toggleDataSidebar(val = false) {
      this.addNewDataSidebar = val
    },
    printProposal() {
      // this.$htmlToPaper('proposalTable');
      this.$refs.easyPrint.print()
    },
  },
  mounted() {
    this.isMounted = false
  }
}
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
