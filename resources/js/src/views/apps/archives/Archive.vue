<template>
<div>
  <div class="vx-row">
    <div class="vx-col w-full mb-2">
      <vx-card>
        <div class="vx-row">
          <div class="md:w-1/2 lg:w-1/3 xl:w-1/3">
            <div class="vx-col w-full">
              <label for>
                <small>اکونت مربوط را انتخاب نماید!</small>
              </label>
              <v-select v-model="seletedAccount" label="name" :options="accounts" :dir="$vs.rtl ? 'rtl' : 'ltr'" @input="accountChanged" />
            </div>
          </div>
        </div>
      </vx-card>
    </div>
  </div>
  <div class="vx-row reverse-col">
    <div v-if="user_permissions.includes('archive_view')" class="vx-col sm:mt-4 w-full" v-bind:class="{ 'hide': fullWidth, 'lg:w-2/3': !fullWidth }">
      <div v-if="!isdata">
        <TableLoading></TableLoading>
      </div>
      <vx-card v-if="isdata" title="لست آرشیف" style="max-height:527px;">
        <vs-table max-items="7" pagination :data="archives" stripe @updateTable="loadArchives">
          <template slot="thead">
            <vs-th>عنوان</vs-th>
            <vs-th>ریفرینس</vs-th>
            <vs-th>کاربر</vs-th>
            <vs-th>تفصیلات</vs-th>
            <vs-th>تعداد</vs-th>
            <vs-th>تغییرات</vs-th>
          </template>
          <template slot-scope="{ data }">
            <vs-tr :key="indextr" v-for="(tr, indextr) in data">
              <vs-td>{{ tr.title }}</vs-td>
              <vs-td>{{ tr.refcode  }}</vs-td>
              <vs-td>{{ tr.user.firstName }} {{ tr.user.lastName }}</vs-td>
              <vs-td>{{ tr.note }}</vs-td>
              <vs-td>{{ tr.files_count }} فایل</vs-td>
              <vs-td class="whitespace-no-wrap notupfromall">
                <feather-icon icon="FileIcon" svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer" class="mr-2" @click.stop="archiveFilesModal(tr.id)" />
                <feather-icon v-if="user_permissions.includes('archive_edit')" icon="EditIcon" svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer" />
                <!--</router-link> -->
                <feather-icon v-if="user_permissions.includes('archive_remove')" icon="TrashIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current cursor-pointer" class="ml-2" @click.stop="deleteData(tr.id)" />
              </vs-td>
            </vs-tr>
          </template>
        </vs-table>
      </vx-card>
    </div>
    <div v-if="user_permissions.includes('archive_add')" class="vx-col sm:mt-4 w-full" v-bind:class="{ 'lg:w-3/3 full-width': fullWidth, 'lg:w-1/3': !fullWidth }">
      <vx-card title="افزودن آرشیف جدید" :before-change="validateArchiveAdd">
        <template slot="actions">
          <feather-icon :icon="fullWidth ? 'MinimizeIcon' : 'MaximizeIcon'" svgClasses="w-6 h-6 text-primary" @click="fullWidth = !fullWidth"></feather-icon>
        </template>
        <div class="vx-row">
          <div v-bind:class="{ 'md:w-1/2 p-3': fullWidth, 'w-full': !fullWidth }">
            <div class="mb-3">
              <vs-input autocomplete="off" class="w-full" v-validate="'required|min:2'" name="title" label="عنوان فایل" v-model="aForm.title" />
              <span class="absolute text-danger alerttext">{{ errors.first('archForm.title') }}</span>
            </div>
            <div class="mb-3">
              <vs-input type="text" class="w-full" autocomplete="off" v-validate="'required'" name="refcode" label="ریفرینس کود" v-model="aForm.refcode" />
              <span class="absolute text-danger alerttext">{{ errors.first('archForm.refcode') }}</span>
            </div>
          </div>
          <div class="mb-3" v-bind:class="{ 'md:w-1/2 p-3': fullWidth, 'w-full': !fullWidth }">
            <label for=""><small>تفصیلات</small></label>
            <vs-textarea :rows="fullWidth ? 4 : 2" autocomplete="off" class="w-full" name="note" v-model="aForm.note" />
            <span class="absolute text-danger alerttext">{{ errors.first('archForm.note') }}</span>
          </div>
        </div>
        <div id="scroll">
          <vs-upload :limit="20" ref="vsUpload" accept="pdf" :data="{account_id: aForm.account_id}" text="اپلود فایل آرشیف" multiple fileName='archive[]' :show-upload-button="checkUploadCondition" action="/api/archive/upload" @on-success="successUpload" @on-error="onError" @on-delete="onDelete" />
        </div>

        <div class="flex flex-wrap items-center p-6" slot="footer">
          <vs-button color="warning" class="lg:mr-6 sm:mx-auto mb-2" @click="upload">آپلود همه</vs-button>
          <vs-button :color="(aForm.archive_files != null && aForm.archive_files.length > 0) ? 'success': 'danger'" :title="(aForm.archive_files != null && aForm.archive_files.length > 0)? 'فایل های آپلود شده را ثبت کنید' : 'ابتدا فایل های آرشیف را آپلود کنید.'" class="lg:mr-6 sm:mx-auto mb-2" @click="submitArchiveData">ذخیره</vs-button>
          <vs-button type="border" class="sm:mx-auto mb-2" color="danger" @click="resetFrom">لغو</vs-button>
        </div>
        <!-- </form> -->
      </vx-card>
    </div>
  </div>
  <vs-popup class="holamundo width-80" title="معلومات آرشیف" :active.sync="archiveModalActive">
    <archive-view :archive="archive_to_view" />
  </vs-popup>
