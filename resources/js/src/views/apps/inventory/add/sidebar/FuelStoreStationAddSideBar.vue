<template>
<vs-sidebar position-right parent="body" default-index="1" color="primary" class="add-new-data-sidebar items-no-padding" spacer v-model="isSidebarActiveLocal">
  <div class="mt-6 flex items-center justify-between px-6">
    <h4>افزودن ذخیره جدید</h4>
    <feather-icon icon="XIcon" @click.stop="isSidebarActiveLocal = false" class="cursor-pointer"></feather-icon>
  </div>
  <component :is="scrollbarTag" class="scroll-area--data-list-add-new" :key="$vs.rtl">
    <div class="p-6">

      <!-- <vs-input class="w-full" :value="data.id" v-model="form.station_id" hidden/> -->
      <form data-vv-scope="StorageAddForm">
        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="station_name" v-validate="'required'" label="نام" v-model="form.name" />
            <span class="absolute text-danger alerttext">{{ errors.first('StorageAddForm.station_name') }}</span>
          </div>
        </div>

        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input class="w-full" name="station_supervisor" v-validate="'required'" label="مسؤل" v-model="form.supervisor" />
            <span class="absolute text-danger alerttext">{{ errors.first('StorageAddForm.station_supervisor') }}</span>
          </div>
        </div>

        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <vs-input type="number" name="station_capacity" v-validate="'required'" class="w-full" label="ظرفیت" v-model="form.capacity" />
            <span class="absolute text-danger alerttext">{{ errors.first('StorageAddForm.station_capacity') }}</span>
          </div>
        </div>

        <div class="vx-row mb-6">
          <div class="vx-col w-full">
            <label for class="vs-input--label">واحد ظرفیت</label>
            <v-select name="station_oum" v-validate="'required'" v-model="form.oum_id" label="title" :options="uom" :searchable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'">
              <span slot="no-options">{{$t('WhoopsNothinghere')}}</span>
            </v-select>
            <span class="absolute text-danger alerttext">{{ errors.first('StorageAddForm.station_oum') }}</span>
          </div>
        </div>

        <div class="vx-row">
          <div class="vx-col w-full">
            <vs-button :disabled="form.busy" v-if="!form.id" class="mr-3 mb-2" @click="submitData">ثبت</vs-button>
            <vs-button :disabled="form.busy" v-if="form.id" class="mr-3 mb-2" @click="submitEditData">ویرایش</vs-button>
            <vs-button color="warning" v-if="!form.id" type="border" class="mb-2" @click.stop="form.reset()">پاک کردن فرم</vs-button>
            <vs-button color="warning" v-if="form.id" type="border" class="mb-2" @click.stop="$emit('closeSidebar')">بستن فرم</vs-button>
          </div>
        </div>
      </form>
    </div>
  </component>
</vs-sidebar>
</template>

<script>
import vSelect from 'vue-select'
import {
  Validator
} from 'vee-validate'
export default {
  props: ['data', 'isSidebarActive'],
  components: {
    'v-select': vSelect,
    Validator
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      uom: [],
      station: [],
      form: new Form({
        id: '',
        station_id: null,
        name: '',
        supervisor: '',
        capacity: '0',
        oum_id: '',

      }),
      // End of sidebar items
      settings: { // perfectscrollbar settings
        maxScrollbarLength: 60,
        wheelSpeed: .60
      },
      dict: {
        custom: {
          station_name: {
            required: 'اسم ذخیره الزامی میباشد.'
          },
          station_supervisor: {
            required: 'اسم شخص مسول ذخیره الزامی میباشد.'
          },
          station_capacity: {
            required: 'ظرفیت ذخیره الزامی میباشد.'
          },
          station_oum: {
            required: 'واحد ظرفیت ذخیره الزامی میباشد'
          },
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
          this.$validator.reset()
          this.$emit('closeSidebar')
        }
      }
    },

    scrollbarTag() {
      return this.$store.getters.scrollbarTag
    }
  },

  created() {
    Validator.localize('en', this.dict);
    this.loaduom();
    this.loadfuelstation();
  },
  methods: {
    setFuelStoreEditData(d) {
      this.form.id = d.id
      this.form.station_id = d.station_id
      this.form.name = d.name
      this.form.supervisor = d.supervisor
      this.form.capacity = d.capacity
      this.form.oum_id = d.measur_unit
    },
    submitData() {
      this.$validator.validateAll('StorageAddForm').then(result => {
        if (result) {
          this.form.station_id = this.data.id;
          this.form.post('/api/fuelstorestation')
            .then(() => {
              this.$vs.notify({
                title: ' ذخیره جدید اضافه شد',
                text: 'عملیه موفقانه انجام شد',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
              this.form.reset();
              this.$validator.reset();
            })
            .catch(() => {})
        }
      })

    },
    submitEditData() {
      this.$validator.validateAll('StorageAddForm').then(result => {
        if (result) {
          this.form.station_id = this.data.id;
          this.form.patch('/api/fuelstorestation/' + this.form.id)
            .then(() => {
              this.$vs.notify({
                title: 'ذخیره مرورد نظر آپدیت شد.',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
            })
            .catch(() => {})
        }
      })

    },

    loaduom() {
      this.axios.get('/api/uom').then(({
        data
      }) => (this.uom = data));
    },

    loadfuelstation() {
      this.axios.get('/api/fuelstation').then(({
        data
      }) => (this.station = data));
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
  height: calc(var(--vh, 1vh) * 100 - 16px - 45px - 2px);

  &:not(.ps) {
    overflow-y: auto;
  }
}
</style>
