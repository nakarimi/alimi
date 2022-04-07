<!-- =========================================================================================
  File Name: AddNewDataSidebar.vue
  Description: Add New Data - Sidebar component
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
<vs-sidebar position-right parent="body" default-index="1" color="primary" class="add-new-data-sidebar items-no-padding" spacer v-model="isSidebarActiveLocal">
  <div class="mt-6 flex items-center justify-between px-6 float-right">
    <!-- <vs-button type="border" icon-pack="feather-icon" icon="close" color="warning" class="mr-6" @click.stop="isSidebarActiveLocal = false">بستن فورم</vs-button>-->
    <feather-icon icon="XIcon" @click.stop="isSidebarActiveLocal = false" class="cursor-pointer"></feather-icon>
  </div>
  <vs-tabs>
    <vs-tab v-if="user_permissions.includes('vendor_view')" label="لست فروشنده گان" icon="list" class="leftScrol">
      <div class="scroll-area--data-list-add-new" :key="$vs.rtl" v-if="sellerActiveForm">
        <form>
          <vs-divider>
            <h4>
              ویرایش معلومات فروشنده
            </h4>
          </vs-divider>
          <div class="pt-6 pr-6 pl-6">
            <form>
              <vs-input label="نام فروشنده" class="mt-5 w-full" name="item-name" v-validate="'required'" v-model="formEdit.name" />
              <has-error :form="formEdit" field="name"></has-error>
              <vs-input label="شماره تماس" class="mt-5 w-full" name="item-phone" v-model="formEdit.phone" />
              <has-error :form="formEdit" field="phone"></has-error>
              <vs-input label="آدرس" class="mt-5 w-full" name="item-address" v-model="formEdit.address" />
              <has-error :form="formEdit" field="address"></has-error>
              <div class="flex flex-wrap items-center p-2 mt-3" slot="footer">
                <vs-button v-if="user_permissions.includes('vendor_edit')" type="border" icon="edit" color="success" class="mr-6" @click="updateData()">ویرایش</vs-button>
                <vs-button type="border" icon="close" color="danger" @click="resetAllState()">بستن فورم ویرایش</vs-button>
              </div>
            </form>
          </div>
          <vs-divider>---</vs-divider>
        </form>
      </div>
      <div id="data-list-thumb-view" class="w-full data-list-container">
        <div v-if="!isdata">
          <TableLoading></TableLoading>
        </div>
        <vs-table v-if="isdata" class="w-full" ref="table" id="clientList" pagination :max-items="9" :data="sellers">
          <template slot="thead">
            <vs-th>نام فروشنده</vs-th>
            <vs-th>شماره تماس</vs-th>
            <vs-th>آدرس</vs-th>
            <vs-th>بررسی</vs-th>
          </template>
          <template slot-scope="{data}">
            <tbody>
              <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                <vs-td>
                  <span v-text="tr.name" class="cursor-pointer" @click.stop="showSellerData(tr.id)"></span>
                </vs-td>
                <vs-td>
                  <span v-text="tr.phone" class="cursor-pointer" @click.stop="showSellerData(tr.id)"></span>
                </vs-td>
                <vs-td>
                  <span v-text="tr.address" class="cursor-pointer" @click.stop="showSellerData(tr.id)"></span>
                </vs-td>
                <vs-td class="whitespace-no-wrap notupfromall">
                  <feather-icon icon="EditIcon" svgClasses="w-5 h-5 hover:text-primary stroke-current" class="mr-2" @click.stop="editData(tr)" />
                  <feather-icon icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2" @click.stop="deleteData(tr.id)" />
                </vs-td>
              </vs-tr>
            </tbody>
          </template>
        </vs-table>
        <vs-popup class="holamundo" title="جزییات معلومات نهاد" :active.sync="popupActive">
          <div :key="indextr" v-for="(tr, indextr) in seller">
            <div class="con-expand-clients w-full">
              <div class="con-btns-client flex items-center justify-between">
                <div class="con-clientx flex items-center justify-start">
                  <span><strong>{{ tr.name }}</strong></span>
                </div>
                <vs-divider></vs-divider>
                <div class="flex">
                </div>
              </div>
              <vs-list>
                <vs-list-item icon-pack="feather" icon="icon-phone" :title="tr.phone"></vs-list-item>
                <vs-list-item icon-pack="feather" icon="icon-map-pin" :title="tr.address"></vs-list-item>
              </vs-list>
            </div>
          </div>
        </vs-popup>
      </div>
    </vs-tab>
    <vs-tab v-if="user_permissions.includes('vendor_add')" label="ثبت فروشنده" icon="add" class="leftScrol">
      <div class="scroll-area--data-list-add-new" :key="$vs.rtl">
        <div class="pt-6 pr-6 pl-6">
          <form data-vv-scope="sellerForm">
            <vs-input label="نام فروشنده" class="mt-5 w-full" name="sellername" v-validate="'required'" v-model="form.name" />
            <span class="absolute text-danger alerttext">{{ errors.first('sellerForm.sellername') }}</span>
            <!--<has-error :form="form" field="name"></has-error>-->
            <vs-input label="شماره تماس" class="mt-5 w-full" name="sellerphone" v-validate="'required'" v-model="form.phone" />
            <span class="absolute text-danger alerttext">{{ errors.first('sellerForm.sellerphone') }}</span>
            <!--<has-error :form="form" field="phone"></has-error>-->
            <vs-input label="آدرس" class="mt-5 w-full" name="selleraddress" v-validate="'required|min:2'" v-model="form.address" />
            <span class="absolute text-danger alerttext">{{ errors.first('sellerForm.selleraddress') }}</span>
            <!--<has-error :form="form" field="address"></has-error>-->
            <div class="flex flex-wrap items-center p-2 mt-3" slot="footer">
              <vs-button type="border" color="success" class="mr-6" :disabled="form.busy" @click="submitData" icon="save">ذخیره</vs-button>
            </div>
            <!--<vs-list v-if="(errors.items.length > 0)">
              <vs-list-header color="danger" title="مشکلات"></vs-list-header>
              <div :key="indextr" v-for="(error, indextr) in errors.items">
                <vs-list-item icon="verified_user" style="color:red;" :subtitle="error.msg"></vs-list-item>
              </div>
            </vs-list>-->
          </form>
        </div>
      </div>
    </vs-tab>
  </vs-tabs>
