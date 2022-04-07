<template>
<vs-sidebar position-right parent="body" default-index="1" color="primary" class="add-new-data-sidebar items-no-padding" spacer v-model="isSidebarActiveLocal">
  <div class="mt-6 flex items-center justify-between px-6">
    <h4 v-if="editMode">حساب مذکور را ویرایش کنید</h4>
    <h4 v-if="!editMode">حساب جدید اضافه کنید</h4>
    <feather-icon icon="XIcon" @click.stop="isSidebarActiveLocal = false" class="cursor-pointer"></feather-icon>
  </div>
  <vs-divider class="mb-0"></vs-divider>
  <component :is="scrollbarTag" class="scroll-area--data-list-add-new" :key="$vs.rtl">
    <form data-vv-scope="accountForm">
      <div class="pt-6 pr-6 pl-6">
        <!-- NAME -->
        <div class="vx-col mt-4">
          <label for=""><small>نوعیت</small></label>
          <v-select label="title" name="account_type" v-validate="'required'" v-model="accForm.type_id" :options="accountTypes" :dir="$vs.rtl ? 'rtl' : 'ltr'" />
          <span class="absolute text-danger alerttext">{{ errors.first('accountForm.account_type') }}</span>
        </div>
        <vs-input label="ریفرینس کد" name="reference_code" v-model="accForm.ref_code" type="number" class="mt-5 w-full" />
        <span class="absolute text-danger alerttext">{{ errors.first('accountForm.reference_code') }}</span>
        <!-- NAME -->
        <vs-input label="عنوان" name="account_title" v-validate="'required'" v-model="accForm.name" class="mt-5 w-full" />
        <span class="absolute text-danger alerttext">{{ errors.first('accountForm.account_title') }}</span>
        <div class="vx-col mt-5">
          <label for="" class="ml-4 mr-4 mb-2">حالت</label>
          <div class="radio-group w-full">
            <div class="w-1/2">
              <input type="radio" v-model="accForm.status" value="1" id="struct" name="status" />
              <label for="struct" class="w-full text-center">فعال</label>
            </div>
            <div class="w-1/2">
              <input type="radio" v-model="accForm.status" value="2" id="specific" name="status" />
              <label for="specific" class="w-full text-center">غیرفعال</label>
            </div>
          </div>
        </div>
        <!-- NAME -->
        <div class="vx-row mt-4">
          <div class="vx-col w-1/2 pt-4">
            <!-- TITLE -->
            <label for=""> <small> کردیت</small></label>
            <vx-input-group class="mb-base">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>َAFN</span>
                </div>
              </template>
              <vs-input type="number" name="credit_account" v-validate="'required'" :disabled="accForm.id || accForm.debit > 0" v-model="accForm.credit" />
              <span class="absolute text-danger alerttext">{{ errors.first('accountForm.credit_account') }}</span>
            </vx-input-group>
            <!-- /TITLE -->
          </div>

          <div class="vx-col w-1/2 pt-4">
            <!-- TITLE -->
            <label for=""> <small> دبت</small></label>
            <vx-input-group class="mb-base">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>AFN</span>
                </div>
              </template>
              <vs-input type="number" name="debit_account" v-validate="'required'" :disabled="accForm.id || accForm.credit > 0" v-model="accForm.debit" />
              <span class="absolute text-danger alerttext">{{ errors.first('accountForm.debit_account') }}</span>
            </vx-input-group>
            <!-- /TITLE -->
          </div>
        </div>
        <vs-textarea label="تفصیلات" v-model="accForm.description" :rows="accForm.description && accForm.description.split(`\n`).length > 2 ? accForm.description.split(`\n`).length + 1 : 3"/>
        <!--<vs-list v-if="(errors.items.length > 0)">
          <vs-list-header color="danger" title="مشکلات"></vs-list-header>
          <div :key="indextr" v-for="(error, indextr) in errors.items">
            <vs-list-item icon="verified_user" style="color:red;" :subtitle="error.msg"></vs-list-item>
          </div>
        </vs-list>-->
      </div>
    </form>
  </component>

  <div class="flex flex-wrap items-center p-6" slot="footer">
    <vs-button v-if="!editMode" class="mr-6" :disabled="accForm.busy" @click="submitData">انجام</vs-button>
    <vs-button v-if="editMode" class="mr-6" :disabled="accForm.busy" @click="editAccountData">ویرایش</vs-button>
    <vs-button type="border" color="danger" @click="isSidebarActiveLocal = false">بستن</vs-button>
  </div>
