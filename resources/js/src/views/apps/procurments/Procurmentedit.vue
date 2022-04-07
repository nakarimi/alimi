<template>
<div>
  <SerllerAddForm :isSidebarActive="addNewDataSidebar" @closeSidebar="toggleDataSidebar" :data="sidebarData" />
  <div class="vx-row">
    <vx-card class="height-vh-80">
      <div class="vx-row">
        <div class="vx-col w-1/2">
          <h3>فورم ثبت خریداری</h3>
        </div>
        <div class="vx-col w-1/2">
          <vs-button type="filled" v-if="user_permissions.includes('vendor_add')" @click="addNewData" class="mt-5 float-right">ثبت فروشنده جدید</vs-button>
        </div>
      </div>

      <form data-vv-scope="procurmentForm">
        <vs-row vs-w="12">
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
            <div class="w-full pt-2 ml-3 mr-3">
              <vs-input size="medium" v-validate="'required'" label="سریال نمبر" name="serialnumber" v-model="prForm.serial_no" class="w-full" disabled />
            </div>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
            <div class="w-full pt-2 ml-3 mr-3">
              <label for>واحد پولی</label>
              <div class="radio-group w-full">
                <div class="w-1/2">
                  <input type="radio" id="afn" name="currency" value="1" v-model="prForm.currency_id" />
                  <label for="afn" class="w-full text-center">افغانی</label>
                </div>
                <div class="w-1/2">
                  <input type="radio" id="usd" name="currency" value="2" v-model="prForm.currency_id" />
                  <label for="usd" class="w-full text-center">دالر</label>
                </div>
              </div>
            </div>
            <span class="absolute text-danger alerttext">{{ errors.first('procurmentForm.currency') }}</span>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
            <div class="w-full  ml-3 mr-3">
              <label for="date" class="mt-3"><small>تاریخ </small></label>
              <date-picker color="#e85454" name="procurment_date" v-validate="'required'" input-format="YYYY/MM/DD HH:mm" format="jYYYY/jMM/jDD HH:mm" type="datetime" v-model="prForm.date_time" />
              <span class="absolute text-danger alerttext">{{ errors.first('procurmentForm.procurment_date') }}</span>
            </div>
          </vs-col>

          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
            <div class="w-full pt-2 ml-3 mr-3">
              <label for>
                <small> نام فروشنده</small>
              </label>
              <v-select label="name" name="seller_name" v-validate="'required'" v-model="prForm.vendor_name" :options="allvendors" :dir="$vs.rtl ? 'rtl' : 'ltr'" @input="setVendordata" />
              <span class="absolute text-danger alerttext">{{ errors.first('procurmentForm.seller_name') }}</span>
            </div>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
            <div class="w-full pt-2 ml-3 mr-3">
              <vs-input size="medium" v-validate="'required'" label="شماره تماس" name="seller_phone" class="w-full" v-model="prForm.vendor_phone" />
              <span class="text-danger text-sm" v-show="errors.has('seller_phone')">{{ errors.first("seller_phone") }}</span>
            </div>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
            <div class="w-full pt-2 ml-3 mr-3">
              <vs-input size="medium" v-validate="'required'" label="آدرس" name="address" class="w-full" v-model="prForm.vendor_address" />
              <span class="text-danger text-sm" v-show="errors.has('address')">{{ errors.first("address") }}</span>
            </div>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
            <div class="w-full pt-2 ml-3 mr-3">
              <label for>
                <small>ذخیره اصلی</small>
              </label>
              <source-select ref="str" :parentForm="prForm" @updateItems="update_items" name="source" v-validate="'required'" v-model="prForm.source_id"></source-select>
              <span class="absolute text-danger alerttext">{{ errors.first('procurmentForm.source') }}</span>
            </div>
          </vs-col>
        </vs-row>
        <br>
        <vs-divider />
        <EkmalatStock :items="prForm.item" :form="prForm" :currencyID="prForm.currency_id" :listOfFields="[]" :disabledFields="[]" :grid="[]" ref="ekmalat"></EkmalatStock>
        <vs-row vs-w="12">
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="12" vs-sm="12" vs-xs="12">
            <div class="w-full pt-2 ml-3 mr-3">
              <vs-textarea label="تفصیلات" :rows="prForm.description && prForm.description.split(`\n`).length > 2 ? prForm.description.split(`\n`).length + 1 : 3" v-model="prForm.description"></vs-textarea>
            </div>
          </vs-col>
        </vs-row>
        <vs-button class="mr-3 mb-2" :disabled="prForm.busy" @click.prevent="submitData">ثبت</vs-button>
      </form>
    </vx-card>
  </div>
</div>
</template>

<script>
import SerllerAddForm from './SerllerAddForm.vue'
import moment from 'moment-jalaali'
import EkmalatStock from "../shared/EkmalatStock"
import SourceSelect from "../shared/SourceSelect";
import vSelect from 'vue-select'
import {
  Validator
} from 'vee-validate'

