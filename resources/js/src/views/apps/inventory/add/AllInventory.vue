<template>
<div id="all-inventory" class="scrollable">
  <component :is="scrollbarTag" class="scroll-area--data-list-add-new" :key="$vs.rtl">
    <div :key="$vs.rtl" v-if="storageActiveForm">
      <form data-vv-scope="addnewInveForm" class="p-3">
        <vs-divider>
          <h4>
            ویرایش معلومات ذخیره
          </h4>
        </vs-divider>
        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="st_code" v-validate="'required'" type="text" label="کود" v-model="sFormEdit.code" />
            <span class="absolute text-danger alerttext">{{ errors.first('addnewInveForm.st_code') }}</span>
          </div>
        </div>

        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="st_name" v-validate="'required|min:2'" label="عنوان" v-model="sFormEdit.name" />
            <span class="absolute text-danger alerttext">{{ errors.first('addnewInveForm.st_name') }}</span>
          </div>
        </div>

        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="st_manager" v-validate="'required'" label="مسؤل" v-model="sFormEdit.manager" />
            <span class="absolute text-danger alerttext">{{ errors.first('addnewInveForm.st_manager') }}</span>
          </div>
        </div>

        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="st_phone" v-validate="'required'" label="شماره" v-model="sFormEdit.phone" />
            <span class="absolute text-danger alerttext">{{ errors.first('addnewInveForm.st_phone') }}</span>
          </div>
        </div>

        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="st_address" v-validate="'required|min:3'" label="آدرس" v-model="sFormEdit.address" />
            <span class="absolute text-danger alerttext">{{ errors.first('addnewInveForm.st_address') }}</span>
          </div>
        </div>

        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="st_capacity" v-validate="'required'" label="ظرفیت" v-model="sFormEdit.capacity" />
            <span class="absolute text-danger alerttext">{{ errors.first('addnewInveForm.st_capacity') }}</span>
          </div>
        </div>

        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <label for="" class="vs-input--label">واحد ظرفیت</label>
            <v-select v-model="sFormEdit.oum_id" name="st_oum_id" v-validate="'required'" label="title" :options="uom" :searchable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'">
              <span slot="no-options">
                {{$t('WhoopsNothinghere')}}
              </span>
            </v-select>
            <span class="absolute text-danger alerttext">{{ errors.first('addnewInveForm.st_oum_id') }}</span>
          </div>
        </div>

        <div class="vx-row">
          <div class="vx-col w-full">
            <vs-button :disabled="sFormEdit.busy" class="mr-3 mb-2" @click="updateStorageData">آپدیت</vs-button>
            <vs-button color="warning" type="border" class="mb-2" @click.stop="storageActiveForm = false">لغو</vs-button>
          </div>
        </div>
      </form>
    </div>
    <div id="data-list-thumb-view" class="w-full data-list-container">
      <div v-if="!isdata">
        <TableLoading></TableLoading>
      </div>
      <vs-table v-if="isdata" :data="storages">
        <template slot="thead">
          <vs-th>کود</vs-th>
          <vs-th>نام ذخیره</vs-th>
          <vs-th>مسول</vs-th>
          <vs-th>ظرقیت</vs-th>
          <vs-th>بررسی</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr :key="indextr" v-for="(tr, indextr) in data">
            <vs-td>
              <span class="cursor-pointer" @click.stop="showStorageData(tr.id)">{{++indextr}}</span>
            </vs-td>
            <vs-td>
              <span v-text="tr.name" class="cursor-pointer" @click.stop="showStorageData(tr.id)"></span>
            </vs-td>
            <vs-td>
              <span v-text="tr.manager" class="cursor-pointer" @click.stop="showStorageData(tr.id)"></span>
            </vs-td>
            <vs-td>
              <span v-text="tr.capacity" class="cursor-pointer" @click.stop="showStorageData(tr.id)"></span>
            </vs-td>
            <vs-td class="whitespace-no-wrap notupfromall">
              <feather-icon icon="MoreVerticalIcon" svgClasses="w-6 h-6 hover:text-primary stroke-current" class="mr-2 cursor-pointer" @click.stop="showStorageData(tr.id)" />
              <feather-icon icon="EditIcon" svgClasses="w-6 h-6 hover:text-primary stroke-current" class="mr-2 cursor-pointer" @click.stop="editStorageData(tr)" />
              <feather-icon icon="TrashIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click.stop="deleteStorageData(tr.id)" />
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
      <vs-popup class="holamundo" title="جزییات معلومات ذخیره" :active.sync="popupActive">
        <div :key="indextr" v-for="(tr, indextr) in storageSingleRowData">
          <div class="con-expand-clients w-full">
            <vs-list>
              <vs-list-item icon-pack="feather" icon="icon-home" :title="tr.name"></vs-list-item>
              <vs-list-item icon-pack="feather" icon="icon-user" :title="tr.manager"></vs-list-item>
              <vs-list-item icon-pack="feather" icon="icon-phone" :title="tr.phone"></vs-list-item>
              <vs-list-item icon-pack="feather" icon="icon-map-pin" :title="tr.address"></vs-list-item>
              <vs-list-item icon-pack="feather" icon="icon-clipboard" :title="tr.capacity"></vs-list-item>
            </vs-list>
          </div>
        </div>
      </vs-popup>
    </div>
  </component>
