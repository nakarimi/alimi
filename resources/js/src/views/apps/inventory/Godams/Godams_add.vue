<template>
<vs-sidebar position-right parent="body" default-index="1" color="primary" class="add-new-data-sidebar items-no-padding" spacer v-model="isSidebarActiveLocal">
  <div class="mt-6 flex items-center justify-between px-6 float-right">
    <feather-icon icon="XIcon" @click.stop="isSidebarActiveLocal = false" class="cursor-pointer"></feather-icon>
  </div>
  <vs-tabs>
    <vs-tab v-if="user_permissions.includes('godam_view')" label=" لیست گدام" icon-pack="feather" icon="icon-list" class="leftScrol">
      <div :key="$vs.rtl" v-if="godamActiveForm">
        <form>
          <vs-divider>
            <h4>
              ویرایش معلومات گدام
            </h4>
          </vs-divider>
          <div class="pt-1 pr-6 pl-6">
            <vs-input label="نام گدام" class="mt-3 w-full" v-model="gFormEdit.name" />
            <has-error :form="gFormEdit" field="name"></has-error>
            <vs-input label=" آدرس" class="mt-3 w-full" v-model="gFormEdit.address" />
            <has-error :form="gFormEdit" field="address"></has-error>
            <vs-input label=" مسول" class="mt-3 w-full" v-model="gFormEdit.manager" />
            <has-error :form="gFormEdit" field="manager"></has-error>
            <vs-input label=" تماس" class="mt-3 w-full" v-model="gFormEdit.phone" />
            <has-error :form="gFormEdit" field="phone"></has-error>
            <div class="vx-col mt-5">
              <vs-textarea label="تفصیلات" :rows="gFormEdit.description && gFormEdit.description.split(`\n`).length > 2 ? gFormEdit.description.split(`\n`).length + 1 : 3" v-model="gFormEdit.description" />
              <has-error :form="gFormEdit" field="description"></has-error>
            </div>
          </div>
          <div class="flex flex-wrap items-center p-6" slot="footer">
            <vs-button color="success" class="mr-6" @click="updateGodamData()"><strong>ویرایش گدام</strong></vs-button>
            <vs-button color="danger" @click="resetAllState()">بستن فورم ویرایش</vs-button>
            <vs-divider>---</vs-divider>
          </div>
        </form>
      </div>
      <div id="data-list-thumb-view" class="w-full data-list-container">
        <vx-card v-if="!isdata">
          <TableLoading></TableLoading>
        </vx-card>
        <vs-table v-if="isdata" pagination :max-items="4" :data="godams">
          <template slot="thead">
            <vs-th>#</vs-th>
            <vs-th>نام گدام</vs-th>
            <vs-th>مسول</vs-th>
            <vs-th>تماس</vs-th>
            <vs-th>بررسی</vs-th>
          </template>
          <template slot-scope="{data}">
            <vs-tr :key="indextr" v-for="(tr, indextr) in data">
              <vs-td>
                <span class="cursor-pointer" @click.stop="showGodamData(tr.id)">{{++indextr}}</span>
              </vs-td>
              <vs-td>
                <span v-text="tr.name" class="cursor-pointer" @click.stop="showGodamData(tr.id)"></span>
              </vs-td>
              <vs-td>
                <span v-text="tr.manager" class="cursor-pointer" @click.stop="showGodamData(tr.id)"></span>
              </vs-td>
              <vs-td>
                <span v-text="tr.phone" class="cursor-pointer" @click.stop="showGodamData(tr.id)"></span>
              </vs-td>
              <vs-td class="whitespace-no-wrap notupfromall">
                <feather-icon icon="MoreVerticalIcon" svgClasses="w-6 h-6 hover:text-primary stroke-current" class="mr-2 cursor-pointer" @click.stop="showGodamData(tr.id)" />
                <feather-icon v-if="user_permissions.includes('godam_edit')" icon="EditIcon" svgClasses="w-6 h-6 hover:text-primary stroke-current" class="mr-2 cursor-pointer" @click.stop="editGodamData(tr)" />
                <feather-icon v-if="user_permissions.includes('godam_remove')" icon="TrashIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click.stop="deleteGodamData(tr.id)" />
              </vs-td>
            </vs-tr>
          </template>
        </vs-table>
        <vs-popup class="holamundo" title="جزییات معلومات گدام" :active.sync="popupActive">
          <div :key="indextr" v-for="(tr, indextr) in godamSingleRowData">
            <div class="con-expand-clients w-full">
              <div class="con-btns-client flex items-center justify-between">
                <div class="con-clientx flex items-center justify-start">
                </div>
                </vs-divider>
                <div class="flex">
                </div>
              </div>
              <vs-list>
                <vs-list-item icon-pack="feather" icon="icon-home" :title="tr.name"></vs-list-item>
                <vs-list-item icon-pack="feather" icon="icon-user" :title="tr.manager"></vs-list-item>
                <vs-list-item icon-pack="feather" icon="icon-phone" :title="tr.phone"></vs-list-item>
                <vs-list-item icon-pack="feather" icon="icon-map-pin" :title="tr.address"></vs-list-item>
                <vs-list-item icon-pack="feather" icon="icon-clipboard" :title="tr.description"></vs-list-item>
              </vs-list>
            </div>
          </div>
        </vs-popup>
      </div>
    </vs-tab>
    <vs-tab v-if="user_permissions.includes('godam_add')" label="ثبت گدام" icon-pack="feather" icon="icon-plus" class="leftScrol">
      <form data-vv-scope="godamForm">
        <div class="pt-1 pr-6 pl-6">
          <vs-input label="نام گدام" name="inv_name" v-validate="'required'" class="mt-3 pt-1 w-full" v-model="gForm.name" />
          <span class="absolute text-danger alerttext">{{ errors.first('godamForm.inv_name') }}</span>
          <!--<has-error :form="gForm" field="name"></has-error>-->
          <vs-input label=" آدرس" name="inv_address" v-validate="'required|min:3'" class="mt-3 pt-1 w-full" v-model="gForm.address" />
          <span class="absolute text-danger alerttext">{{ errors.first('godamForm.inv_address') }}</span>
          <!--<has-error :form="gForm" field="address"></has-error>-->
          <vs-input label=" مسول" name="inv_responsible" v-validate="'required|min:2'" class="mt-3 pt-1 w-full" v-model="gForm.manager" />
          <span class="absolute text-danger alerttext">{{ errors.first('godamForm.inv_responsible') }}</span>
          <!--<has-error :form="gForm" field="manager"></has-error>-->
          <vs-input label=" تماس" name="inv_phone" v-validate="'required'" class="mt-3 pt-1 w-full" v-model="gForm.phone" />
          <span class="absolute text-danger alerttext">{{ errors.first('godamForm.inv_phone') }}</span>
          <!--<has-error :form="gForm" field="phone"></has-error>-->
          <div class="vx-col mt-5">
            <vs-textarea label="تفصیلات" v-model="gForm.description" :rows="gForm.description && gForm.description.split(`\n`).length > 2 ? gForm.description.split(`\n`).length + 1 : 3"/>
            <has-error :form="gForm" field="description"></has-error>
          </div>
        </div>
        <div class="flex flex-wrap items-center p-6" slot="footer">
          <vs-button :disabled="gForm.busy" color="success" icon="save" class="mr-6" @click="submitGFormData()"><strong>ثبت گدام</strong></vs-button>
        </div>
        <!--<vs-list v-if="(errors.items.length > 0)">
          <vs-list-header color="danger" title="مشکلات"></vs-list-header>
          <div :key="indextr" v-for="(error, indextr) in errors.items">
            <vs-list-item icon="verified_user" style="color:red;" :subtitle="error.msg"></vs-list-item>
          </div>
        </vs-list> -->
      </form>
    </vs-tab>
  </vs-tabs>
