<template>
<vx-card>
  <form data-vv-scope="s1Form">
    <div class="vx-row sm-row-custom">
      <div class="sm:w-12 md:w-1/2 lg:w-1/4 xl:w-1/4 pr-3 pb-2 pt-3">
        <div class="vx-col w-full">
          <label for class="vs-input--label">انتخاب قراردادها</label>
          <v-select v-validate="'required'" v-model="sForm.project_id" :get-option-label="
              (option) => option.serial_no + ' - ' + option.pro_data.title  + ' - ' + option.pro_data.company_id.sign
            " name="contract" :options="contracts" :searchable="false" :dir="$vs.rtl ? 'rtl' : 'ltr'" @input="onChangeContract">
            <span slot="no-options">{{ $t("WhoopsNothinghere") }}</span>
          </v-select>
          <span class="absolute text-danger alerttext">{{ errors.first('s1Form.contract') }}</span>
        </div>
      </div>
      <div class="sm:w-12 md:w-1/2 lg:w-2/4 xl:w-2/4 pr-3 pb-2 pt-3">
        <div class="vx-col w-full">
          <label for=""><small>سریال نمبر</small></label>
          <vx-input-group class="number-rtl">
            <template slot="append">
              <div v-if="sForm.project_id && sForm.project_id.pro_data" class="append-text bg-primary serial_no_reference">
                <span>{{ sForm.project_id.pro_data.reference_no }}</span>
              </div>
              <div class="append-text bg-primary">
                <span>S1</span>
              </div>
            </template>
            <vs-input :value="sForm.serial_no" disabled autocomplete="off" placeholder="انتخاب قرارداد الزمی است." type="number" />
          </vx-input-group>
        </div>
      </div>
      <div class="sm:w-1 md:w-1/2 lg:w-1/4 xl:w-1/4 pr-3 pb-2 pt-3">
        <label for="date" class="mt-3"><small>تاریخ</small></label>
        <date-picker v-model="sForm.datatime" inputFormat="jYYYY/jMM/jDD HH:mm" display-format="jYYYY/jMM/jDD hh:mm" color="#e85454" type="datetime" v-validate="'required'" :title="errors.first(`contract_date`)" v-bind:class="errors.first(`contract_date`) ? 'has-error' : ''" name="sel_date" :auto-submit="true" size="large"></date-picker>
        <span class="absolute text-danger alerttext">{{ errors.first('s1Form.sel_date') }}</span>
      </div>
    </div>
    <div class="vx-row sm-row-custom">
      <div class="sm:w-1 md:w-1/2 lg:w-1/4 xl:w-1/4 pr-3 pb-2 pt-3">
        <div class="vx-col w-full">
          <vs-input name="client_name" v-validate="'required|min:2'" class="w-full" v-bind:value="field_data.clientName" label="نام نهاد" />
        </div>
      </div>
      <div class="sm:w-1 md:w-1/2 lg:w-1/4 xl:w-1/4 pr-3 pb-2 pt-3">
        <div class="vx-col w-full">
          <vs-input name="phone_number" v-validate="'required|min:2'" class="w-full number-rtl" v-bind:value="field_data.clientPhone" label="شماره تماس" />
        </div>
      </div>
      <div class="sm:w-1 md:w-1/2 lg:w-1/4 xl:w-1/4 pr-3 pb-2 pt-3">
        <div class="vx-col w-full">
          <vs-input name="address" v-validate="'required|min:3'" class="w-full" v-bind:value="field_data.clientAddress" label="آدرس" />
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
        <span class="absolute text-danger alerttext">{{ errors.first('s1Form.curency') }}</span>
      </div>
    </div>
    <!-- 3td Row -->
    <div class="vx-row sm-row-custom">
      <div class="sm:w-1 md:w-1/2 lg:w-1/4 xl:w-1/4 pr-3 pb-2 pt-3">
        <!-- This conpoment need the form source id and form source type field -->
        <label for=""><small>منبع</small></label>
        <source-select ref="str" :parentForm="sForm" @updateItems="update_items" name="source" v-validate="'required'" v-model="sForm.source_id"></source-select>
        <span class="absolute text-danger alerttext">{{ errors.first('s1Form.source') }}</span>
      </div>
      <div class="sm:w-1 md:w-1/2 lg:w-3/4 xl:w-3/4 pr-3 pb-2 pt-3">
        <div class="vx-col w-full">
          <vs-input v-model="sForm.destination" v-validate="'required'" :title="errors.first(`destination`)" v-bind:class="errors.first(`destination`) ? 'has-error' : ''" name="destination" class="w-full" label="مقصد" />
          <span class="absolute text-danger alerttext">{{ errors.first('s1Form.destination') }}</span>
        </div>
      </div>
    </div>
    <!-- EkmalatStock -->
    <ekmalat-stock :items="sForm.item" :form="sForm" :currencyID="sForm.currency_id" :listOfFields="[]" :disabledFields="[]" :grid="[]" ref="ekmalat"></ekmalat-stock>
    <vs-row vs-w="12" class="mb-base">
      <vs-col vs-type="flex" vs-lg="3" vs-sm="6" vs-xs="12">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 mr-3">
            <label for=""><small>مصارف انتقالات</small></label>
            <vx-input-group class="number-rtl">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span v-if="sForm.currency_id==1">AFN</span>
                  <span v-if="sForm.currency_id==2">USD</span>
                </div>
              </template>
              <vs-input v-model="visualFields.transport_cost" @input="formatToEnPrice($event, sForm, 'transport_cost', visualFields)" autocomplete="off" v-validate="'required'" name="others" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{
             errors.first('s1Form.others') }}</span>
            <!--<span class="absolute text-danger alerttext">{{
              errors.first("step-2.others") others,transit,total_price,tax,deposit,total_price
            }}</span>
          <has-error :form="sForm" field="others"></has-error> -->
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 lg:ml-3 md:ml-0 mr-3">
            <label for=""><small>مصارف خدمات</small></label>
            <vx-input-group class="number-rtl">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span v-if="sForm.currency_id==1">AFN</span>
                  <span v-if="sForm.currency_id==2">USD</span>
                </div>
              </template>
              <vs-input v-model="visualFields.service_cost" @input="formatToEnPrice($event, sForm, 'service_cost', visualFields)" autocomplete="off" v-validate="'required'" name="transit" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{
              errors.first("s1Form.transit")
            }}</span>
          </div>
        </vs-col>
      </vs-col>
      <vs-col vs-type="flex" vs-lg="3" vs-sm="6" vs-xs="12">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center">
          <div class="w-full pt-2 lg:ml-3 md:ml-0 mr-3">
            <label for=""><small>مصرف مجموعی</small></label>
            <vx-input-group class="number-rtl">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span v-if="sForm.currency_id==1">AFN</span>
                  <span v-if="sForm.currency_id==2">USD</span>
                </div>
              </template>
              <vs-input disabled :value="saleTotalCost" autocomplete="off" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{
              errors.first("s1Form.total_price")
            }}</span>
          </div>
        </vs-col>
      </vs-col>

      <vs-col vs-type="flex" vs-lg="3" vs-sm="6" vs-xs="12">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 lg:ml-3 md:ml-0 mr-3">
            <label for=""><small>مالیات</small></label>
            <vx-input-group class="number-rtl">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>٪</span>
                </div>
              </template>
              <vs-input v-model="sForm.tax" autocomplete="off" type="number" v-validate="'required'" name="tax" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{
              errors.first("s1Form.tax")
            }}</span>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 mr-3">
            <!-- TITLE -->
            <label for=""><small>تامینات</small></label>
            <vx-input-group class="number-rtl">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>٪</span>
                </div>
              </template>
              <vs-input v-model="sForm.deposit" autocomplete="off" type="number" v-validate="'required'" name="deposit" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{
              errors.first("s1Form.deposit")
            }}</span>
          </div>
        </vs-col>
      </vs-col>

      <vs-col vs-type="flex" vs-lg="3" vs-sm="6" vs-xs="12">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center">
          <div class="w-full pt-2 lg:ml-3 md:ml-0 mr-3">
            <label for=""><small>قیمت نهایی</small></label>
            <vx-input-group class="number-rtl">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span v-if="sForm.currency_id==1">AFN</span>
                  <span v-if="sForm.currency_id==2">USD</span>
                </div>
              </template>
              <vs-input disabled :value="saleTotalCostFinal" autocomplete="off" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{
              errors.first("s1Form.total_price")
            }}</span>
          </div>
        </vs-col>
      </vs-col>
    </vs-row>
    <div class="vx-row">
      <div class="w-full pt-2 lg:ml-3 md:ml-0 mr-3">
        <vs-col vs-align="right" vs-lg="3" class="lg:pl-4 md:pl-2" vs-sm="6" vs-xs="12">
          <bank-account-select ref="bas" :form="sForm" name="bank_account" v-validate="'required'" v-model="sForm.bank_account"></bank-account-select><br>
          <span class="absolute text-danger alerttext">{{ errors.first('s1Form.bank_account') }}</span>
        </vs-col>
      </div>
    </div>
    <div class="vx-row">
      <div class="w-full pt-2 lg:ml-3 md:ml-0 mr-3">
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
import vSelect from "vue-select";
import EkmalatStock from "../../../shared/EkmalatStock";
import SourceSelect from "../../../shared/SourceSelect";
import BankAccountSelect from "../../../shared/BankAccountSelect"
import {
  Validator
} from 'vee-validate'

