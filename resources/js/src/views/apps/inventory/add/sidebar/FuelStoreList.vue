<template>
<div id="data-list-thumb-view" class="w-full data-list-container">
  <vs-table class="w-full" ref="table" pagination :max-items="itemsPerPage" :data="fuelstorage">
    <template slot="thead">
      <vs-th sort-key="name">شماره</vs-th>
      <vs-th sort-key="name">نام</vs-th>
      <vs-th sort-key="popularity">تانک استیشن</vs-th>
      <vs-th sort-key="popularity">مسول</vs-th>
      <vs-th sort-key="category">ظرقیت</vs-th>
      <vs-th sort-key="order_status">واحد </vs-th>
      <vs-th v-if="user_permissions.includes('fuel_edit')|| user_permissions.includes('fuel_remove')">تنظیمات</vs-th>

    </template>

    <template slot-scope="{data}">
      <tbody>
        <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
          <vs-td>
            <p class="product-category">{{ indextr+1 }}</p>
          </vs-td>
          <vs-td>
            <p class="product-category">{{ tr.name }}</p>
          </vs-td>

          <vs-td>
            <p class="product-category">{{ tr.fuel_station.name }}</p>
          </vs-td>

          <vs-td>
            <p class="product-category">{{ tr.supervisor }}</p>
          </vs-td>

          <vs-td>
            <p class="product-category">{{ tr.capacity | NumThreeDigit }}</p>
          </vs-td>

          <vs-td>
            <p class="product-category">{{ tr.measur_unit.title }}</p>
          </vs-td>

          <vs-td v-if="user_permissions.includes('fuel_edit')|| user_permissions.includes('fuel_remove')" class="whitespace-no-wrap notupfromall">
            <feather-icon v-if="user_permissions.includes('fuel_edit')" icon="EditIcon" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click.stop="$emit('editStore', tr)"/>
            <feather-icon v-if="user_permissions.includes('fuel_remove')" icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2" @click.stop="deleteData(tr.id)" />
          </vs-td>
        </vs-tr>
      </tbody>
    </template>
  </vs-table>
</div>
</template>

<script>
export default {
  // name: 'project-list',
  props: ['fuelstorage'],
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      isdata: false,
      // fuelstorage: [],
      statusFa: {
        on_hold: 'مدیر',
        delivered: 'ادمین',
        canceled: 'سوپروایزر',
      },
      selected: [],
      // products: [],
      itemsPerPage: 4,
      isMounted: false,
      addNewDataSidebar: false,
      sidebarData: {},
    }
  },
  components: {},
  created() {

  },
  computed: {
    currentPage() {
      if (this.isMounted) {
        return this.$refs.table.currentx
      }
      return 0
    },
    queriedItems() {
      return this.$refs.table ? this.$refs.table.queriedResults.length : this.fuelstorage.length
    }
  },
  created() {

  },
  methods: {
    // Start Custom
    goTo(data) {
      this.$router.push({
        path: '/projects/project/${data.id}',
        params: {
          id: data.id,
          dyTitle: data.name
        },
      }).catch(() => {})
    },
    viewProject(id) {
      this.$router.push('/projects/project/' + id).catch(() => {})
    },
    // End Custom
    addNewData() {
      this.sidebarData = {}
      this.toggleDataSidebar(true)
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
          this.axios.delete('/api/fuelstorestation/' + id).then(() => {
            swal.fire(
              'حذف شد !',
              'موفقانه عملیه حذف انجام شد',
              'success'
            )
            this.loadfullstorage();
          }).catch(() => {
            this.$vs.notify({
              title: 'ناموفق!',
              text: ' سیستم قادر به حذف نمیباشد!',
              color: 'danger',
              iconPack: 'feather',
              icon: 'icon-alert-triangle',
              position: 'top-right'
            })
          })
        }
      })
    },
    getOrderStatusColor(status) {
      if (status === 'on_hold') return 'warning'
      if (status === 'delivered') return 'success'
      if (status === 'canceled') return 'danger'
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
  },
  mounted() {
    this.isMounted = true
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