</vs-sidebar>
</template>

<script>
import vSelect from "vue-select";
import TableLoading from './../../shared/TableLoading.vue'
import {
  Validator
} from 'vee-validate'
export default {
  props: {
    isSidebarActive: {
      type: Boolean,
      required: true,
    },
    data: {
      type: Object,
      default: () => {},
    },
  },
  components: {
    "v-select": vSelect,
    TableLoading,
    Validator
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      isdata: false,
      godamActiveForm: false,
      popupActive: false,
      gForm: new Form({
        name: '',
        address: '',
        manager: '',
        phone: '',
        description: ''
      }),
      gFormEdit: new Form({
        name: '',
        address: '',
        manager: '',
        phone: '',
        description: ''
      }),
      godams: [],
      godamSingleRowData: [],
      dict: {
        custom: {
          inv_name: { required: ' نام گدام الزامی میباشد', min: 'اسم گدام باید بیشتر از 2 حرف باشد.' },
          inv_address: { required: 'آدرس گدام الزامی میباشد.', min: 'آدرس گدام باید بیشتر از 3 حرف باشد.' },
          inv_responsible: { required: 'شخص مسول گدام الزامی میباشد', min: 'اسم شخص مسول گدام باید بیشتر از 2 حرف باشد.' },
          inv_phone: { required: 'شماره تماس شخص مسول گدام الزامی میباشد' }
        }
      }
    };
  },
  watch: {},
  computed: {
    isSidebarActiveLocal: {
      get() {
        return this.isSidebarActive;
      },
      set(val) {
        if (!val) {
          this.$emit("closeSidebar");
        }
      },
    },
  },
  created() {
    Validator.localize('en', this.dict);
    this.getGodamList();
  },
  methods: {
    submitGFormData() {
      this.$validator.validateAll('godamForm').then(result => {
        if (result) {
          this.gForm.post('/api/godam')
            .then(({
              data
            }) => {
              this.getGodamList();
              this.$vs.notify({
                title: 'موفقیت!',
                text: 'گدام موفقانه ثبت سیستم شد.',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
              this.gForm.reset();
              this.$validator.reset();
            }).catch((errors) => {
              this.$Progress.set(100)
              this.$vs.notify({
                title: 'ناموفق!',
                text: 'لطفاً معلومات گدام را چک کنید و دوباره امتحان کنید!',
                color: 'danger',
                iconPack: 'feather',
                icon: 'icon-alert-triangle',
                position: 'top-right'
              })
            });

        } else {

          // form have errors
        }
      })

    },
    getGodamList() {
      this.$Progress.start()
      // this.$vs.
      this.axios.get('/api/godam')
        .then((response) => {
          this.godams = response.data;
          this.isdata = true;
          this.$Progress.set(100)
        })
    },
    editGodamData(data) {
      this.godamActiveForm = true;
      this.gFormEdit.name = data.name;
      this.gFormEdit.address = data.address;
      this.gFormEdit.manager = data.manager;
      this.gFormEdit.phone = data.phone;
      this.gFormEdit.description = data.description;
      this.gFormEdit.id = data.id;
    },
    showGodamData(id) {
      this.godamActiveForm = false;
      this.$Progress.start()
      this.gForm.get('/api/godam/' + id)
        .then((response) => {
          this.godamSingleRowData = response.data;
          this.$Progress.set(100)
          this.popupActive = true;
        })
    },
    resetAllState() {
      this.godamActiveForm = false
      this.gFormEdit.reset();
    },
    updateGodamData() {
      this.gFormEdit.put('/api/godam/' + this.gFormEdit.id)
        .then(({
          data
        }) => {
          // Finish the Progress Bar
          this.getGodamList();
          this.resetAllState();
          // toast notification
          this.$vs.notify({
            title: 'موفقیت!',
            text: 'موسسه مذکور موفقانه آپدیت شد.',
            color: 'success',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
        });
    },
    deleteGodamData(id) {
      this.godamActiveForm = false;
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
          this.gForm.delete('/api/godam/' + id).then((id) => {
              swal.fire({
                title: 'عملیه موفقانه انجام شد.',
                text: "گدام از سیستم پاک شد!",
                icon: 'success',
              })
              this.getGodamList();
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
  }
};
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
    width: 600px;
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
              height: 80px;
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