</vs-sidebar>
</template>

<script>
import vSelect from "vue-select";
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import {
  Validator
} from 'vee-validate'

export default {
  props: ['isSidebarActive', 'accountTypes', 'accForm', 'editMode'],
  components: {
    VuePerfectScrollbar,
    "v-select": vSelect,
    Validator
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      // accountTypes: [],
      settings: {
        // perfectscrollbar settings
        // maxScrollbarLength: 60,
        // wheelSpeed: 0.6,
      },
      dict: {
        custom: {
          account_type: { required: ' نوع حساب الزامی میباشد.' },
          reference_code: { required: ' کود الزامی میباشد.' },
          account_title: { required: ' عنوان حساب الزامی میباشد.' },
          credit_account: { required: ' حساب کریدیت الزامی میباشد.' },
          debit_account: { required: ' حساب دیبیت الزامی میباشد.' }
        }
      }
    };
  },
  created() {
    Validator.localize('en', this.dict);
  },
  watch: {

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
  },
  methods: {
    findType(id) {
      let name = '';
      Object.keys(this.accountTypes).some(key => (this.accountTypes[key].id == id) ? name = this.accountTypes[key].title : null);
      return name;
    },
    keydown($event) {},
    submitData() {
      this.$validator.validateAll('accountForm').then(result => {
        if (result) {
          this.accForm.post('/api/account')
            .then(({
              accForm
            }) => {
              // Finish the Progress Bar
              this.$vs.notify({
                title: 'موفقیت!',
                text: 'موفقانه ثبت شد.',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
              this.accForm.reset();
              this.$validator.reset();

            }).catch((errors) => {
              this.$Progress.set(100)
              this.$vs.notify({
                title: 'ناموفق!',
                text: 'لطفاً معلومات را چک کنید و دوباره امتحان کنید!',
                color: 'danger',
                iconPack: 'feather',
                icon: 'icon-alert-triangle',
                position: 'top-right'
              })
            });
        } else {

          // form have errors
        }
      });

    },
    editAccountData() {
      this.$validator.validateAll('accountForm').then(result => {
        if (result) {
          if (this.accForm.id) {
            this.accForm.patch('/api/account/' + this.accForm.id)
              .then(({
                accForm
              }) => {
                this.$vs.notify({
                  title: 'موفقیت!',
                  text: ' حساب مذکور موفقانه ویرایش گردید .',
                  color: 'success',
                  iconPack: 'feather',
                  icon: 'icon-check',
                  position: 'top-right'
                })
                this.$emit("closeSidebar");
              }).catch((errors) => {
                this.$Progress.set(100)
                this.$vs.notify({
                  title: 'ناموفق!',
                  text: 'لطفاً معلومات را چک کنید و دوباره امتحان کنید!',
                  color: 'danger',
                  iconPack: 'feather',
                  icon: 'icon-alert-triangle',
                  position: 'top-right'
                })
              });
          } else {}
        } else {
          console.log('Error', 'Form has error')
        }
      });
    }
  },
};
</script>

<style lang="scss" scoped>
.add-new-data-sidebar {
  ::v-deep .vs-sidebar--background {
    z-index: 52010;
  }

  ::v-deep .vs-sidebar {
    z-index: 52010;
    width: 400px;
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
</style>
