<template>
<vs-sidebar position-right :click-not-close="true" parent="body" default-index="1" color="primary" class="add-new-data-sidebar items-no-padding" spacer v-model="isSidebarActiveLocal">
  <div class="mt-6 flex items-center justify-between px-6 float-right">
    <feather-icon icon="XIcon" @click.stop="isSidebarActiveLocal = false" class="cursor-pointer"></feather-icon>
  </div>

  <vs-tabs>
    <vs-tab v-if="user_permissions.includes('fuel_view')" label="لیست ">
      <Stationlist></Stationlist>
    </vs-tab>
    <vs-tab v-if="user_permissions.includes('fuel_add')" label="ثبت">
      <component :is="scrollbarTag" class="scroll-area--data-list-add-new" :key="$vs.rtl">
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
                <vs-button :disabled="form.busy" class="mr-3 mb-2" @click="submitdata">ثبت</vs-button>
                <vs-button color="warning" type="border" class="mb-2" @click="input9 = input10 = input11 = input12 = ''; check3 = false;">پاک کردن فرم</vs-button>
              </div>
            </div>
            <!--  <vs-list v-if="(errors.items.length > 0)">
              <vs-list-header color="danger" title="مشکلات"></vs-list-header>
              <div :key="indextr" v-for="(error, indextr) in errors.items">
                <vs-list-item icon="verified_user" style="color:red;" :subtitle="error.msg"></vs-list-item>
              </div>
            </vs-list>-->
          </form>
        </div>
      </component>
    </vs-tab>
  </vs-tabs>
</vs-sidebar>
</template>

<script>
import vSelect from 'vue-select'
import Stationlist from './Stationlist.vue'
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
  components: {
    'v-select': vSelect,
    Stationlist,
    Validator
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      form: new Form({
        id: '',
        name: '',
        manager: '',
        phone: '',
        address: '',
      }),
      clickNotClose: false,
      // End of sidebar items
      dataId: null,
      dataName: '',
      dataCategory: null,
      dataImg: null,
      dataOrder_status: 'pending',
      dataPrice: 0,

      settings: { // perfectscrollbar settings
        maxScrollbarLength: 60,
        wheelSpeed: .60
      },
      dict: {
        custom: {
          st_name: { required: ' نام تانگ تیل الزامی میباشد.' },
          st_manager: { required: ' نام شخص مسول الزامی میباشد.' },
          st_phone: { required: ' شماره تماس شخص مسول الزامی میباشد.' },
          st_address: { required: ' آدرس تانگ تیل الزامی میباشد.' },
        }
      }
    }
  },
  created() {
    Validator.localize('en', this.dict);
  },
  computed: {
    isSidebarActiveLocal: {
      get() {
        return this.isSidebarActive
      },
      set(val) {
        if (!val) {
          this.$validator.reset()
          this.$emit('closeSidebar')
        }
      }
    },
    isFormValid() {
      return !this.errors.any() && this.dataName && this.dataCategory && this.dataPrice > 0
    },
    scrollbarTag() {
      return this.$store.getters.scrollbarTag
    }
  },
  methods: {
    submitdata() {
      this.$validator.validateAll('fueStationAdForm').then(result => {
        if (result) {
          this.form.post('/api/fuelstation')
            .then(({
              data
            }) => {
              this.$vs.notify({
                title: 'ذخیره اضافه شد',
                text: 'عملیه موفقانه انجام شد',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
              this.form.reset();
              this.$validator.reset();
            })
            .catch(() => {
              this.$vs.notify({
                title: 'عملیه ناکام شد',
                text: 'عملیه ثبت تانگ تیل انجام نشد',
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

  }
}
</script>

<style lang="scss" scoped>
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
  height: calc(var(--vh, 1vh) * 100 - 16px - 45px - 2px);

  &:not(.ps) {
    overflow-y: auto;
  }
}
</style>