export default {
  props: {
    data: {
      type: Object,
      default: () => {},
    },
  },
  components: {
    "v-select": vSelect,
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
          label: 'تنظیم اطلاعات'
        },
        {
          state: false,
          label: 'تحویل اجناس'
        },
        {
          state: false,
          label: 'ارسال بل'
        },
        {
          state: false,
          label: 'فورم م-16 (m-16)'
        },
        {
          state: false,
          label: 'وزارت مالیه'
        },
        {
          state: false,
          label: 'دافغانستان بانک'
        },
        {
          state: false,
          label: 'دریافت پول'
        },
        {
          state: false,
          label: 'تایید'
        },
      ],
      pro_items: [],
      userid: localStorage.getItem('id'),
      visualFields: {
        transport_cost: "0",
        service_cost: "0",
        tax: "0",
        deposit: "0",
        total: "0",
      },
      sForm: new Form({
        serial_no: "",
        project_id: "",
        destination: "",
        transport_cost: "0",
        service_cost: "0",
        tax: "0",
        deposit: "0",
        total: "0",
        steps: "0",
        description: "",

        // shared fields with other sales
        bank_account: null,
        type: "s1",
        source_id: "", // The Id of the Project.
        source_type: "", // Type Project
        user_id: localStorage.getItem('id'), //Get the current user id
        currency_id: 1,
        datatime: '',
        // Item for the ekmalat section
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
      items: [],
      contracts: [],
      field_data: [],
      dict: {
        custom: {
          curency: {
            required: ' واحد پولی الزامی میباشد.'
          },
          sel_date: {
            required: ' تاریخ فروش الزامی میباشد.'
          },
          contract: {
            required: ' انتخاب قرارداد الزامی میباشد.'
          },
          // client_name: { required: '  اسم نهاد الزامی میباشد.', min: 'اسم نهاد باید بیشتر از 2 حرف باشد.', },
          // address: { required: '  آدرس الزامی میباشد.', min: 'آدرس باید بیشتر از 3 حرف باشد.', },
          source: {
            required: ' انتخاب منبع الزامی میباشد.'
          },
          destination: {
            required: ' انتخاب مقصد الزامی میباشد.'
          },
          bank_account: {
            required: 'حساب بانکی الزامی میباشد.',
            min: 'حساب بانکی باید بیشتر از 2 حرف باشد.',
          },
          others: {
            required: 'مصارف انتقالات ضروری است.'
          },
          transit: {
            required: 'مصارف خدمات ضروری است.'
          },
          // total_price: { required: '' },
          tax: {
            required: 'مالیه ضروری است.'
          },
          deposit: {
            required: 'مصارف تامینات ضروری است.'
          },
          total_price: {
            required: ''
          }
        }
      },
      sdict: {
        custom: {
          company_id: {
            required: 'سریال نمبر الزامی میباشد.',
            number: 'سریال نمبر باید نمبر باشد.'
          },
          proposal_id: {
            required: 'انتخاب اعلان الزامی میباشد.',
          },
          contract_date: {
            required: 'تاریخ عقد قرارداد را انتخاب کنید.'
          },
          client_id: {
            required: 'نهاد را انتخاب کنید.'
          },
          title: {
            required: 'عنوان قرراداد الزامی است.'
          },
          reference_no: {
            required: 'شماره شناسایی قرارداد ضروری است.'
          },
          contract_end_date: {
            required: 'تاریخ ختم قرارداد الزامی است.'
          },
          bidding_date: {
            required: 'تاریخ آفرگشایی الزامی است.'
          },
          bidding_address: {
            required: 'آدرس آفرگشایی الزامی است.'
          },
          project_guarantee: {
            required: 'تضمین حسن اجرا الزامی است'
          },
          deposit: {
            required: 'فیصدی تامینات را وارد کنید.',
          },
          tax: {
            required: 'فیصدی مالیه را وارد کنید',
          },
          others: {
            required: 'قیمت متفرقه بالای قرارداد را وارد کنید.',
          },
          pr_worth: {
            required: 'ارزش قرارداد الزامی است.',
          },
          transit: {
            required: 'قیمت انتقالات را وارد کنید.',
          },
          total_price: {
            required: '',
          },
        },
      },
    };
  },
  created() {
    Validator.localize('en', this.dict)
    this.getProject()
    window.addEventListener('keydown', (e) => {
      if (e.key == 'Enter') {
        if (!e.path.find(x => x.className === 'vs-textarea' || x.className === 'vs__selected-options')) {
          this.submitForm();
        }
      }
    })
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
      let x = (parseFloat(this.sForm.transport_cost) + parseFloat(this.sForm.service_cost)).toFixed(0);
      return this.formatToEnPriceSimple(x);
    }
  },
  methods: {
    getProject() {
      this.$Progress.start();
      this.axios
        .get("/api/project")
        .then((data) => {
          this.contracts = data.data;
          this.getSale();
        })
        .catch(() => {});
    },
    // Old methods
    onChangeContract(contract, sale_items = false) {
      if (this.$refs.ekmalat) {
        this.$refs.ekmalat.resetArrays()
        if (contract != null) {
          // this.getNextSerialNo(contract.id);

          this.field_data.clientName = contract.pro_data.client.name;
          // this.field_data.repativePerson = contract.pro_data.client.phone;
          this.field_data.clientPhone = contract.pro_data.client.phone;
          this.field_data.clientAddress = contract.pro_data.client.address;
          this.sForm.tax = contract.pro_data.tax
          this.sForm.deposit = contract.pro_data.deposit
          if (sale_items == true) {
            this.sForm.item = this.pro_items;
          } else {
            this.sForm.item = contract.pro_items;
          }

          for (const [index, item] of Object.values(this.sForm.item).entries()) {
            if (sale_items) {
              this.sForm.item[index].increment_equiv = this.sForm.item[index].decrement_equiv;
              this.sForm.item[index].increment = this.sForm.item[index].decrement;
            } else {
              this.sForm.item[index].increment_equiv = this.sForm.item[index].equivalent;
              this.sForm.item[index].increment = this.sForm.item[index].ammount;
            }
            var data = {
              increment: this.sForm.item[index].increment,
              increment_equiv: this.sForm.item[index].increment_equiv,
              unit_price: this.sForm.item[index].unit_price,
              total_price: this.sForm.item[index].total_price,
            }
            this.$refs.ekmalat.addRow({
              'key': index,
              'data': data
            });
            this.$refs.ekmalat.itemSelected('', this.sForm.item[index].item_id.id, index, this.sForm.item[index].item_id.uom_id.acronym);
            this.$refs.ekmalat.operationChange(this.sForm.item[index].operation_id, index);
          }
        }
      } else {
        this.field_data.clientName = null;
        // this.field_data.repativePerson = null;
        this.field_data.clientPhone = null;
        this.field_data.clientAddress = null;
      }
    },
    resetForm() {
      const f = this.form;
      for (var key in f) {
        this.form[key] = null;
      }
    },
    update_items(matched_items) {
      this.$refs.ekmalat.getAllItems(matched_items);
    },
    submitForm() {
      this.$validator.validateAll('s1Form').then(result => {
        if (result) {
          this.$Progress.start()
          this.sForm.patch(`/api/sale1/${this.$route.params.id}`)
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
        } else {}
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
      this.axios.get(`/api/sale1/${this.$route.params.id}/edit`)
        .then((response) => {
          this.setSaleDataEdit(response.data);
          this.$Progress.set(100);
          document.getElementById("loading-bg").style.display = "none";
        })
    },
    setSaleDataEdit(d) {
      let ds = d.sale_s1;
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
      this.pro_items = d.sale_s1.pro_items;
      this.contracts.forEach(e => {
        if (e.id === ds.project.id) {
          this.onChangeContract(e, true);
        }
      })
      document.getElementById("loading-bg").style.display = "none";
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
