<template>
<vs-row vs-w="12" class="mb-4">
  <div class="vx-card">
    <div class="vx-card__header">
      <div class="vx-card__title">
        <h4 class=""> ثبت واحدات اندازه گیری </h4>
      </div>
    </div>
    <component :is="scrollbarTag" :key="$vs.rtl" class="setting-height-scroll">
      <div class="pt-6 pr-6 pl-6 pb-6">
        <div class="vx-row" v-if="user_permissions.includes('setting_uom_add')">
          <form action="" class="p-2 vx-col w-full mb-3 pr-4 pl-5 mr-3 ml-3">
            <vs-col vs-type="flex" vs-lg="6" vs-sm="12" vs-xs="12" class="p-1">
              <div class="w-full">
                <vs-input size="medium" label=" مخفف" v-model="form.acronym" name="type" class="w-full" />
              </div>
            </vs-col>
            <vs-col vs-type="flex" vs-lg="6" vs-sm="12" vs-xs="12" class="p-1">
              <div class="w-full">
                <vs-input size="medium" label="نام" v-model="form.title" name="type" class="w-full" />
              </div>
            </vs-col>

            <vs-col vs-type="flex" vs-lg="12" vs-sm="12" vs-xs="12" class="mt-4 float-left">
              <vs-button @click="submitdata" v-if="!form.id"> ثبت </vs-button>
              <vs-button @click="submitEditdata" v-if="form.id"> ویرایش </vs-button>
            </vs-col>
          </form>
        </div>
        <br>
        <vs-table :data="uom" vs-justify="center" stripe>
          <template slot="thead" vs-justify="center">
            <vs-th>شماره</vs-th>
            <vs-th>نام</vs-th>
            <vs-th>مخفف</vs-th>
            <vs-th v-if="user_permissions.includes('setting_uom_edit') ||
            user_permissions.includes('setting_uom_remove')">تنظیمات</vs-th>
          </template>
          <template slot-scope="{data}">
            <vs-tr :key="indextr" v-for="(tr, indextr) in data">
              <vs-td :data="data[indextr].type">
                {{indextr+1}}
              </vs-td>

              <vs-td :data="data[indextr].type">
                {{data[indextr].title}}
              </vs-td>

              <vs-td :data="data[indextr].type">
                {{data[indextr].acronym}}
              </vs-td>

              <vs-td v-if="user_permissions.includes('setting_uom_edit') ||
                user_permissions.includes('setting_uom_remove')">
                <feather-icon v-if="user_permissions.includes('setting_uom_edit')" icon="EditIcon" svgClasses="w-5 h-5 hover:text-primary stroke-current" class="ml-2 cursor-pointer" @click.stop="editData(data[indextr])" />
                <feather-icon v-if="user_permissions.includes('setting_uom_remove')" icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click.stop="deleteData(data[indextr].id)" />
              </vs-td>

            </vs-tr>
          </template>
        </vs-table>
      </div>
    </component>
  </div>

</vs-row>
</template>

<script>
export default {
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      uom: [],
      form: new Form({
        id: '',
        title: '',
        acronym: '',
      }),
    }
  },
  methods: {
    submitdata() {
      this.form.post('/api/uom')
        .then(() => {
          this.$vs.notify({
            title: 'نوعیت محصول اضافه شد',
            text: 'عملیه موفقانه انجام شد',
            color: 'success',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
          this.form.reset();
          this.loadItemtype();
        })

        .catch(() => {
          this.$vs.notify({
            title: 'عملیه ناکام شد',
            text: 'عملیه موفقانه انجام شد',
            color: 'danger',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
        })
    },
    submitEditdata() {
      this.form.patch('/api/uom/' + this.form.id)
        .then(() => {
          this.$vs.notify({
            title: 'نوعیت محصول آپدیت شد',
            text: 'عملیه موفقانه انجام شد',
            color: 'success',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
          this.form.reset();
          this.loadItemtype();
        })

        .catch(() => {
          this.$vs.notify({
            title: 'عملیه ناکام شد',
            text: 'عملیه موفقانه انجام نشد',
            color: 'danger',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
        })
    },
    editData(data) {
      this.form.id = data.id
      this.form.title = data.title
      this.form.acronym = data.acronym
    },
    deleteData(id) {
      swal.fire({
        title: 'آیا شما مطمئن هستید ؟',
        text: "شما قادر به برگردادن این مورد پس از حذف نمی باشید !",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#362277',
        cancelButtonColor: '#e85454',
        confirmButtonText: 'بلی مطمئن هستم',
        cancelButtonText: 'خیر'
      }).then((result) => {
        if (result.value) {
          this.axios.delete('/api/uom/' + id)
            .then((response) => {
              swal.fire(
                'حذف شد !',
                'موفقانه عملیه حذف انجام شد',
                'success'
              )
              this.loadItemtype();
            })
            .catch((error) => {
              swal.fire(
                'ناموفق !',
                'سیستم قادر به حذف نیست. وابستگی های آیتم را بررسی نمایید!',
                'error'
              )
            });
        }
      })
    },

    loadItemtype() {
      this.axios.get('/api/uom').then(({
        data
      }) => (this.uom = data));
    },

  },
  computed: {
    scrollbarTag() {
      return this.$store.getters.scrollbarTag;
    },
  },
  created() {
    this.loadItemtype();
  },
}
</script>
