<template>
<div id="data-list-list-view" class="data-list-container">
  <div v-if="!isdata">
    <TableLoading></TableLoading>
  </div>
  <vs-table v-if="isdata" ref="table" pagination :max-items="itemsPerPage" search :data="allTransaction">
    <div slot="header" class="flex flex-wrap-reverse items-center flex-grow justify-between">
      <div class="flex flex-wrap-reverse items-center data-list-btn-container">
        <vs-dropdown vs-trigger-click class="cursor-pointer mb-4 mr-4 items-per-page-handler float-right">
          <div class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
            <span class="mr-2">{{ currentPage * itemsPerPage - (itemsPerPage - 1) }} -
              {{
                  allTransaction.length - currentPage * itemsPerPage > 0
                    ? currentPage * itemsPerPage
                    : allTransaction.length
                }}
              of {{ queriedItems }}</span>
            <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
          </div>
          <!-- <vs-button class="btn-drop" type="line" color="primary" icon-pack="feather" icon="icon-chevron-down"></vs-button> -->
          <vs-dropdown-menu>
            <vs-dropdown-item @click="itemsPerPage = 4">
              <span>4</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage = 10">
              <span>10</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage = 15">
              <span>15</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage = 20">
              <span>20</span>
            </vs-dropdown-item>
          </vs-dropdown-menu>
        </vs-dropdown>
      </div>
    </div>

    <template slot="thead">
      <vs-th sort-key="name">شماره</vs-th>
      <vs-th sort-key="category">سریال نمبر</vs-th>
      <vs-th sort-key="popularity">عنوان</vs-th>
      <vs-th sort-key="order_status">حالت معامله</vs-th>
      <vs-th sort-key="price">مقدار</vs-th>
      <vs-th sort-key="price">واحد پولی</vs-th>
      <vs-th sort-key="price">کاربر ثبت</vs-th>
      <vs-th sort-key="price">توضیحات</vs-th>
      <vs-th v-if="user_permissions.includes('account_edit') || user_permissions.includes('account_remove')">تنظیمات</vs-th>
    </template>

    <template slot-scope="{ data }">
      <tbody v-if="data">
        <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
          <vs-td>
            <p @click.stop="viewData(tr)" class="cursor-pointer product-name font-medium truncate">{{ indextr+1 }}</p>
          </vs-td>
          <vs-td>
            <p @click.stop="viewData(tr)" class="cursor-pointer product-name font-medium truncate">{{'TRA-'+ tr.serial_no }}</p>
          </vs-td>
          <vs-td>
            <p @click.stop="viewData(tr)" class="cursor-pointer product-category">{{ tr.title }}</p>
          </vs-td>
          <vs-td>
            <vs-chip :color="getOrderStatusColorr(tr.transaction_status)" class="product-order-status">{{ tr.transaction_status }}</vs-chip>
          </vs-td>
          <vs-td>
            <vs-chip :color="getOrderStatusColor(tr.ammount)" class="product-order-status">{{ (tr.ammount) | NumThreeDigit }}</vs-chip>
          </vs-td>
          <vs-td>
            <p class="product-price">{{ (tr.currency) ? tr.currency.sign_fa : 'AFN'}}</p>
          </vs-td>
          <vs-td>
            <p class="product-price">{{ tr.user.firstName }} {{ tr.user.lastName }}</p>
          </vs-td>
          <vs-td>
            <p class="product-price">{{ tr.description }}</p>
          </vs-td>
          <vs-td class="whitespace-no-wrap">
            <feather-icon v-if="user_permissions.includes('transaction_steps')" icon="CheckSquareIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current cursor-pointer" class="ml-2" @click.stop="showStepsModal(tr.id)" />&nbsp;&nbsp;
            <feather-icon v-if="user_permissions.includes('transaction_edit')" icon="EditIcon" svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer" @click.stop="$router.push({path: '/transactions/edit/' + tr.id, params: {item: tr }}).catch(() => {})" />
            <feather-icon v-if="user_permissions.includes('transaction_remove')" icon="TrashIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current cursor-pointer" class="ml-2" @click.stop="deleteData(tr.id)" />
          </vs-td>
        </vs-tr>
      </tbody>
    </template>
  </vs-table>
  <vs-popup class="holamundo" title=" تنظیمات مربوط به معاملات تجارتی " :active.sync="popupStepActive">
    <TransactionStep @closesteps="closeModel" ref="wizardTrans" :data="transaction"></TransactionStep>
  </vs-popup>
  <vs-popup class="holamundo" title="معلومات معاملات تجارتی" :active.sync="transactionModalActive">
    <transaction-view :data="transaction_to_view" />
  </vs-popup>
