<template>
<div>
  <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="6" vs-sm="12" vs-xs="12">
    <vs-row vs-w="12" class="mb-4">
      <div class="w-full pt-2 ml-3 mr-3">
        <div class="vx-card">
          <div class="vx-card__header">
            <div class="vx-card__title">
              <h4 class="">نوعیت حساب ها</h4>
            </div>
            <vs-button v-if="user_permissions.includes('setting_acc_type_add')" type="border" @click="acountActiveForm = !acountActiveForm, acountTypeForm.reset()">{{ acountActiveForm ? 'بستن فورم' : 'افزودن نوع حساب جدید' }}</vs-button>
          </div>
          <component :is="scrollbarTag" :key="$vs.rtl" class="setting-height-scroll">
            <div class="pt-6 pr-6 pl-6 pb-6">
              <div class="vx-row">
                <div class="vx-col w-full mb-base" v-if="acountActiveForm">
                  <div class="vx-col pr-4 pl-5 mr-3 ml-3">
                    <div class="vx-col sm:w-1/3 w-full">
                      <span>عنوان</span>
                    </div>
                    <div class="vx-col w-full">
                      <vs-input class="w-full" v-model="acountTypeForm.title" />
                      <has-error :form="acountTypeForm" field="title"></has-error>
                    </div>
                  </div>
                  <div class="vx-col pt-4 pb-5 pr-4 pl-5 mr-3 ml-3">
                    <div class="vx-col w-full">
                      <vs-checkbox color="success" size="large" v-model="have_type"> نوعیت دارد ! </vs-checkbox>
                    </div>
                  </div>
                  <div class="vx-col pt-2 pb-4 pr-4 pl-5 mr-3 ml-3" v-if="have_type">
                    <label for="title"><small>نوعیت</small> </label>
                    <v-select label="title" :options="accountTypes" :dir="$vs.rtl ? 'rtl' : 'ltr'" v-model="acountTypeForm.type_id" />
                  </div>
                  <div class="vx-col w-full mb-base mt-5 pr-4 pl-4 mr-3 ml-3">
                    <vs-button v-if="!acountTypeForm.id" class="shadow-md w-full lg:mt-0 mt-4 mr-3 mb-2" @click="storeAccountType">ثبت عملیه</vs-button>
                    <vs-button v-if="acountTypeForm.id" class="shadow-md w-full lg:mt-0 mt-4 mb-2" @click="updateAccountType">آپدیت عملیه</vs-button>
                  </div>
                </div>
              </div>
              <br>
              <vs-table :data="accountTypes" stripe>
                <template slot="thead">
                  <vs-th>#</vs-th>
                  <vs-th>نام</vs-th>
                  <vs-th>نوعیت</vs-th>
                  <vs-th>سیستم</vs-th>
                  <vs-th v-if="
                    user_permissions.includes('setting_acc_type_add') ||
                    user_permissions.includes('setting_acc_type_edit') ||
                    user_permissions.includes('setting_acc_type_remove')">عملیه</vs-th>
                </template>

                <template slot-scope="{data}">
                  <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                    <vs-td :data="tr.id">
                      {{ ++indextr }}
                    </vs-td>
                    <vs-td :data="tr.id">
                      {{ tr.title }}
                    </vs-td>

                    <vs-td :data="tr.id">
                      <span v-if="!(tr.system == 1)">
                        <div v-for="(item,index) in accountTypes" :key="index">
                          <span v-if="(tr.type_id==item.id)">
                            <span v-text="item.title"></span>
                          </span>
                        </div>
                      </span>
                      <span v-if="(tr.system == 1)"> سیستم </span>
                    </vs-td>

                    <vs-td :data="tr.id">
                      <span v-if="(tr.system == 1)">است</span>
                      <span v-if="(tr.system == 0)">نیست</span>
                    </vs-td>
                    <vs-td v-if="
                      user_permissions.includes('setting_acc_type_add') ||
                      user_permissions.includes('setting_acc_type_edit') ||
                      user_permissions.includes('setting_acc_type_remove')" :data="tr.id">
                      <div class="flex">
                        <feather-icon v-if="user_permissions.includes('setting_acc_type_edit')" icon="EditIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="cursor-pointer" @click.stop="editAccountType(tr)" />&nbsp;&nbsp;
                        <span v-if="user_permissions.includes('setting_acc_type_remove')">
                          <span v-if="(tr.system ==1)" style="padding-top:9px;">
                            <feather-icon disabled icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="cursor-pointer" @click.stop="deleteAccountType(tr.id)" />
                          </span>
                          <span v-if="!(tr.system ==1)" style="padding-top:9px;">
                            <feather-icon icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="cursor-pointer" @click.stop="deleteAccountType(tr.id)" />
                          </span>
                        </span>
                      </div>
                    </vs-td>
                  </vs-tr>
                </template>
              </vs-table>
            </div>
          </component>
        </div>
      </div>
      <div class="w-full pt-2 ml-3 mr-3">
        <ItemType></ItemType>
      </div>
    </vs-row>
  </vs-col>
  <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="6" vs-sm="12" vs-xs="12">
    <vs-row vs-w="12" class="mb-3">
      <div class="w-full pt-2 ml-3 mr-3">
        <div class="vx-card">
          <div class="vx-card__header">
            <div class="vx-card__title">
              <h4 class="">واحدهای پولی </h4>
            </div>
            <vs-button v-if="user_permissions.includes('setting_currency')" type="border" @click="currencyActiveChange = !currencyActiveChange, operationForm.reset()">{{ currencyActiveChange ? 'بستن فورم' : 'اپدیت نرخ اسعار' }}</vs-button>
          </div>
          <br>
          <!-- Add New Currency -->
          <div v-if="!exRateForm">
            <div class="vx-card__collapsible-content vs-con-loading__container">
              <div class="vx-card__body">
                <div class="w-full mb-base">
                  <div class="vx-row">
                    <div class="vx-col w-full">
                      <div class="vx-col w-full">
                        <span>نشان انگلیسی</span>
                      </div>
                      <div class="vx-col w-full">
                        <vs-input class="w-full" v-model="currencyForm.sign_en" />
                        <has-error :form="currencyForm" field="sign_en"></has-error>
                      </div>
                    </div>
                    <div class="vx-col w-full md:w-1/2">
                      <div class="vx-col sm:w-1/3 w-full">
                        <span>نشان دری</span>
                      </div>
                      <div class="vx-col w-full">
                        <vs-input class="w-full" v-model="currencyForm.sign_fa" />
                        <has-error :form="currencyForm" field="sign_fa"></has-error>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="vx-row">
                  <div class="vx-col w-full md:w-1/2">
                    <div class="vx-col sm:w-1/3 w-full">
                      <span>نرخ</span>
                    </div>
                    <div class="vx-col w-full">
                      <vx-input-group class="">
                        <template slot="prepend">
                          <div class="prepend-text bg-primary">
                            <span>AFN</span>
                          </div>
                        </template>
                        <vs-input type="number" v-model="currencyForm.rate" class="number-rtl" />
                      </vx-input-group>

                      <has-error :form="currencyForm" field="rate"></has-error>
                    </div>
                  </div>
                  <div class="vx-col w-full md:w-1/2 mb-base mt-5">
                    <vs-button class="shadow-md w-full lg:mt-0 mt-4 mr-3 mb-2" @click="addNewCurrency">ثبت</vs-button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End of Add Currency -->
          <!-- Edit rates -->
          <div v-if="currencyActiveChange">
            <vs-divider></vs-divider>
            <div class="vx-card__collapsible-content vs-con-loading__container">
              <div class="vx-card__body">
                <div class="vx-col w-full mb-base">
                  <div v-for="(item, index) in rateEditForm.currencies" :key="item.id">
                    <div class="vx-row mb-6">
                      <div class="vx-col sm:w-1/3 w-full">
                        <span>{{ item.sign_fa }}</span>
                      </div>
                      <div class="vx-col sm:w-2/3 w-full">
                        <vx-input-group class="">
                          <template slot="prepend">
                            <div class="prepend-text bg-primary">
                              <span>AFN</span>
                            </div>
                          </template>
                          <span v-if="(item.sign_en == 'AFN')">
                            <vs-input type="number" disabled v-model="rateEditForm.currencies[index].last_rate.rate" class="number-rtl" /></span>
                          <span v-if="!(item.sign_en == 'AFN')">
                            <vs-input type="number" v-model="rateEditForm.currencies[index].last_rate.rate" class="number-rtl" /></span>
                        </vx-input-group>
                      </div>
                    </div>
                  </div>
                  <div class="vx-col w-full md:w-1/2 mb-base mt-5 float-right">
                    <vs-button class="shadow-md w-full lg:mt-0 mt-4 mr-3 mb-2" @click="editRates">آپدیت نرخ اسعار</vs-button>
                  </div>

                </div>
              </div>
            </div>
          </div>
          <!-- Currency Table -->
          <vs-table :data="currencies" class="overflow-hidden-x">

            <template slot="thead">
              <vs-th>#</vs-th>
              <vs-th>نشان انگلیسی</vs-th>
              <vs-th>نشان دری</vs-th>
              <vs-th>نرخ</vs-th>
              <vs-th>تاریخ</vs-th>
            </template>

            <template slot-scope="{data}">
              <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                <vs-td :data="tr.id">
                  {{ tr.id }}
                </vs-td>
                <vs-td :data="tr.sign_en">
                  {{ tr.sign_en }}
                </vs-td>
                <vs-td :data="tr.sign_fa">
                  {{ tr.sign_fa }}
                </vs-td>
                <vs-td :data="tr.last_rate">
                  {{ tr.last_rate.rate }}
                </vs-td>
                <vs-td :data="tr.last_rate">
                  {{ new Date(tr.last_rate.created_at).toLocaleString('fa-AF', { hour12: true }) }}
                </vs-td>
              </vs-tr>
            </template>

          </vs-table>
          <!-- end -->
        </div>
      </div>
      <div class="w-full pt-2 ml-3 mr-3">
        <Operations></Operations>
      </div>
      <div class="w-full pt-2 ml-3 mr-3">
        <Uom></Uom>
      </div>
      <div class="w-full pt-2 ml-3 mr-3">
        <Companies></Companies>
      </div>
    </vs-row>
  </vs-col>
  <vs-divider />