</div>
</template>

<script>
import TableLoading from '../shared/TableLoading.vue'
import ArchiveView from './ArchiveView'
import {
  Validator
} from 'vee-validate'
import vSelect from "vue-select";
export default {
  props: {
    data: {
      type: Object,
      default: () => {},
    },
  },
  name: "vx-archive",
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      archiveModalActive: false,
      archive_to_view: null,
      seletedAccount: {},
      archiveFile: [],
      accounts: [],
      isdata: false,
      aForm: new Form({
        title: '',
        refcode: '',
        account_id: null,
        note: '',
        user_id: localStorage.getItem('id'),
        type: 'important',
        archive_files: null,
      }),
      fullWidth: false,
      settings: {
        // perfectscrollbar settings
        maxScrollbarLength: 60,
        wheelSpeed: 0.6,
      },
      archives: [],
      dict: {
        custom: {
          title: {
            required: ' عنوان الزامی میباشد.',
            min: 'عنوان باید بیشتر از 2 حرف باشد.',
          },
          refcode: {
            required: 'کود الزامی میباشد',
            unique: 'کود منحصر میباشد'
          },
          account_id: {
            required: 'حساب الزامی است.',
          },
          note: {
            required: ' توضیح الزامی است.',
            min: 'توضیح باید بیشتر از 3 حرف باشد.',
          },
          type: {
            required: 'نوع آرشیف الزامی است.',
          }
        }
      }
    };
  },

  methods: {
    validateArchiveAdd() {
      return new Promise((resolve, reject) => {
        this.$validator.validateAll('archForm').then(result => {
          if (result) {
            resolve(true)
          } else {
            reject('correct all values')
          }
        })
      })
    },
    resetFrom() {
      // Remove Items From VS Upload object
      for (const index of Object.keys(this.$refs.vsUpload.filesx)) {
        this.$refs.vsUpload.removeFile(+index);
      }
      var account_id = this.aForm.account_id;
      this.aForm.reset();
      this.aForm.account_id = account_id;
      this.$validator.reset();
    },
    loadAccount() {
      // this.$vs.loading();
      this.$Progress.start();
      this.axios.get('/api/accoutload')
        .then((resp) => {
          this.accounts = resp.data;
          this.$Progress.set(100);
          // document.getElementById("loading-bg").style.display = "none";;
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
        });
    },
    defalutAcount() {
      // this.$vs.loading()
      this.$Progress.start();
      this.axios.get('/api/mostresent').then(({
        data
      }) => (this.seletedAccount = data, this.aForm.account_id = data.id, this.$Progress.set(100), document.getElementById("loading-bg").style.display = "none"));
    },

    accountChanged(data) {
      this.isdata = false;
      this.aForm.account_id = data.id;
      this.loadArchives();
    },
    upload() {
      this.$refs.vsUpload.upload('all');
    },
    submitArchiveData() {
      if (this.aForm.archive_files != null && this.aForm.archive_files.length > 0) {
        let valide = this.validateArchiveAdd();
        if (valide) {
          this.aForm.post('/api/archive')
            .then(({
              data
            }) => {
              this.resetFrom();
              this.loadArchives();
              this.$vs.notify({
                title: 'موفقیت!',
                text: 'آرشیف موفقانه ثبت سیستم شد.',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
            }).catch((errors) => {
              this.$Progress.set(100)
              this.$vs.notify({
                title: 'ناموفق!',
                text: 'لطفاً معلومات آرشیف را چک کنید و دوباره امتحان کنید!',
                color: 'danger',
                iconPack: 'feather',
                icon: 'icon-alert-triangle',
                position: 'top-right'
              })
            });

        }
      } else {
        swal.fire({
          text: 'فایل های آرشیف را آپلود کنید.',
          icon: 'error',
          title: 'خطای کاربر',
          confirmButtonColor: '#ea5455',
        }).then((result) => {}).catch(() => {
          document.getElementById("loading-bg").style.display = "none";
        });
      }
    },

    loadArchives(id) {
      // this.$vs.loading();
      this.$Progress.start();
      this.axios.get('/api/archive', this.seletedAccount)
        .then((resp) => {
          // this.archives = resp.data;
          this.archives = resp.data.filter(c => (this.seletedAccount != null && this.seletedAccount.id) ? c.account_id == this.seletedAccount.id : true);
          this.$Progress.set(100);
          // document.getElementById("loading-bg").style.display = "none";;
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
          this.isdata = true;
        });
    },
    deleteData(id) {
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
          this.aForm.delete('/api/archive/' + id).then((id) => {
              swal.fire({
                title: 'عملیه حذف موفقانه انجام شد.',
                icon: 'success',
              })
              this.loadArchives();
              this.resetFrom();
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
    successUpload(event) {
      var result = true;
      try {
        JSON.parse(event.srcElement.response);
      } catch (e) {
        result = false;
      }
      if (result) {
        this.aForm.archive_files = event.srcElement.response;
        this.$vs.notify({
          color: "success",
          title: "آپلود فایل",
          text: "عمیله آپلود فایل با موفقیت انجام شد.",
        });
      } else {
        this.$refs.vsUpload.srcs.some((item, i) => {
          // console.log(item);
        })
        this.$vs.notify({
          color: "error",
          title: "آپلود ناموفق",
          text: "عمیله آپلود فایل انجام نشد.",
        });
      }
    },
    onError(event) {
      // console.log(event);
      const RESP = JSON.parse(event.srcElement.response);
      // console.log(RESP);
      for (let i = 0; i < RESP.length; ++i) {
        this.$refs.vsUpload.srcs[i].error = RESP[i];
        // this.$refs.vsUpload.srcs[i].percent = (RESP[i]) ? 100 : 0;
      }
      var msg = "خطا در آپلود فایل، لطفا فایل های خود را چک کنید.";
      if (event.srcElement.errors == 403) {
        msg = "حجم استاندارد برای آپلود ۴۰ ام‌بی میباشد."
      }
      this.$vs.notify({
        color: "danger",
        title: "آپلود فایل ناموفق",
        text: msg,
      });
    },
    onDelete(event) {
      // console.log(event);
      // setTimeout(() => {
      //   // let files = this.$refs.vsUpload.filesx.filter((key, item) => {
      //   let files = [];
      //   let srcs = [];
      //   this.$refs.vsUpload.filesx.some((item, i) => {
      //     if (!item.remove) {
      //       files.push(item);
      //       srcs.push(this.$refs.vsUpload.srcs[i])
      //     }
      //   });
      //   // this.$refs.vsUpload.itemRemove = [];
      //   this.$refs.vsUpload.srcs = srcs;
      //   this.$refs.vsUpload.filesx = files;
      // }, 501)

      // this.axios.post('/api/remove-file', {
      //   fileName: event.name,
      // }).then(({
      //   data
      // }) => {
      //   this.$vs.notify({
      //     color: "info",
      //     title: "حذف فایل",
      //     text: "عمیله حذف فایل با موفقیت انجام شد.",
      //   });
      // }).catch((errors) => {

      // });
    },
    archiveFilesModal(id) {
      this.axios.get(`/api/archive/${id}`)
        .then((resp) => {
          this.archive_to_view = resp.data;
          this.$Progress.set(100);
          // document.getElementById("loading-bg").style.display = "none";;
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
          this.archiveModalActive = true;
        });

    }

  },

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
    scrollbarTag() {
      return this.$store.getters.scrollbarTag;
    },
    checkUploadCondition() {
      return true;
    }
  },

  created() {
    Validator.localize('en', this.dict);
    this.defalutAcount();
    this.loadAccount();
    this.loadArchives();
  },
  components: {
    "v-select": vSelect,
    TableLoading,
    Validator,
    ArchiveView
  },
}
</script>

<style>
[dir] .vx-row>.vx-col {
  padding: 0 0.4rem !important;
}

.alerttext {
  padding: 10px;
  font-size: 13px !important;
}

#scroll {
  height: 35vh;
  overflow-y: scroll;
}

.vs-con-textarea {
  margin-bottom: 1px;
}

.con-vs-popup .vs-popup {
  width: fit-content !important;
}
</style>