export default {
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      active: true,
      showEr: true,
      // Data Sidebar
      addNewDataSidebar: false,
      addNewDataSidebar: false,
      sidebarData: {},

      allvendors: [],
      storage: [],
      prForm: new Form({
        serial_no: null,
        currency_id: 1,
        date_time: this.momentj().format('jYYYY/jMM/jDD HH:mm'), //new Date().getTime().formatdate('YYYY/MM/DD'),
        vendor_id: '',
        account_id: '',
        vendor_address: '',
        vendor_phone: '',
        vendor_name: '',
        source: '',
        source_id: '',
        source_type: '',
        user_id: localStorage.getItem('id'),
        description: '',
        item: [{
          item_id: "",
          unit_id: "",
          operation_id: null,
          increment_equiv: "",
          increment: "",
          unit_price: "",
          total_price: "",
          density: 1,
        }],

      }),
      dictp: {
        custom: {
          procurment_date: {
            required: ' تاریخ انجام خریداری الزامی میباشد.'
          },
          seller_name: {
            required: ' نام فروشنده الزامی میباشد.'
          },
          seller_phone: {
            required: ' شماره تماس فروشنده الزامی میباشد.'
          },
          address: {
            required: ' آدرس فروشنده الزامی میباشد.',
            min: 'آدرس فروشنده باید بیشتر از 2 حرف باشد.'
          },
          source: {
            required: ' انتخاب ذخیره اصلی الزامی میباشد.'
          }
        }
      },

    }
  },
  watch: {
    addNewDataSidebar: function () {
      if (this.addNewDataSidebar == false) {
        this.$validator.reset();
        this.showEr = true;
      } else {
        this.showEr = false;
      }
    },

  },
  created() {
    this.$on('sellerdelete', function (value) {
      this.loadvendor();
    });
    this.$on('selleradd', function (value) {
      this.loadvendor();
    });
    Validator.localize('en', this.dictp);
    this.loadvendor();
    this.getProcurment();
  },
  components: {
    "v-select": vSelect,
    EkmalatStock,
    moment,
    SourceSelect,
    Validator,
    SerllerAddForm
  },
  methods: {
    update_items(matched_items) {
      this.$refs.ekmalat.getAllItems(matched_items, false);
    },
    toggleDataSidebar(val = false) {
      this.loadvendor();
      this.$validator.reset()
      this.addNewDataSidebar = val
    },
    addNewData() {
      this.sidebarData = {}
      this.toggleDataSidebar(true)
    },
    setVendordata(data) {
      this.prForm.vendor_id = data.id;
      this.prForm.vendor_address = data.address;
      this.prForm.vendor_phone = data.phone;
      this.prForm.account_id = data.account_id;
      this.prForm.vendor_name = data.name;
      console.log(this.prForm);
    },
    loadvendor() {
      this.axios.get('/api/vendors')
        .then((resp) => {
          this.allvendors = resp.data;
        });
    },
    getProcurment() {
      this.axios.get('/api/procurments/' + this.$route.params.id + '/edit')
        .then((resp) => {
          if (this.$refs.ekmalat) {
            this.$refs.ekmalat.resetArrays()
            let d = resp.data;
            this.prForm.serial_no = d.serial_no;
            this.prForm.currency_id = d.currency_id;
            this.prForm.date_time = d.date_time;
            this.setVendordata(d.vendor);
            this.prForm.description = d.description;
            if (this.$refs.str) {
              this.$refs.str.findAndSet(d.source_id, d.source_type);
            }
            this.prForm.item = [];
            this.prForm.item = d.items;
            this.setProcurmentItems();
          }
          document.getElementById("loading-bg").style.display = "none";
        });
    },
    setProcurmentItems() {
      if (this.$refs.ekmalat) {
        this.$refs.ekmalat.resetArrays()
        for (const [index, item] of Object.values(this.prForm.item).entries()) {
          var data = {
            increment: this.prForm.item[index].increment,
            increment_equiv: this.prForm.item[index].increment_equiv,
            unit_price: this.prForm.item[index].unit_price,
            total_price: this.prForm.item[index].total_price,
          }
          this.$refs.ekmalat.addRow({
            'key': index,
            'data': data
          });
          this.$refs.ekmalat.itemSelected('', this.prForm.item[index].item_id.id, index, (this.prForm.item[index].item_id.uom_id) ? this.prForm.item[index].item_id.uom_id.acronym : '');
          this.$refs.ekmalat.operationChange(this.prForm.item[index].operation_id, index);
        }
      }
    },
    toggleDataSidebar(val = false) {
      this.$validator.reset()
      this.addNewDataSidebar = val
    },
    submitData() {
      this.$validator.validateAll('procurmentForm').then(result => {
        if (result) {
          this.prForm.patch('/api/procurments/' + this.$route.params.id)
            .then(() => {
              this.$vs.notify({
                title: 'عملیه آپدیت موفق بود!',
                text: 'عملیه موفقانه انجام شد',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
              this.$router.push(`/procurment?tab=1`).catch(() => {})
            }).catch(() => {
              this.$vs.notify({
                title: 'آپدیت عملیه  ناموفق بود!',
                text: 'عملیه  ناکام شد لطفا دوباره تلاش نماید',
                color: 'danger',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
            })
        } else {}
      })
    }
  }
}
</script>

<style>
.customstyle {
  border-right: solid;
  border-right-width: initial;
  border-right-style: solid;
  border-right-color: initial;
}

.vs-radio {
  border-top-left-radius: 1 !important;
  border-bottom-left-radius: 1 !important;
}
</style>
