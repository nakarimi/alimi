<template>
<div id="data-list-thumb-view" class="w-full data-list-container scrollable">
  <div v-if="!isloaded">
    <TableLoading></TableLoading>
  </div>
  <component v-if="activeEditForm" :is="scrollbarTag" class="scroll-area--data-list-add-new" :key="$vs.rtl">
    <div class="p-6 mb-base">
      <form data-vv-scope="fueStationAdForm">
        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="st_name" v-validate="'required'" label="نام" v-model="form.name" />
            <span class="absolute text-danger alerttext">{{ errors.first('fueStationAdForm.st_name') }}</span>
          </div>
        </div>

        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="st_manager" v-validate="'required'" label="مسؤل" v-model="form.manager" />
            <span class="absolute text-danger alerttext">{{ errors.first('fueStationAdForm.st_manager') }}</span>
          </div>
        </div>
        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="st_phone" v-validate="'required'" label="آدرس" v-model="form.phone" />
            <span class="absolute text-danger alerttext">{{ errors.first('fueStationAdForm.st_phone') }}</span>
          </div>
        </div>
        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="st_address" v-validate="'required'" label="شماره" v-model="form.address" />
            <span class="absolute text-danger alerttext">{{ errors.first('fueStationAdForm.st_address') }}</span>
          </div>
        </div>

        <div class="vx-row">
          <div class="vx-col w-full">
            <vs-button :disabled="form.busy" class="mr-3 mb-2" @click.stop="submitdata">آپدیت</vs-button>
            <vs-button color="warning" type="border" class="mb-2" @click.stop="activeEditForm = false">بستن</vs-button>
          </div>
        </div>
      </form>
    </div>
  </component>
  <vs-table v-if="isloaded" class="w-full" ref="table" pagination :max-items="itemsPerPage" :data="fuelstation">
    <template slot="thead">
      <vs-th>شماره</vs-th>
      <vs-th sort-key="name">نام</vs-th>
      <vs-th sort-key="name">مسول</vs-th>
      <vs-th sort-key="category"> شماره</vs-th>
      <vs-th sort-key="popularity"> آدرس</vs-th>
      <vs-th v-if="user_permissions.includes('fuel_edit')|| user_permissions.includes('fuel_remove')">تنظیمات</vs-th>
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
            <p class="product-category">{{ tr.manager }}</p>
          </vs-td>

          <vs-td>
            <p class="product-category">{{ tr.phone }}</p>
          </vs-td>

          <vs-td>
            <p class="product-category">{{ tr.address }}</p>
          </vs-td>

          <vs-td v-if="user_permissions.includes('fuel_edit')|| user_permissions.includes('fuel_remove')" class="whitespace-no-wrap notupfromall">

            <feather-icon v-if="user_permissions.includes('fuel_edit')" icon="EditIcon" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click="setEditData(tr)" />
            <feather-icon v-if="user_permissions.includes('fuel_remove')" icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2" @click.stop="deleteData(tr.id)" />
          </vs-td>
        </vs-tr>
      </tbody>
    </template>
  </vs-table>
</div>
</template>

<script>
import TableLoading from './../../../shared/TableLoading.vue'
export default {

  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      activeEditForm: false,
      fuelstation: [],
      isloaded: false,
      selected: [],
      // products: [],
      itemsPerPage: 4,
      isMounted: false,
      form: new Form({
        id: '',
        name: '',
        manager: '',
        phone: '',
        address: '',
      }),
      settings: {
        maxScrollbarLength: 60,
        wheelSpeed: .60
      },
    }
  },
  components: {
    TableLoading
  },
  created() {
    this.loadfuelstation();
  },

  computed: {

    currentPage() {
      if (this.isMounted) {
        return this.$refs.table.currentx
      }
      return 0
    },
    scrollbarTag() {
      return this.$store.getters.scrollbarTag
    },
    queriedItems() {
      return this.$refs.table ? this.$refs.table.queriedResults.length : this.products.length
    }
  },

  methods: {
    submitdata() {
      this.$validator.validateAll('fueStationAdForm').then(result => {
        swal.fire({
          title: 'آیا مطمئن هستید؟',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: 'rgb(54 34 119)',
          cancelButtonColor: 'rgb(229 83 85)',
          confirmButtonText: '<span>بله!</span>',
          cancelButtonText: '<span>خیر!</span>'
        }).then((result) => {
          if (result.isConfirmed) {
            this.form.patch(`/api/fuelstation/${this.form.id}`)
              .then(({
                data
              }) => {
                this.$vs.notify({
                  title: 'ذخیره آپدیت شد',
                  text: 'عملیه موفقانه انجام شد',
                  color: 'success',
                  iconPack: 'feather',
                  icon: 'icon-check',
                  position: 'top-right'
                })
                this.loadfuelstation();
                this.activeEditForm = false
              })
              .catch(() => {
                this.$vs.notify({
                  title: 'عملیه ناکام شد',
                  text: 'عملیه آپدیت تانگ تیل انجام نشد',
                  color: 'danger',
                  iconPack: 'feather',
                  icon: 'icon-check',
                  position: 'top-right'
                })
              })
          }
        })
      })

    },
    setEditData(data) {
      this.axios.get(`/api/fuelstation/${data.id}/edit`)
        .then((response) => {
          let d = response.data;
          this.form.id = d.id;
          this.form.name = d.name;
          this.form.manager = d.manager;
          this.form.phone = d.phone;
          this.form.address = d.address;
          this.activeEditForm = true;
        })

    },
    loadfuelstation() {
      this.axios.get('/api/fuelstation').then(({
        data
      }) => (this.fuelstation = data, this.isloaded = true));
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
          this.axios.delete('/api/fuelstation/' + id).then(() => {
            swal.fire(
              'حذف شد !',
              'موفقانه عملیه حذف انجام شد',
              'success'
            )
            this.loadfuelstation();
          }).catch(() => {
            swal.fire("Failed!", "سیستم قادر به حذف نیست دوباره تلاش نماید.", "warning");
          })
        }
      })
    },

  },

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
