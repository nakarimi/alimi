<template>
<vx-card class="no-shadow">
  <component :is="scrollbarTag" class="scroll-area--data-list-add-new" :key="$vs.rtl">
    <div class="p-6">
      <form data-vv-scope="addnewInveForm">
        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="st_code" v-validate="'required'" type="text" label="کود" v-model="form.code" />
            <span class="absolute text-danger alerttext">{{ errors.first('addnewInveForm.st_code') }}</span>
          </div>
        </div>

        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="st_name" v-validate="'required|min:2'" label="عنوان" v-model="form.name" />
            <span class="absolute text-danger alerttext">{{ errors.first('addnewInveForm.st_name') }}</span>
          </div>
        </div>

        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="st_manager" v-validate="'required'" label="مسؤل" v-model="form.manager" />
            <span class="absolute text-danger alerttext">{{ errors.first('addnewInveForm.st_manager') }}</span>
          </div>
        </div>

        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="st_phone" v-validate="'required'" label="شماره" v-model="form.phone" />
            <span class="absolute text-danger alerttext">{{ errors.first('addnewInveForm.st_phone') }}</span>
          </div>
        </div>

        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="st_address" v-validate="'required|min:3'" label="آدرس" v-model="form.address" />
            <span class="absolute text-danger alerttext">{{ errors.first('addnewInveForm.st_address') }}</span>
          </div>
        </div>

        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="st_capacity" v-validate="'required'" label="ظرفیت" v-model="form.capacity" />
            <span class="absolute text-danger alerttext">{{ errors.first('addnewInveForm.st_capacity') }}</span>
          </div>
        </div>

        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <label for="" class="vs-input--label">واحد ظرفیت</label>
            <v-select height="100px" v-model="form.oum_id" name="st_oum_id" v-validate="'required'" label="title" :options="uom" :searchable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'">
              <span slot="no-options">
                {{$t('WhoopsNothinghere')}}
              </span>
            </v-select>
            <span class="absolute text-danger alerttext">{{ errors.first('addnewInveForm.st_oum_id') }}</span>
          </div>
        </div>

        <div class="vx-row">
          <div class="vx-col w-full">
            <vs-button :disabled="form.busy" class="mr-3 mb-2" @click="submitdata">ثبت</vs-button>
            <vs-button color="warning" type="border" class="mb-2">پاک کردن فرم</vs-button>
          </div>
        </div>
      </form>
      <br>
    </div>
  </component>
</vx-card>
</template>

<script>
import vSelect from 'vue-select'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
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
    VuePerfectScrollbar,
    Validator
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      uom: [],
      form: new Form({
        id: '',
        code: '0',
        name: '',
        manager: '',
        phone: '',
        address: '',
        capacity: '0',
        oum_id: '',

      }),

      settings: { // perfectscrollbar settings
        maxScrollbarLength: 60,
        wheelSpeed: .60
      },
      dict: {
        custom: {
          st_code: { required: ' کود الزامی میباشد.' },
          st_name: { required: ' عنوان ذخیره الزامی میباشد.', min: 'عنوان ذخیره باید بیشتر از 2 حرف باشد.' },
          st_manager: { required: ' اسم شخص مسول الزامی میباشد.' },
          st_phone: { required: ' شماره تماس شخص مسول الزامی میباشد.' },
          st_address: { required: ' آدرس شخص مسول الزامی میباشد.', min: 'آدرس شخص مسول باید بیشتر از 3 حرف باشد.' },
          st_capacity: { required: ' ظرفیت ذخیره الزامی میباشد.' },
          st_oum_id: { required: ' واحد ظرفیت ذخیره الزامی میباشد.' },
        }
      }
    }
  },

  computed: {
    isSidebarActiveLocal: {
      get() {
        return this.isSidebarActive
      },
      set(val) {
        if (!val) {
          this.$emit('closeSidebar')
          // this.$validator.reset()
          // this.initValues()
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

  created() {
    Validator.localize('en', this.dict);
    this.loaduom();
  },
  methods: {
    submitdata() {
      this.$validator.validateAll('addnewInveForm').then(result => {
        if (result) {
          this.form.post('/api/storage')
            .then(() => {
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
            }).catch(() => {
              this.$vs.notify({
                title: 'عملیه ناکام شد',
                text: 'عملیه موفقانه انجام شد',
                color: 'danger',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
            })
        }
      })

    },
    loaduom() {
      this.axios.get('/api/uom').then(({
        data
      }) => (this.uom = data));
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
    width: 400px;
    max-width: 90vw;

  }
}

.scroll-area--data-list-add-new {
  // height: calc(var(--vh, 1vh) * 100 - 4.3rem);
  height: calc(var(--vh, 1vh) * 100 - 10px - 25px - 82px);

  &:not(.ps) {
    overflow-y: auto;
  }
}
</style>