</div>
</template>

<script>
import vSelect from 'vue-select'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import {
  Validator
} from 'vee-validate'

import TableLoading from './../../shared/TableLoading.vue'
export default {
  name: 'vx-archive',
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      isdata: false,
      storageActiveForm: false,
      popupActive: false,
      uom: [],
      sFormEdit: new Form({
        id: '',
        code: '0',
        name: '',
        manager: '',
        phone: '',
        address: '',
        capacity: '0',
        oum_id: '',
      }),
      dict: {
        custom: {
          st_code: {
            required: ' کود الزامی میباشد.'
          },
          st_name: {
            required: ' عنوان ذخیره الزامی میباشد.',
            min: 'عنوان ذخیره باید بیشتر از 2 حرف باشد.'
          },
          st_manager: {
            required: ' اسم شخص مسول الزامی میباشد.'
          },
          st_phone: {
            required: ' شماره تماس شخص مسول الزامی میباشد.'
          },
          st_address: {
            required: ' آدرس شخص مسول الزامی میباشد.',
            min: 'آدرس شخص مسول باید بیشتر از 3 حرف باشد.'
          },
          st_capacity: {
            required: ' ظرفیت ذخیره الزامی میباشد.'
          },
          st_oum_id: {
            required: ' واحد ظرفیت ذخیره الزامی میباشد.'
          },
        }
      },
      storages: [],
      storageSingleRowData: [],
      settings: {
        maxScrollbarLength: 50,
        wheelSpeed: .60
      },
    }
  },
  created() {
    this.getStorageList();
    this.loaduom();
  },
  components: {
    VuePerfectScrollbar,
    TableLoading,
    'v-select': vSelect,
    Validator
  },
  computed: {
    scrollbarTag() {
      return this.$store.getters.scrollbarTag
    }
  },

  methods: {
    getStorageList() {
      this.axios.get('/api/storage')
        .then((response) => {
          this.isdata = true;
          this.storages = response.data;

        })
    },
    loaduom() {
      this.axios.get('/api/uom').then(({
        data
      }) => (this.uom = data));
    },
    editStorageData(data) {
      this.sFormEdit.get(`/api/storage/${data.id}/edit`)
        .then((response) => {
          let d = response.data;
          this.storageActiveForm = true;
          this.sFormEdit.id = d.id;
          this.sFormEdit.code = d.code;
          this.sFormEdit.name = d.name;
          this.sFormEdit.manager = d.manager;
          this.sFormEdit.phone = d.phone;
          this.sFormEdit.address = d.address;
          this.sFormEdit.capacity = d.capacity;
          this.sFormEdit.oum_id = this.uom.find(element => element.id == d.oum_id);
        })
    },
    showStorageData(id) {
      this.storageActiveForm = false;
      this.$Progress.start()
      this.sFormEdit.get('/api/storage/' + id)
        .then((response) => {
          this.storageSingleRowData = response.data;
          this.$Progress.set(100)
          this.popupActive = true;
        })
    },
    resetAllState() {
      this.storageActiveForm = false
      this.sFormEdit.reset();
    },
    updateStorageData() {
      this.$validator.validateAll('addnewInveForm').then(result => {
        if (result) {
          swal.fire({
            title: 'آیا مطمئن هستید؟',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: 'rgb(54 34 119)',
            cancelButtonColor: 'rgb(229 83 85)',
            confirmButtonText: '<span>بله، حذف شود!</span>',
            cancelButtonText: '<span>خیر، لغو عملیه!</span>'
          }).then((result) => {
            this.sFormEdit.patch('/api/storage/' + this.sFormEdit.id)
              .then(({
                data
              }) => {
                this.getStorageList();
                this.resetAllState();
                this.$vs.notify({
                  title: 'موفقیت!',
                  text: 'ذخیره مذکور موفقانه آپدیت شد.',
                  color: 'success',
                  iconPack: 'feather',
                  icon: 'icon-check',
                  position: 'top-right'
                })
              });
          });
        }
      });
    },
    deleteStorageData(id) {
      this.storageActiveForm = false;
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
          this.sFormEdit.delete('/api/storage/' + id).then((id) => {
              swal.fire({
                title: 'عملیه موفقانه انجام شد.',
                text: "ذخیره از سیستم پاک شد!",
                icon: 'success',
              })
              this.getStorageList();
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

    }
  },
  mounted() {
    this.isMounted = false
  },
}
</script>

<style lang="scss">
#all-inventory .con-tab.vs-tabs--content {
  max-height: 90vh !important;
  overflow-y: scroll !important;
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
