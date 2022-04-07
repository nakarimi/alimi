<template>
<vx-card>
  <form data-vv-scope="s3Form">
    <div class="vx-row sm-row-custom">
      <div class="sm:w-1 md:w-1/2 lg:w-1/4 xl:w-1/4 pr-3 pb-2 pt-3">
        <div class="vx-col w-full">
          <label for class="vs-input--label">انتخاب قراردادها</label>
          <v-select v-model="sForm.project_id" :get-option-label="
              (option) => option.serial_no + ' - ' + option.pro_data.title  + ' - ' + option.pro_data.company_id.sign
            " name="contract" :options="contracts" :searchable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'" @input="onChange" v-validate="'required'">
            <span slot="no-options">{{ $t("WhoopsNothinghere") }}</span>
          </v-select>
          <span class="absolute text-danger alerttext">{{ errors.first('s3Form.contract') }}</span>
        </div>
      </div>
      <div class="sm:w-1 md:w-1/2 lg:w-2/4 xl:w-2/4 pr-3 pb-2 pt-3">
        <div class="vx-col w-full">
          <label for=""><small>سریال نمبر</small></label>
          <vx-input-group class="number-rtl">
            <template slot="append">
              <div v-if="sForm.project_id && sForm.project_id.pro_data" class="append-text bg-primary serial_no_reference">
                <span>{{ sForm.project_id.pro_data.reference_no }}</span>
              </div>
              <div class="append-text bg-primary">
                <span>S3</span>
              </div>
            </template>
            <vs-input :value="sForm.serial_no" disabled autocomplete="off" placeholder="انتخاب قرارداد الزمی است." type="number" />
          </vx-input-group>
        </div>
      </div>
      <div class="sm:w-1 md:w-1/2 lg:w-1/4 xl:w-1/4 pr-3 pb-2 pt-3">
        <label for="date" class="mt-3"><small>تاریخ</small></label>
        <date-picker inputFormat="jYYYY/jMM/jDD HH:mm" display-format="jYYYY/jMM/jDD hh:mm" color="#e85454" v-model="sForm.datatime" type="datetime" v-validate="'required'" name="sele_date" :auto-submit="true" size="large"></date-picker>
        <span class="absolute text-danger alerttext">{{ errors.first('s3Form.sel_date') }}</span>
      </div>
    </div>
    <div class="vx-row sm-row-custom">
      <div class="sm:w-1 md:w-1/2 lg:w-1/4 xl:w-1/4 pr-3 pb-2 pt-3">
        <div class="vx-col w-full">
          <vs-input name="client_name" v-validate="'required|min:2'" class="w-full" v-bind:value="contract.clientName" label="نام نهاد" />
        </div>
      </div>
      <div class="sm:w-1 md:w-1/2 lg:w-1/4 xl:w-1/4 pr-3 pb-2 pt-3">
        <div class="vx-col w-full">
          <vs-input name="phone_number" v-validate="'required|min:2'" class="w-full number-rtl" v-bind:value="contract.clientPhone" label="شماره تماس" />
        </div>
      </div>
      <div class="sm:w-1 md:w-1/2 lg:w-1/4 xl:w-1/4 pr-3 pb-2 pt-3">
        <div class="vx-col w-full">
          <vs-input name="address" v-validate="'required|min:3'" class="w-full" v-bind:value="contract.clientAddress" label="آدرس" />
        </div>
      </div>
      <div class="sm:w-1 md:w-1/2 lg:w-1/4 xl:w-1/4 pr-3 pb-2 pt-3">
        <div class="vx-col w-full">
          <label for class="ml-4 mr-4 mb-2">واحد پولی</label>
          <div class="radio-group w-full">
            <div class="w-1/2">
              <input type="radio" v-model="sForm.currency_id" value="1" id="struct" name="curency" />
              <label for="struct" class="w-full text-center">افغانی</label>
            </div>
            <div class="w-1/2">
              <input type="radio" v-model="sForm.currency_id" value="2" id="specific" name="curency" />
              <label for="specific" class="w-full text-center">دالر</label>
            </div>
          </div>
        </div>
        <span class="absolute text-danger alerttext">{{ errors.first('s3Form.curency') }}</span>
      </div>
    </div>
    <div class="vx-row sm-row-custom">
      <div class="sm:w-1 md:w-1/2 lg:w-1/3 xl:w-1/3 pr-3 pb-2 pt-3">
        <div class="vx-col w-full">
          <vs-input name="driver" v-validate="'required|min:2'" class="w-full" v-model="sForm.driver_name" label="نام دریور" />
        </div>
      </div>
      <div class="sm:w-1 md:w-1/2 lg:w-1/3 xl:w-1/3 pr-3 pb-2 pt-3">
        <div class="vx-col w-full">
          <vs-input name="plate_no" v-validate="'required|min:2'" class="w-full number-rtl" v-model="sForm.plate_no" label="نمبر پلیت" />
        </div>
      </div>
      <div class="sm:w-1 md:w-1/2 lg:w-1/3 xl:w-1/3 pr-3 pb-2 pt-3">
        <div class="vx-col w-full">
          <vs-input name="driver_phone" v-validate="'required|min:2'" class="w-full" v-model="sForm.driver_phone" label="شماره تماس" />
        </div>
      </div>
    </div>

    <!-- 3td Row -->
    <div class="vx-row sm-row-custom">
      <div class="sm:w-1 md:w-1/2 lg:w-1/4 xl:w-1/4 pr-3 pb-2 pt-3">
        <!-- This conpoment need the form source id and form source type field -->
        <label for=""><small>منبع</small></label>
        <source-select ref="str" :parentForm="sForm" @updateItems="update_items" name="source" v-validate="'required'" v-model="sForm.source_id"></source-select>
        <span class="absolute text-danger alerttext">{{ errors.first('s3Form.source') }}</span>
      </div>
      <div class="sm:w-1 md:w-1/2 lg:w-3/4 xl:w-3/4 pr-3 pb-2 pt-3">
        <div class="vx-col w-full">
          <vs-input name="destination" v-validate="'required|min:2'" v-model="sForm.destination" class="w-full" label="مقصد" />
          <span class="absolute text-danger alerttext">{{ errors.first('s3Form.destination') }}</span>
        </div>
      </div>
    </div>

    <!-- EkmalatStock -->
    <ekmalat-stock :items="sForm.item" :currencyID="sForm.currency_id" :form="sForm" :listOfFields="[]" :disabledFields="[]" :grid="[]" ref="ekmalat"></ekmalat-stock>

    <vs-row vs-w="12" class="mb-base">
      <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
        <div class="w-full pt-2 lg:mx-3 md:mx-2 ">
          <label for=""><small>مصارف خدمات</small></label>
          <vx-input-group class="number-rtl">
            <template slot="prepend">
              <div class="prepend-text bg-primary">
                <span v-if="sForm.currency_id==1">AFN</span>
                <span v-if="sForm.currency_id==2">USD</span>
              </div>
            </template>
            <vs-input v-model="visualFields.service_cost" autocomplete="off" @input="formatToEnPrice($event, sForm, 'service_cost', visualFields)" v-validate="'required'" name="service_cost" />
          </vx-input-group>
          <span class="absolute text-danger alerttext">{{ errors.first('s3Form.service_cost') }}</span>
          <has-error :form="sForm" field="service_cost"></has-error>
        </div>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
        <div class="w-full pt-2 lg:mx-3 md:mx-2">
          <label for=""><small>مصرف مجموعی</small></label>
          <vx-input-group class="number-rtl">
            <template slot="prepend">
              <div class="prepend-text bg-primary">
                <span v-if="sForm.currency_id==1">AFN</span>
                <span v-if="sForm.currency_id==2">USD</span>
              </div>
            </template>
            <vs-input disabled :value="saleTotalCost" autocomplete="off" v-validate="'required'" name="sale_total_price" />
          </vx-input-group>
          <span class="absolute text-danger alerttext">{{ errors.first('s3Form.sale_total_price') }}</span>
        </div>
      </vs-col>

      <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
        <vs-col class="mr-2" vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 lg:mx-3 md:mx-2 sm:mr-2">
            <label for=""><small>مالیات</small></label>
            <vx-input-group class="number-rtl">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>٪</span>
                </div>
              </template>
              <vs-input v-model="sForm.tax" autocomplete="off" type="number" v-validate="'required'" name="tax" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{ errors.first('s3Form.tax') }}</span>
            <has-error :form="sForm" field="tax"></has-error>
          </div>
        </vs-col>
        <vs-col class="sm:pl-2" vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 lg:mr-3">
            <label for=""><small>تامینات</small></label>
            <vx-input-group class="number-rtl">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>٪</span>
                </div>
              </template>
              <vs-input v-model="sForm.deposit" autocomplete="off" type="number" v-validate="'required'" name="deposit" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{ errors.first('s3Form.deposit') }}</span>
            <has-error :form="sForm" field="deposit"></has-error>
          </div>
        </vs-col>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
        <div class="w-full pt-2 lg:mx-3 md:mx-2">
          <label for=""><small>قیمت نهایی</small></label>
          <vx-input-group class="number-rtl">
            <template slot="prepend">
              <div class="prepend-text bg-primary">
                <span v-if="sForm.currency_id==1">AFN</span>
                <span v-if="sForm.currency_id==2">USD</span>
              </div>
            </template>
            <vs-input disabled :value="saleTotalCostFinal" autocomplete="off" name="sale_total_value" v-validate="'required'" />
          </vx-input-group>
          <span class="absolute text-danger alerttext">{{ errors.first('s3Form.sale_total_value') }}</span>
        </div>
      </vs-col>
    </vs-row>
    <div class="vx-row sm-row-custom">
      <div class="w-full pt-2 lg:mx-3 md:mx-2">
        <vs-col vs-align="right" vs-lg="3" class="lg:pl-4 md:pl-3" vs-sm="6" vs-xs="12">
          <bank-account-select ref="bas" :form="sForm" name="bank_account" v-validate="'required'" v-model="sForm.bank_account"></bank-account-select><br>
          <span class="absolute text-danger alerttext">{{ errors.first('s3Form.bank_account') }}</span>
        </vs-col>
      </div>
    </div>
    <div class="vx-row sm-row-custom">
      <div class="w-full pt-2 lg:mx-3 md:mx-2">
        <label for=""><small>تفصیلات</small></label>
        <vs-textarea :rows="sForm.description && sForm.description.split(`\n`).length > 2 ? sForm.description.split(`\n`).length + 1 : 3" v-model="sForm.description" class="mr-3 mb-1 w-full" />
      </div>
    </div>

    <div class="mt-10">
      <div class="vx-col w-full">
        <vs-button :disabled="sForm.busy" @click.prevent="submitForm" class="mb-2 ml-2 mr-2">ثبت</vs-button>
        <vs-button color="warning" type="border" class="mb-2 ml-2" @click="resetForm">پاک کردن فرم</vs-button>
      </div>
    </div>
  </form>
