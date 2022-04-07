<template>
<vs-row>
  <div class="vx-card">
    <div class="vx-card__header">
      <div class="vx-card__title">
        <h4 class="">نوعیت محصولات </h4>
      </div>

    </div>
    <component :is="scrollbarTag" :key="$vs.rtl" class="setting-height-scroll">
      <div class="pt-6 pr-6 pl-6 pb-6">
        <div class="vx-row" v-if="user_permissions.includes('setting_item_type_add', 'setting_item_type_edit')">
          <form action="" class="p-2 vx-col w-full mb-3 pr-4 pl-5 mr-3 ml-3">
            <vs-col vs-type="flex" vs-lg="9" vs-sm="12" vs-xs="12">
              <div class="w-full">
                <vs-input size="medium" label="عنوان" v-model="form.type" name="type" required class="w-full" />
              </div>
            </vs-col>
            <vs-col vs-type="flex" vs-lg="3" vs-sm="12" vs-xs="12" class="mt-4 float-left">
              <vs-button @click="submitdata" v-if="!form.id"> ثبت </vs-button>
              <vs-button @click="submitEdotdata" v-if="form.id"> ویرایش </vs-button>
            </vs-col>
          </form>
        </div>
        <div class="vx-row">
          <vs-alert :active.sync="showExistMSG" closable close-icon="close" class="mr-3 ml-3">
            <p style="color:red">نوعیت مذکور به جای دیگری استفاده شده است و قابل حذف نیست</p>
          </vs-alert>
        </div>
        <br>
        <vs-table :data="itemtype" vs-justify="center" stripe>
          <template slot="thead" vs-justify="center">
            <vs-th> شماره</vs-th>
            <vs-th>نام</vs-th>
            <vs-th v-if="user_permissions.includes('setting_item_type_remove') || user_permissions.includes('setting_item_type_edit')"> تنظیمات</vs-th>
          </template>

          <template slot-scope="{data}">
            <vs-tr :key="indextr" v-for="(tr, indextr) in data">
              <vs-td :data="data[indextr].type">
                {{indextr+1}}
              </vs-td>

              <vs-td :data="data[indextr].type">
                {{data[indextr].type}}
              </vs-td>
              <vs-td v-if="user_permissions.includes('setting_item_type_remove') || user_permissions.includes('setting_item_type_edit')">
                <feather-icon v-if="user_permissions.includes('setting_item_type_edit')" icon="EditIcon" svgClasses="w-5 h-5 hover:text-primary stroke-current" class="cursor-pointer" @click.stop="editData(data[indextr])" />
                <feather-icon v-if="user_permissions.includes('setting_item_type_remove')" icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click.stop="deleteData(data[indextr].id)" />
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
      showExistMSG: false,
      itemtype: [],
      form: new Form({
        id: '',
        type: '',
      }),
    }
  },
  computed: {
    scrollbarTag() {
      return this.$store.getters.scrollbarTag;
    },
  },
  methods: {
    submitdata() {
      this.form.post('/api/itemtype')
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
    submitEdotdata() {
      this.form.patch('/api/itemtype/' + this.form.id)
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
            color: 'danger',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
        })
    },
    editData(data) {
      this.form.id = data.id;
      this.form.type = data.type;
    },
    deleteData(id) {
      swal.fire({
        title: 'آیا شما مطمئن هستید ؟',
        text: "شما قادر به برگردادن این عملیه پس از حذف نمی باشید !",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#362277',
        cancelButtonColor: '#e85454',
        confirmButtonText: 'بلی مطمئن هستم',
        cancelButtonText: 'خیر'
      }).then((result) => {
        if (result.value) {
          this.axios.delete('/api/itemtype/' + id)
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
      this.axios.get('/api/itemtype').then(({
        data
      }) => (this.itemtype = data));
    },

  },

  created() {
    this.loadItemtype();
  },
}
</script>
