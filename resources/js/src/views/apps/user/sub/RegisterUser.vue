<template>
<vx-card>
  <form class="pl-4 pr-4 pb-2 pt-2" autocomplete="off" data-vv-scope="userAddForm">
    <input autocomplete="off" name="hidden" type="text" style="display:none;">
    <div class="vx-row">
      <div class="vx-row w-2/3">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12" class="pl-4 pr-4 pb-2 pt-2">
          <div class="w-full">
            <vs-input class="w-full" label="نام" name="firstname" v-validate="'required'" v-model="form.firstName" />
            <span class="absolute text-danger alerttext">{{ errors.first('userAddForm.firstname') }}</span>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12" class="pl-4 pr-4 pb-2 pt-2">
          <div class="w-full">
            <vs-input class="w-full" label="تخلص" name="lastname" v-validate="'required'" v-model="form.lastName" />
            <span class="absolute text-danger alerttext">{{ errors.first('userAddForm.lastname') }}</span>
          </div>
        </vs-col>

        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12" class="pl-4 pr-4 pb-2 pt-2">
          <div class="w-full">
            <label for="text"><small>نوعیت کاربر</small> </label>
            <v-select label="text" :options="usertypes" :dir="$vs.rtl ? 'rtl' : 'ltr'" name="usertype" v-validate="'required'" v-model="form.user_type" />
            <span class="absolute text-danger alerttext">{{ errors.first('userAddForm.usertype') }}</span>
          </div>
        </vs-col>

        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12" class="pl-4 pr-4 pb-2 pt-2">
          <div class="w-full">
            <vs-input class="w-full" type="text" label="پوسیشن" name="position" v-validate="'required'" v-model="form.position" />
            <span class="absolute text-danger alerttext">{{ errors.first('userAddForm.position') }}</span>
          </div>
        </vs-col>

        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12" class="pl-4 pr-4 pb-2 pt-2">
          <div class="w-full">
            <vs-input class="w-full" type="email" label="ایمیل" name="emailadress" v-validate="'required'" v-model="form.email" />
            <span class="absolute text-danger alerttext">{{ errors.first('userAddForm.emailadress') }}</span>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12" class="pl-4 pr-4 pb-2 pt-2">
          <div class="w-full">
            <vs-input class="w-full" type="text" label="شماره تماس" name="phoneno" v-validate="'required'" v-model="form.phone" />
            <span class="absolute text-danger alerttext">{{ errors.first('userAddForm.phoneno') }}</span>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12" class="pl-4 pr-4 pb-2 pt-2">
          <div class="w-full">
            <vs-input class="w-full" type="text" label="آدرس" name="address" v-validate="'required'" v-model="form.address" autocomplete="off" autofill="off" />
            <span class="absolute text-danger alerttext">{{ errors.first('userAddForm.address') }}</span>
          </div>
        </vs-col>

        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12" class="pl-4 pr-4 pb-2 pt-2">
          <div class="w-full">
            <vs-input class="w-full" type="password" label="رمز عبور" name="passowrd" v-validate="'required'" v-model="form.password" />
            <span class="absolute text-danger alerttext">{{ errors.first('userAddForm.passowrd') }}</span>
          </div>
        </vs-col>

        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12" class="pl-4 pr-4 pb-2 pt-2">
          <div class="w-full con-select-example">
            <label class="typo__label">مافوق برای کابر</label>
            <v-select label="firstName" multiple name="userleaders" v-validate="'required'" v-model="form.userleaders" :options="users" :searchable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'">
              <span slot="no-options">{{$t('WhoopsNothinghere')}}</span>
            </v-select>
            <span class="absolute text-danger alerttext">{{ errors.first('userAddForm.userleaders') }}</span>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12" class="pl-4 pr-4 pb-2 pt-2">
          <div class="w-full">
            <vs-button class="w-full mt-6 input-height" @click.stop="setPrivileges(form)">صلاحیت های کابر</vs-button>
          </div>
        </vs-col>
      </div>
      <div class="vx-col w-1/3">
        <!-- Product Image -->
        <template v-if="form.image">
          <!-- Image Container -->
          <div class="img-container w-64 mx-auto flex items-center justify-center mt-5">
            <img :src="form.image" width="50px" height="50px" alt="img" class="user_image responsive" />
          </div>

          <!-- Image upload Buttons -->
          <div class="modify-img flex justify-between mt-5">
            <input type="file" class="hidden" ref="updateImgInput" @change="updateCurrImg" accept="image/*" />
            <vs-button class="mr-4" type="flat" @click="$refs.updateImgInput.click()">تغییر</vs-button>
            <vs-button type="flat" color="#999" @click="form.image = null">حذف</vs-button>
          </div>
        </template>
        <!-- Upload -->

        <!-- <vs-upload text="Upload Image" class="img-upload" ref="fileUpload" /> -->

        <div class="upload-img mt-5 text-center" v-if="!form.image">
          <input type="file" class="hidden" ref="uploadImgInput" @change="updateCurrImg" accept="image/*" />
          <vs-button @click="$refs.uploadImgInput.click()">آپلود تصویر</vs-button>
        </div>

        <!-- Product Image -->
        <template v-if="!form.image">
          <div class="mt-4">
            <!-- Image Container -->
            <div class="img-container w-64 mx-auto flex items-center justify-center">
              <img src="/images/user/user.jpg" width="50px" height="50px" alt="img" class="user_image responsive" />
            </div>

          </div>
        </template>
        <!-- Upload -->
      </div>
    </div>
    <div class="vx-row">
      <div class="vx-col pt-5">
        <div class="w-full">
          <vs-button class="mr-3 mb-2" @click.prevent="submitData">ثبت</vs-button>
          <vs-button color="warning" type="border" class="mb-2" @click="formreset">پاک کردن</vs-button>
        </div>
      </div>
    </div>
    <!-- <vs-list v-if="(errors.items.length > 0)">
      <vs-list-header color="danger" title="مشکلات"></vs-list-header>
      <div :key="indextr" v-for="(error, indextr) in errors.items">
        <vs-list-item icon="verified_user" style="color:red;" :subtitle="error.msg"></vs-list-item>
      </div>
       -->
    </vs-list>
  </form>
  <vs-popup class="holamundo width-60" title="تنظیمات صلاحیت ها" :active.sync="privilegeModalActive">
    <privilege-setting ref="userprivilege" :source="[]" @closeModal="assignPrivileges" />
  </vs-popup>