</vs-sidebar>
</template>

<script>
import TableLoading from './../shared/TableLoading.vue'
import vSelect from 'vue-select'
import {
  Validator
} from 'vee-validate'
export default {
  props: {
    isSidebarActive: {
      type: Boolean,
      required: true
    },
    data: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      isdata: false,
      sellerActiveForm: false,
      popupActive: false,
      form: new Form({
        id: '',
        name: '',
        phone: '',
        address: '',
        user_id: localStorage.getItem('id'),
      }),
      formEdit: new Form({
        id: '',
        name: '',
        phone: '',
        address: '',
        account_id: null,
      }),
      sellers: [],
      seller: {},
      dictf: {
        custom: {
          sellername: {
            required: ' نام فروشنده الزامی میباشد.'
          },
          sellerphone: {
            required: ' شماره تماس فروشنده الزامی میباشد.'
          },
          selleraddress: {
            required: ' آدرس فروشنده الزامی میباشد.',
            min: 'آدرس فروشنده باید بیشتر از 2 حرف باشد.'
          },
        }
      }
    }
  },
  created() {
    Validator.localize('en', this.dictf);
    this.getAllSellers();
  },
  components: {
    TableLoading,
    Validator
  },
  computed: {
    isSidebarActiveLocal: {
      get() {
        return this.isSidebarActive
      },
      set(val) {
        if (!val) {
          this.$validator.reset();
          this.$emit('closeSidebar')
        }
      }
    },
  },
  methods: {
    getAllSellers() {
      this.$Progress.start()
      // // this.$vs.loading()
      this.axios.get('/api/vendors')
        .then((response) => {
          this.sellers = response.data;
          this.$Progress.set(100);
          this.isdata = true;
          // // document.getElementById("loading-bg").style.display = "none";;
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
        })
    },
    submitData() {
      this.$validator.validateAll('sellerForm').then(result => {
        if (result) {
          this.form.post('/api/vendors')
            .then(() => {
              this.$parent.$emit('selleradd', 'add');
              this.$vs.notify({
                title: 'عملیه ثبت موفق بود!',
                text: 'عملیه موفقانه انجام شد',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
              this.getAllSellers();
              this.form.reset();
              this.$validator.reset();
            })
            .catch(() => {
              this.$vs.notify({
                title: 'عملیه ثبت نام فروشندهوفق بود!',
                text: 'عملیه  ناکام شد لطفا دوباره تلاش نماید',
                color: 'danger',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
            })

        } else {

          // form have errors
        }
      })

    },
    editData(data) {
      this.sellerActiveForm = true;
      this.formEdit.name = data.name;
      this.formEdit.phone = data.phone;
      this.formEdit.address = data.address;
      this.formEdit.account_id = data.account_id;
      this.formEdit.id = data.id;
    },
    resetAllState() {
      this.sellerActiveForm = false
      this.formEdit.reset();
    },
    showSellerData(id) {
      this.$Progress.start()
      // this.$vs.loading()
      this.sellerActiveForm = false;
      this.form.get('/api/vendors/' + id)
        .then((response) => {
          this.seller = response.data
          this.$Progress.set(100)
          // document.getElementById("loading-bg").style.display = "none";;
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
          this.popupActive = true;
        })
    },
    updateData() {
      this.formEdit.put('/api/vendors/' + this.formEdit.id)
        .then(({
          data
        }) => {
          // Finish the Progress Bar
          this.getAllSellers();
          this.resetAllState();
          // toast notification
          this.$vs.notify({
            title: 'موفقیت!',
            text: 'فروشنده مذکور موفقانه آپدیت شد.',
            color: 'success',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
        });
    },
    deleteData(id) {
      this.sellerActiveForm = false;
      swal.fire({
        title: 'آیا مطمئن هستید؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'rgb(54 34 119)',
        cancelButtonColor: 'rgb(229 83 85)',
        confirmButtonText: '<span>بله، حذف شود!</span>',
        cancelButtonText: '<span>خیر، لغو عملیه!</span>'
      }).then((result) => {
        if (result.isConfirmed) {
          this.$parent.$emit('sellerdelete', 'delete');
          this.form.delete('/api/vendors/' + id).then((id) => {
              swal.fire({
                title: 'عملیه حذف موفقانه انجام شد.',
                icon: 'success',
              })
              this.getAllSellers();
              this.resetAllState();
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
  }
}
</script>

<style>
.leftScrol {
  height: 80vh;
  overflow-y: scroll;
}
</style><style lang="scss" scoped>
.add-new-data-sidebar {
  ::v-deep .vs-sidebar--background {
    z-index: 52010;
  }

  ::v-deep .vs-sidebar {
    z-index: 52010;
    width: 500px;
    max-width: 90vw;

    .img-upload {
      margin-top: 2rem;

      .con-img-upload {
        padding: 0;
      }

      .con-input-upload {
        width: 100%;
        margin: 0;
      }
    }
  }
}

.scroll-area--data-list-add-new {
  // height: calc(var(--vh, 1vh) * 100 - 4.3rem);
  height: calc(var(--vh, 1vh) * 100 - 16px - 45px - 82px);

  &:not(.ps) {
    overflow-y: auto;
  }
}
</style><style lang="scss">
#data-list-thumb-view {
  .vs-con-table {
    // .product-name {
    //   max-width: 23rem;
    // }

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
      padding: 0 0.6rem;

      tr {
        box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);

        td {
          padding: 10px !important;

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
              height: 70px;
              padding-left: 10px;
            }
          }
        }

        td.td-check {
          padding: 10px !important;
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