</div>
</template>

<script>
//import DataViewSidebar from '../../../DataViewSidebar.vue'
import TableLoading from './../shared/TableLoading.vue'
import TransactionView from './TransactionView';
import TransactionStep from './TransactionStep.vue'

export default {
  components: {
    TransactionView,
    TableLoading,
    TransactionStep
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      transaction: [],
      popupStepActive: false,
      isdata: false,
      allTransaction: [],
      itemsPerPage: 10,
      isMounted: false,
      transaction_to_view: null,
      transactionModalActive: false,
    };
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
        this.allTransaction.length;
    },
  },
  methods: {
    closeModel() {
      this.popupStepActive = false;
    },
    getTransaction(id) {
      this.$Progress.start()
      this.axios
        .get("/api/transaction/" + id)
        .then((data) => {
          let transaction = data.data;
          this.$refs.wizardTrans.transaction = data.data;
          this.$refs.wizardTrans.transaction_id = transaction.id;
          this.$refs.wizardTrans.transForm.step = transaction.step;
          this.$refs.wizardTrans.transForm.is_approved = transaction.fr.valid;
          this.$refs.wizardTrans.current = transaction.step - 1;
          this.$Progress.set(100);
          this.popupStepActive = true;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    showStepsModal(id) {
      this.getTransaction(id);
    },
    loadTransaction() {
      this.axios.get('/api/transaction').then(({
          data
        }) => (this.allTransaction = data,
          this.isdata = true))
        .catch(() => {
          this.$vs.notify({
            title: 'معلومات بارگیری نشد !',
            text: 'عملیه بارگیری معلومات نام شد',
            color: 'danger',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
        });
    },
    viewData(transaction) {
      this.$Progress.start()
      this.axios
        .get("/api/transaction/" + transaction.id)
        .then((resp) => {
          this.$refs.wizardTrans.transaction = resp.data;
          this.transactionModalActive = true;
          console.log('this.$refs.wizardTrans.transaction', this.$refs.wizardTrans.transaction);
          this.$Progress.set(100);
        })
        .catch(() => {
          document.getElementById("loading-bg").style.display = "none";
        });

    },
    deleteData(id) {
      swal.fire({
        title: 'آیا شما مطمئن هستید ؟',
        text: "شما قادر به برگردادن این عملیه پس از حذف نمی باشید !",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#362277',
        cancelButtonColor: '#e85454',
        confirmButtonText: 'بلی مطمئن هستم',
        cancelButtonText: 'خیر'
      }).then((result) => {
        if (result.value) {
          this.axios.delete('/api/transaction/' + id).then(() => {
            swal.fire(
              'حذف شد !',
              'موفقانه عملیه حذف انجام شد',
              'success'
            )
            this.loadTransaction();
          }).catch(() => {
            swal.fire("Failed!", "سیستم قادر به حذف نیست دوباره تلاش نماید.", "warning");
          })
        }
      })
    },

    editData(data) {
      this.sidebarData = data;
      this.toggleDataSidebar(true);
    },
    getOrderStatusColor(status) {
      return "success";
    },

    getOrderStatusColorr() {
      return "primary";
    }

  },
  created() {
    this.loadTransaction();
  },
  mounted() {
    this.isMounted = false
  },
};
</script>

<style lang="scss">
#data-list-list-view {
  .vs-con-table {

    /*
      Below media-queries is fix for responsiveness of action buttons
      Note: If you change action buttons or layout of this page, Please remove below style
    */
    @media (max-width: 689px) {
      .vs-table--search {
        margin-left: 0;
        max-width: unset;
        width: 100%;

        .vs-table--search-input {
          width: 100%;
        }
      }
    }

    @media (max-width: 461px) {
      .items-per-page-handler {
        display: none;
      }
    }

    @media (max-width: 341px) {
      .data-list-btn-container {
        width: 100%;

        .dd-actions,
        .btn-add-new {
          width: 100%;
          margin-right: 0 !important;
        }
      }
    }

    .product-name {
      max-width: 23rem;
    }

    .vs-table--header {
      display: flex;
      flex-wrap: wrap;
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