</div>
</template>

<script>
import ItemType from './ItemType'
import Uom from "./Uom"
import vSelect from 'vue-select'
import Companies from './Companies'
import Operations from './Operations'
export default {
  components: {
    ItemType,
    Uom,
    'v-select': vSelect,
    Companies,
    Operations
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      exRateForm: true,
      currencyActiveChange: false,
      acountActiveForm: false,
      currencies: [],
      currencyForm: new Form({
        sign_en: '',
        sign_fa: '',
        id: null,
        rate: '',
        user_id: localStorage.getItem('id'),
      }),
      rateEditForm: new Form({
        currencies: null,
        user_id: localStorage.getItem('id'),
      }),
      operations: [],
      operationForm: new Form({
        title: '',
        formula: '',
        description: '',
        id: null,
        user_id: localStorage.getItem('id'),
      }),

      // select1: 10,
      // Account_type: By Ahamadi
      accountTypes: [],
      acountTypeForm: new Form({
        id: null,
        title: '',
        type_id: '',
        system: 0,
        user_id: localStorage.getItem('id'),
      }),
      have_type: 0,
    };
  },
  computed: {
    scrollbarTag() {
      return this.$store.getters.scrollbarTag;
    },
  },
  created() {
    this.$Progress.start();
    this.getAllCurrency();
    this.getAllAccountTypes();
  },
  methods: {
    getAllCurrency() {
      this.axios.get('/api/currency')
        .then((response) => {
          this.currencies = response.data;
          this.rateEditForm.currencies = JSON.parse(JSON.stringify(response.data));
        })
    },
    updateCancel() {
      this.currencyForm.reset();
    },
    editCurrency(data) {
      this.currencyForm.sign_en = data.sign_en;
      this.currencyForm.sign_fa = data.sign_fa;
      this.currencyForm.id = data.id;
    },

    getAllCurrency() {
      this.axios.get('/api/currency')
        .then((response) => {
          this.currencies = response.data;
          this.rateEditForm.currencies = JSON.parse(JSON.stringify(response.data));
        })
    },
    updateCancel() {
      this.currencyForm.reset();
    },
    editCurrency(data) {
      this.currencyForm.sign_en = data.sign_en;
      this.currencyForm.sign_fa = data.sign_fa;
      this.currencyForm.id = data.id;
    },
    addNewCurrency() {
      // Start the Progress Bar
      this.currencyForm.post('/api/currency')
        .then(({
          data
        }) => {
          // 
          this.currencyForm.currency_id = data.id;
          this.getAllCurrency();
          this.currencyForm.reset();
          this.$vs.notify({
            title: 'موفقیت!',
            text: 'معلومات موفقانه ثبت سیستم شد.',
            color: 'success',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
        }).catch((errors) => {

          this.$vs.notify({
            title: 'ناموفق!',
            text: 'لطفاً معلومات را چک کنید و دوباره امتحان کنید!',
            color: 'danger',
            iconPack: 'feather',
            icon: 'icon-alert-triangle',
            position: 'top-right'
          })
        });
    },
    editRates() {
      // 
      this.rateEditForm.post('/api/currency/rates')
        .then(({
          data
        }) => {
          // 
          this.getAllCurrency();
          this.currencyForm.reset();
          this.$vs.notify({
            title: 'موفقیت!',
            text: 'معلومات موفقانه ثبت سیستم شد.',
            color: 'success',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
        }).catch((errors) => {
          this.$vs.notify({
            icon: 'icon-alert-triangle',
            position: 'top-right'
          })
        });
    },

    // by ahmadi...

    // 1- store AccountType 
    storeAccountType() {
      this.acountTypeForm.post('/api/acount_type')
        .then(({
          data
        }) => {
          this.getAllAccountTypes();
          this.accountTypes.reset();
          this.$vs.notify({
            title: 'موفقیت!',
            text: 'معلومات موفقانه ثبت سیستم شد.',
            color: 'success',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
        }).catch((errors) => {
          // this.$vs.notify({
          //     title: 'ناموفق!',
          //     text: 'لطفاً معلومات را چک کنید و دوباره امتحان کنید!',
          //     color: 'danger',
          //     iconPack: 'feather',
          //     icon: 'icon-alert-triangle',
          //     position: 'top-right'
          // })

          this.$vs.notify({
            title: 'موفقیت!',
            text: 'معلومات موفقانه ثبت سیستم شد.',
            color: 'success',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
        });
    },
    // 2- Account Types
    getAllAccountTypes() {
      this.axios.get('/api/account_type')
        .then((response) => {
          this.accountTypes = response.data;
        })
    },
    // 3- show edit operation
    editAccountType(data) {
      this.acountActiveForm = true;
      this.acountTypeForm.title = data.title;
      this.acountTypeForm.type_id = data.type_id;
      this.acountTypeForm.system = data.system;
      this.acountTypeForm.id = data.id;
      this.have_type = (this.acountTypeForm.type_id) ? true : false;
    },
    // 4- update the info
    updateAccountType() {
      this.acountTypeForm.put('/api/acount_type/' + this.acountTypeForm.id)
        .then(({
          data
        }) => {
          this.getAllAccountTypes();
          this.$vs.notify({
            title: 'موفقیت!',
            text: 'آیتم موفقانه آپدیت شد.',
            color: 'success',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
        }).catch(error => {
          swal.fire({
            title: 'خطا!',
            text: "عملیه آپدیت انجام نشد. همه فیلدها را بررسی کنید.",
            icon: 'error',
            confirmButtonColor: '#e85454',
            confirmButtonText: 'بستن',
          })
        });
    },
    // 5- deleteAccountType
    deleteAccountType(id) {
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
          this.axios.delete('/api/acount_type/' + id)
            .then((response) => {
              swal.fire(
                'حذف شد !',
                'موفقانه عملیه حذف انجام شد',
                'success'
              )
              this.getAllAccountTypes();
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
  },
  watch: {
    'have_type'() {
      if (!this.have_type) {
        this.acountTypeForm.type_id = '';
      }
    }
  }
  // End Of Methods
}
</script>
