<template>
<div id="data-list-thumb-view" class="w-full data-list-container">
  <div v-if="!isdata">
    <TableLoading></TableLoading>
  </div>
  <vs-table v-if="isdata" class="w-full" ref="table" pagination :max-items="itemsPerPage" search :data="item">
    <div slot="header" class="flex flex-wrap-reverse items-center flex-grow justify-between">
      <vs-dropdown vs-trigger-click class="cursor-pointer mb-4 mr-4">
        <div class="pl-4 pr-4 pt-1 pb-1 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
          <span class="mr-2">{{ currentPage * itemsPerPage - (itemsPerPage - 1) }} تا {{ products.length - currentPage * itemsPerPage > 0 ? currentPage * itemsPerPage : products.length }} از {{ queriedItems }}</span>
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
      <vs-th>شماره</vs-th>
      <vs-th sort-key="name">نام</vs-th>
      <vs-th sort-key="name">نوعیت</vs-th>
      <vs-th sort-key="category">واحد اصلی</vs-th>
      <vs-th sort-key="popularity">واحد فرعی</vs-th>
      <vs-th sort-key="order_status">مقدار معادل</vs-th>
      <vs-th sort-key="price">تفصیلات</vs-th>
      <vs-th v-if="user_permissions.includes('product_edit') || user_permissions.includes('product_remove')">تنظیمات</vs-th>
    </template>

    <template slot-scope="{data}">
      <tbody>
        <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
          <vs-td class="img-container">
            {{ indextr+1 }}
          </vs-td>
          <vs-td>
            <p class="product-category">{{ tr.name }}</p>
          </vs-td>
          <vs-td>
            <p class="product-category">{{ tr.type.type }}</p>
          </vs-td>
          <vs-td>
            <p class="product-category">{{ tr.measurment_unites_min.title }}</p>
          </vs-td>
          <vs-td>
            <p class="product-category">{{ tr.measurment_unites_sub.title }}</p>
          </vs-td>
          <vs-td>
            <p class="product-category">{{ tr.equivalent }}</p>
          </vs-td>
          <vs-td>
            <p class="product-category">{{ tr.description }}</p>
          </vs-td>

          <vs-td v-if="user_permissions.includes('product_edit') || user_permissions.includes('product_remove')" class="whitespace-no-wrap notupfromall">
            <feather-icon v-if="user_permissions.includes('product_edit')" icon="EditIcon" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click.stop="$emit('openGoodModal', tr)"/>
            <feather-icon v-if="user_permissions.includes('product_remove')" icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2" @click.stop="deleteData(tr.id)" />
          </vs-td>
        </vs-tr>
      </tbody>
    </template>
  </vs-table>
</div>
</template>

<script>
import TableLoading from './../../shared/TableLoading.vue'
export default {

  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      isdata: false,
      item: [],
      // products: [],
      itemsPerPage: 4,
      isMounted: false,

    }
  },

  created() {
    this.loaditem();

  },
  components: {
    TableLoading
  },
  computed: {

    currentPage() {
      if (this.isMounted) {
        return this.$refs.table.currentx
      }
      return 0
    },
    products() {
      return []
    },
    queriedItems() {
      return this.$refs.table ? this.$refs.table.queriedResults.length : this.products.length
    }
  },

  methods: {

    loaditem() {
      this.axios.get('/api/item').then(({
        data
      }) => (
        this.item = data,
        this.isdata = true,
        document.getElementById("loading-bg").style.display = "none"
        ));
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
          this.axios.delete('/api/item/' + id).then(() => {
            swal.fire(
              'حذف شد !',
              'موفقانه عملیه حذف انجام شد',
              'success'
            )
            this.loaditem();
          }).catch(() => {
            swal.fire("Failed!", "سیستم قادر به حذف نیست دوباره تلاش نماید.", "error");
          })
        }
      })
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
