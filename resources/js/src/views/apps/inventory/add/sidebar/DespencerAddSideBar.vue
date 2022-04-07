<template>
<vs-sidebar position-right parent="body" default-index="1" color="primary" class="add-new-data-sidebar items-no-padding" spacer v-model="isSidebarActiveLocal">
  <div class="mt-6 flex items-center justify-between px-6">
    <h4>افزودن دسپنسر</h4>
    <feather-icon icon="XIcon" @click.stop="isSidebarActiveLocal = false" class="cursor-pointer"></feather-icon>
  </div>
  <div class="p-6">
    <form data-vv-scope="despenserAddForm">
      <div class="vx-row mb-6">
        <div class="vx-col w-full">
          <vs-input class="w-full" autocomplete="off" name="despenser_name" v-validate="'required'" type="text" v-model="dForm.name" label="نام" />
          <span class="absolute text-danger alerttext">{{ errors.first('despenserAddForm.despenser_name') }}</span>
          <span v-if="errorShow">
            <h5 style="color:red"></h5>
          </span>
        </div>
      </div>
      <div class="vx-row mb-6">
        <div class="vx-col w-full">
          <label for class="vs-input--label">انتخاب ذخایر</label>
          <v-select label="name" name="despenser_storage" v-validate="'required'" multiple v-model="dForm.storage_id" :options="storages" :searchable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'">
            <span slot="no-options">{{$t('WhoopsNothinghere')}}</span>
          </v-select>
          <span class="absolute text-danger alerttext">{{ errors.first('despenserAddForm.despenser_storage') }}</span>
        </div>
      </div>
      <div class="vx-row">
        <div class="vx-col w-full">
          <vs-button :disabled="dForm.busy" v-if="!is_edit" class="mr-3 mb-2 " @click.stop="submitData">ثبت</vs-button>
          <vs-button :disabled="dForm.busy" v-if="is_edit" class="mr-3 mb-2 " @click.stop="submitEdit">آپدیت</vs-button>
          <vs-button color="warning" type="border" class="mb-2" @click.stop="isSidebarActiveLocal = false">بستن فورم</vs-button>
        </div>
      </div>
      <!-- <vs-list v-if="(errors.items.length > 0)">
        <vs-list-header color="danger" title="مشکلات"></vs-list-header>
        <div :key="indextr" v-for="(error, indextr) in errors.items">
          <vs-list-item icon="verified_user" style="color:red;" :subtitle="error.msg"></vs-list-item>
        </div>
      </vs-list> -->
    </form>
  </div>
</vs-sidebar>
</template>

<script>
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
    },
    storages: {
      type: Array,
      default: () => {}
    }
  },
  components: {
    'v-select': vSelect,
    Validator
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      errorShow: false,
      dForm: new Form({
        name: '',
        station_id: null,
        storage_id: null,
      }),
      settings: { // perfectscrollbar settings
        maxScrollbarLength: 60,
        wheelSpeed: .60
      },
      is_edit: false,
      dict: {
        custom: {
          despenser_name: { required: ' اسم دیسپنسر الزامی میباشد' },
          despenser_storage: { required: ' انتخاب دخایر دیسپنسر الزامی میباشد' }
        }
      }
    }
  },
  watch: {
    isSidebarActive(val) {
      if (!val) return
      if (Object.entries(this.data).length === 0) {
        this.initValues()
        this.$validator.reset()
      } else {
        this.initValues()
      }
    }
  },
  created() {
    Validator.localize('en', this.dict);
    this.dForm.name = (this.data) ? this.data.name : '';
    this.dForm.storage_id = (this.data) ? this.data.storage_id : '';

    // this.dForm.storage_id = (this.data.storage_id) ? this.storages.filter(x => x.id == this.data.storage_id) : null;
    // console.log(this.dForm.storage_id);
    this.data.id ? this.is_edit = true : this.is_edit = false;
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
    initValues() {
      if (this.data.id) return
      this.dataId = null
      this.dataName = ''
      this.dataCategory = null
      this.dataOrder_status = 'pending'
      this.dataPrice = 0
      this.dataImg = null
    },
    submitData() {
      this.$validator.validateAll('despenserAddForm').then(result => {
        if (result) {
          this.dForm.station_id = this.data.id;
          this.$Progress.start()
          this.dForm.post('/api/despenser')
            .then((response) => {
              if (response.data.status == 'same') {
                this.dForm.reset();
                this.$validator.reset();
                this.$vs.notify({
                  title: 'ناموفق!',
                  text: 'نام دیسپنسر درعین استیشن مشابه نباشد!',
                  color: 'danger',
                  iconPack: 'feather',
                  icon: 'icon-alert-triangle',
                  position: 'top-right'
                })
              } else {
                this.dForm.reset();
                this.$validator.reset();
                this.$Progress.set(100)
                this.$vs.notify({
                  title: 'موفقیت!',
                  text: 'موفقانه ثبت شد.',
                  color: 'success',
                  iconPack: 'feather',
                  icon: 'icon-check',
                  position: 'top-right'
                })
              }

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
        }
      })
    },
    submitEdit() {
      this.$validator.validateAll('despenserAddForm').then(result => {
        if (result) {
          this.$Progress.start()
          this.dForm.patch(`/api/despenser/${this.data.id}`)
            .then((response) => {
              if (response.data.status == 'same') {
                this.$vs.notify({
                  title: 'ناموفق!',
                  text: 'نام دیسپنسر درعین استیشن مشابه نباشد!',
                  color: 'danger',
                  iconPack: 'feather',
                  icon: 'icon-alert-triangle',
                  position: 'top-right'
                })
              } else {
                this.$Progress.set(100)
                this.$vs.notify({
                  title: 'موفقیت!',
                  text: 'موفقانه آپدیت شد.',
                  color: 'success',
                  iconPack: 'feather',
                  icon: 'icon-check',
                  position: 'top-right'
                })
              }

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
        }
      })
    },
    updateCurrImg(input) {
      if (input.target.files && input.target.files[0]) {
        const reader = new FileReader()
        reader.onload = e => {
          this.dataImg = e.target.result
        }
        reader.readAsDataURL(input.target.files[0])
      }
    }
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