</vx-card>
</template>

<script>
import vSelect from 'vue-select'
import EkmalatStock from "../../../shared/EkmalatStock"
import SourceSelect from "../../../shared/SourceSelect";
import BankAccountSelect from "../../../shared/BankAccountSelect"
import {
  Validator
} from 'vee-validate'

export default {
  props: {
    data: {
      type: Object,
      default: () => {}
    }
  },
  components: {
    'v-select': vSelect,
    EkmalatStock,
    SourceSelect,
    BankAccountSelect,
    Validator

  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      checked: [{
          state: false,
          label: 'دریافت اسناد'
        },
        {
          state: false,
          label: 'اکمالات'
        },
        {
          state: false,
          label: 'تاییدی'
        },
      ],

      userid: localStorage.getItem('id'),
      visualFields: {
        service_cost: 0,
      },
      sForm: new Form({
        serial_no: '',
        project_id: '',
        driver_name: '',
        plate_no: '',
        driver_phone: '0',
        service_cost: '0',
        tax: '0',
        deposit: '0',
        total: '0',
        steps: '0',
        description: '',

        // shared fields with other sales
        bank_account: null,
        type: "s3",
        source_id: "", // The Id of the storage.
        source_type: "", // Type storage
        user_id: localStorage.getItem('id'), //Get the current user id
        currency_id: 1,
        datatime: '',
        relative_person: "",
        // Item for the ekmalat section
        item: [{
          item_id: "",
          unit_id: "",
          operation_id: null,
          increment_equiv: "",
          increment: "",
          unit_price: "0",
          total_price: "0",
          density: 1,
        }],
        items: [],
      }),
      contracts: [],
      contract: [],
      // End of sidebar items
      dict: {
        custom: {
          sele_date: {
            required: ' تاریخ خریداری الزامی میباشد.'
          },
          contract: {
            required: ' انتخاب اعلان الزامی میباشد.'
          },
          // client_name: { required: '  اسم نهاد الزامی میباشد.', min: 'اسم نهاد باید بیشتر از 2 حرف باشد.' },
          // address: { required: '  آدرس الزامی میباشد.', min: 'آدرس باید بیشتر از 3 حرف باشد.' },
          // driver: { required: '  اسم راننده الزامی میباشد.', min: 'اسم راننده باید بیشتر از 2 حرف باشد.' },
          // plate_no: { required: '  نمبر پلیت راننده الزامی میباشد.', min: 'نمبر پلیت راننده باید بیشتر از 2 حرف باشد.' },
          // driver_phone: { required: 'شماره تماس راننده الزامی میباشد .', min: 'شماره تماس راننده باید بیشتر از 2 حرف باشد.' },
          source: {
            required: ' انتخاب منبع الزامی میباشد.'
          },
          destination: {
            required: 'مقصد الزامی میباشد .',
            min: 'مقصد باید بیشتر از 2 حرف باشد.'
          },
          service_cost: {
            required: 'مصارف خدمات الزامی میباشد .'
          },
          sale_total_price: {
            required: 'مصارف کلی فروش الزامی میباشد .'
          },
          tax: {
            required: 'مالیه الزامی میباشد '
          },
          deposit: {
            required: 'تامینات الزامی میباشد '
          },
          sale_total_value: {
            required: 'قیمت نهایی فروش الزامی میباشد '
          },
          bank_account: {
            required: 'حساب بانکی الزامی میباشد.',
            min: 'حساب بانکی باید بیشتر از 2 حرف باشد.',
          },
        }
      }
    };
  },
  created() {
    Validator.localize('en', this.dict);
    this.getProject();
    setTimeout(() => {
      document.getElementById("loading-bg").style.display = "none";
    }, 5000);
  },
  computed: {
    saleTotalCostFinal: function () {
      var i_total = 0;
      this.sForm.item.forEach(item => {
        i_total += item.total_price;
      });
      this.sForm.total = parseFloat(i_total).toFixed(0);
      return this.formatToEnPriceSimple(this.sForm.total);
    },
    saleTotalCost: function () {
      let x = parseFloat(this.sForm.service_cost);
      return this.formatToEnPriceSimple(x);
    }
  },
  methods: {
    getProject() {
      this.$Progress.start();
      // this.$vs.loading();
      this.axios
        .get("/api/project")
        .then((data) => {
          this.contracts = data.data;
          this.getSale();
          this.$Progress.set(100);
        })
        .catch(() => {
          document.getElementById("loading-bg").style.display = "none";
        });
    },
    update_items(matched_items) {
      this.$refs.ekmalat.getAllItems(matched_items);
    },
    onChangeContract(contract) {
      if (contract != null) {
        this.contract.clientName = contract.pro_data.client.name;
        this.contract.clientEmail = contract.pro_data.client.email;
        this.contract.clientPhone = contract.pro_data.client.phone;
        this.contract.clientAddress = contract.pro_data.client.address;
        this.sForm.tax = contract.pro_data.tax
        this.sForm.deposit = contract.pro_data.deposit
        this.sForm.item = contract.pro_items;
        for (const [index, item] of Object.values(this.sForm.item).entries()) {
          this.sForm.item[index].increment = this.sForm.item[index].ammount;
          this.sForm.item[index].increment_equiv = this.sForm.item[index].equivalent;
          var data = {
            increment: this.sForm.item[index].ammount,
            increment_equiv: this.sForm.item[index].equivalent,
            unit_price: this.sForm.item[index].unit_price,
            total_price: this.sForm.item[index].total_price,
          }
          this.$refs.ekmalat.addRow({
            'key': index,
            'data': data
          });
          this.$refs.ekmalat.operationChange(this.sForm.item[index].operation_id, index);
          this.$refs.ekmalat.itemSelected('', this.sForm.item[index].item_id.id, index, this.sForm.item[index].item_id.uom_id.acronym);
        }
      } else {
        this.contract.clientName = null;
        this.contract.clientEmail = null;
        this.contract.clientPhone = null;
        this.contract.clientAddress = null;
      }
    },
    resetForm() {
      const f = this.form;
      for (var key in f) {
        this.form[key] = null;
      }
    },
    submitForm() {
      this.$validator.validateAll('s3Form').then(result => {
        if (result) {
          this.$Progress.start()
          this.sForm.patch(`/api/sale3/${this.$route.params.id}`)
            .then(({
              data
            }) => {
              this.$Progress.set(100)
              this.$vs.notify({
                title: 'موفقیت!',
                text: 'مورد فروش موفقانه آپدیت شد.',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
              this.$router.push(`/sales?tab=1`).catch(() => {})
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
        } else {

          // form have errors
        }

      })

    },
    appCheckBoxes(x) {
      if (this.sForm.steps && this.sForm.steps >= x) {
        this.sForm.steps = (x - 1);
      } else {
        this.sForm.steps = x;
      }
      for (let i = 0; i < this.checked.length; i++) {
        this.checked[i].state = i <= x ? true : false;
      }
    },
    // Edit methods
    getSale() {
      this.axios.get(`/api/sale3/${this.$route.params.id}/edit`)
        .then((response) => {
          this.setSaleDataEdit(response.data);
          this.$Progress.set(100);
          document.getElementById("loading-bg").style.display = "none";
        })
    },
    setSaleDataEdit(d) {
      if (this.$refs.ekmalat) {
        this.$refs.ekmalat.resetArrays()
        let ds = d.sale_s3;
        this.sForm.driver_name = ds.driver_name;
        this.sForm.plate_no = ds.plate_no;
        this.sForm.driver_phone = ds.driver_phone;
        this.sForm.relative_person = ds.relative_person;

        this.sForm.serial_no = ds.serial_no;
        this.sForm.project_id = ds.project;
        this.sForm.destination = ds.destination;
        this.sForm.transport_cost = ds.transport_cost;
        this.sForm.service_cost = ds.service_cost;
        this.sForm.tax = ds.tax;
        this.sForm.deposit = ds.deposit;
        this.sForm.total = ds.total;
        this.sForm.steps = ds.steps;
        this.sForm.description = ds.description;
        this.visualFields.transport_cost = ds.transport_cost;
        this.visualFields.service_cost = ds.service_cost;
        this.visualFields.tax = ds.tax;
        this.visualFields.deposit = ds.deposit;
        this.visualFields.total = ds.total;

        if (this.$refs.bas) {
          this.$refs.bas.findAndSet(d.bank_account);
        }
        if (this.$refs.str) {
          this.$refs.str.findAndSet(d.source_id, d.source_type);
        }
        this.sForm.currency_id = d.currency_id;
        this.sForm.datatime = d.datatime;
        // this.sForm.item = d.items;

        this.contracts.forEach(e => {
          if (e.id === ds.project.id) {
            this.onChangeContract(e);
          }
        })
        document.getElementById("loading-bg").style.display = "none";
      }
    },

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