</vx-card>
</template>

<script>
import vSelect from "vue-select";
import Multiselect from 'vue-multiselect'
import PrivilegeSetting from './PrivilegeSetting'
import {
  Validator
} from 'vee-validate'
export default {
  components: {
    PrivilegeSetting,
    "v-select": vSelect,
    Multiselect,
    Validator
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      users: [],
      user: null,
      privilegeModalActive: false,
      usertypes: [{
          text: 'آدمین',
          value: 0
        },
        {
          text: 'منیجر ',
          value: 2
        },
        {
          text: 'سوپروایزر',
          value: 3
        },

        {
          text: 'کابر عادی',
          value: 4
        },
      ],
      form: new Form({
        id: '',
        firstName: '',
        lastName: '',
        user_type: '',
        position: '',
        email: '',
        phone: '',
        address: '',
        password: '',
        image: '',
        userleaders: null,
        privileges: [],
      }),
      uservalidatedata: {
        custom: {
          firstname: {
            required: ' نام استفاده کننده الزامی میباشد.'
          },
          lastname: {
            required: ' تخلص استفاده کننده الزامی میباشد.'
          },
          usertype: {
            required: ' نوع استفاده کننده الزامی میباشد.'
          },
          position: {
            required: ' موقعیت استفاده کننده الزامی میباشد.'
          },
          emailadress: {
            required: ' ایمیل استفاده کننده الزامی میباشد.'
          },
          phoneno: {
            required: ' شماره تماس استفاده کننده الزامی میباشد.'
          },
          address: {
            required: ' آدرس استفاده کننده الزامی میباشد.'
          },
          passowrd: {
            required: ' رمز عبور استفاده کننده الزامی میباشد.'
          },
          userleaders: {
            required: ' مافوق استفاده کننده الزامی میباشد.'
          },
        }
      }
    }
  },

  methods: {
    setPrivileges(user) {
      this.$refs.userprivilege.getPermissions(null);
    },
    assignPrivileges(data) {
      this.privilegeModalActive = !this.privilegeModalActive
      this.form.privileges = this.$refs.userprivilege.privileges.filter((e) => e.assign === true)
    },
    loadUsers() {
      this.axios.get('/api/users').then(({
        data
      }) => (this.users = data));
    },
    formreset() {
      this.form.reset();
      this.$validator.reset();
    },
    submitData() {
      this.$validator.validateAll('userAddForm').then(result => {
        if (result) {
          this.form.post('/api/users')
            .then(() => {
              this.$vs.notify({
                title: 'عملیه ثبت موفق بود!',
                text: 'عملیه موفقانه انجام شد',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
              this.formreset();
            })
            .catch(() => {
              this.$vs.notify({
                title: 'Failed!',
                text: 'There is some failure, please try again!',
                color: 'danger',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-left'
              })
            })
        } else {
          console.log('ERROR', 'FORMS HAS ERRORS')
        }
      })
    },

    initValues() {
      this.form.image = null
    },
    updateCurrImg(input) {
      if (input.target.files && input.target.files[0]) {
        const reader = new FileReader()
        reader.onload = e => {
          this.form.image = e.target.result
        }
        reader.readAsDataURL(input.target.files[0])
      }
    }
  },

  created() {
    Validator.localize('en', this.uservalidatedata);
    this.loadUsers();
  },
}
</script>
