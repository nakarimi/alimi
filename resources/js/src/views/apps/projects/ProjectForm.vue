<template>
<form-wizard color="rgba(var(--vs-primary), 1)" :title="null" :subtitle="null" back-button-text="قبلی" next-button-text="بعدی" :start-index.sync="currentIndex" ref="wizard" finishButtonText="" @on-complete="submitForm">
  <tab-content title="معلومات عمومی قرارداد" class="mb-5" :before-change="validateStep1">
    <form data-vv-scope="step-1">
      <vs-row vs-w="12">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 lg:mx-3 md:mx-3 sm:mx-2">
            <pro-serial-number :form="pForm" v-model="pForm.company_id" v-validate="'required'" name="company_id" @companySelected="companySelected" :companies="companies"></pro-serial-number>
            <!-- <vs-input size="medium" v-model="pForm.serial_no" label="سریال نمبر" class="w-full" disabled /> -->
            <span style="margin-right: -280px;margin-top: 62px;" class="absolute text-danger alerttext">{{ errors.first('step-1.company_id') }}</span>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 lg:mx-3 md:mx-3 sm:mx-2">
            <label for=""><small>انتخاب اعلان</small></label>
            <v-select name="proposal_id" :clearable="false" :get-option-label="option => option.serial_no + ' - ' + option.pro_data.title" @input="setProjectData" v-model="pForm.proposal_id" :options="proposals" :dir="$vs.rtl ? 'rtl' : 'ltr'">
              <!--<span slot="no-options">{{$t('WhoopsNothinghere')}}</span>-->
            </v-select>
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.proposal_id') }}</span>
            <!--<has-error :form="pForm" field="proposal_id"></has-error>-->
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 lg:mx-3 md:mx-3 sm:mx-2">
            <label for="date" class="mt-3"><small>تاریخ عقد قرارداد</small></label>
            <date-picker inputFormat="jYYYY/jMM/jDD" display-format="jYYYY/jMM/jDD" color="#e85454" v-validate="'required'" name="contract_date" v-model="pForm.contract_date" input-format="YYYY/MM/DD" format="jYYYY/jMM/jDD" :auto-submit="true" size="large"></date-picker>
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.contract_date') }}</span>
            <has-error :form="pForm" field="contract_date"></has-error>
          </div>
        </vs-col>
      </vs-row>
      <vs-row vs-w="12" class="pt-2">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 lg:mx-3 md:mx-3 sm:mx-2">
            <label for=""><small>مرجع مربوطه</small></label>
            <v-select label="name" v-model="pForm.client_id" v-validate="'required'" name="client_id" :options="clients" :dir="$vs.rtl ? 'rtl' : 'ltr'" />
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.client_id') }}</span>
            <has-error :form="pForm" field="client_id"></has-error>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="8" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 lg:mx-3 md:mx-3 sm:mx-2">
            <vs-input size="medium" v-model="pForm.title" v-validate="'required'" label="عنوان قرارداد" name="title" class="w-full" />
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.title') }}</span>
            <has-error :form="pForm" field="title"></has-error>
          </div>
        </vs-col>
      </vs-row>
      <vs-row vs-w="12" class="pt-2">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 lg:mx-3 md:mx-3 sm:mx-2">
            <label for class="ml-4 mr-4 mb-2">نوعیت قرارداد</label>
            <div class="radio-group w-full">
              <div class="w-1/2">
                <input type="radio" v-model="pForm.status" value="1" id="struct" name="status" />
                <label for="struct" class="w-full text-center">چارچوبی</label>
              </div>
              <div class="w-1/2">
                <input type="radio" v-model="pForm.status" value="2" id="specific" name="status" />
                <label for="specific" class="w-full text-center">معین</label>
              </div>
            </div>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 lg:mx-3 md:mx-3 sm:mx-2">
            <vs-input v-model="pForm.reference_no" label="شماره شناسایی قرارداد" name="reference_no" class="w-full" />
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.reference_no') }}</span>
            <has-error :form="pForm" field="reference_no"></has-error>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 lg:mx-3 md:mx-3 sm:mx-2">
            <label for="date" class="mt-3"><small>تاریخ ختم قرارداد</small></label>
            <date-picker inputFormat="jYYYY/jMM/jDD" display-format="jYYYY/jMM/jDD" color="#e85454" v-validate="'required'" name="contract_end_date" v-model="pForm.contract_end_date" input-format="YYYY/MM/DD" format="jYYYY/jMM/jDD" :auto-submit="true" size="large"></date-picker>
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.contract_end_date') }}</span>
            <has-error :form="pForm" field="contract_end_date"></has-error>
          </div>
        </vs-col>
      </vs-row>
      <vs-row vs-w="12" class="pt-2">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 lg:mx-3 md:mx-3 sm:mx-2">
            <label for=""><small>تضمین حسن اجرا</small></label>
            <vx-input-group class="">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>AFN</span>
                </div>
              </template>
              <vs-input name="project_guarantee" v-model="visualFields.project_guarantee" @input="formatToEnPrice($event, pForm, 'project_guarantee', visualFields)" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.project_guarantee') }}</span>
            <has-error :form="pForm" field="project_guarantee"></has-error>
          </div>
        </vs-col>
      </vs-row>
    </form>
  </tab-content>
  <tab-content title="اکمالات / مصارف " class="mb-5" icon="feather icon-briefcase" :before-change="validateStep2">

    <form data-vv-scope="step-2">
      <ekmalat :items="pForm.item" :form="pForm" :listOfFields="dict" ref="ekmalat"></ekmalat>
      <vs-row vs-w="12" class="mt-5">
        <vs-col class="lg-dis-flex sm-dis-contents mb-base" vs-lg="9" vs-sm="12" vs-xs="12">
          <vs-col class="pr-1" vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="6">
            <div class="w-full pt-2 lg:mx-3 md:mx-3 sm:mx-2 sm:mr-3">
              <label for=""><small>مصارف توزیعات / انتقالات</small></label>
              <vx-input-group class="">
                <template slot="prepend">
                  <div class="prepend-text bg-primary">
                    <span>AFN</span>
                  </div>
                </template>
                <vs-input autocomplete="off" v-model="visualFields.transit" @input="formatToEnPrice($event, pForm, 'transit', visualFields)" v-validate="'required'" name="transit" />
              </vx-input-group>
              <span class="absolute text-danger alerttext">{{ errors.first('step-2.transit') }}</span>
              <has-error :form="pForm" field="transit"></has-error>
            </div>
          </vs-col>
          <vs-col class="pl-1" vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="6">
            <div class="w-full pt-2 lg:mx-3 md:mx-3 sm:mx-2">
              <label for=""><small>مصارف متفرقه</small></label>
              <vx-input-group class="">
                <template slot="prepend">
                  <div class="prepend-text bg-primary">
                    <span>AFN</span>
                  </div>
                </template>
                <vs-input autocomplete="off" v-model="visualFields.others" @input="formatToEnPrice($event, pForm, 'others', visualFields)" v-validate="'required'" name="others" />
              </vx-input-group>
              <span class="absolute text-danger alerttext">{{ errors.first('step-2.others') }}</span>
              <has-error :form="pForm" field="others"></has-error>
            </div>
          </vs-col>

          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="12" vs-xs="12">
            <div class="w-full pt-2 lg:mx-3 md:ml-3 sm:mx-2">
              <label for=""><small>نرخ دهی</small></label>
              <vx-input-group class="">
                <template slot="prepend">
                  <div class="prepend-text bg-primary">
                    <span>AFN</span>
                  </div>
                </template>
                <vs-input autocomplete="off" v-model="visualFields.total_price" @input="formatToEnPrice($event, pForm, 'total_price', visualFields)" :v-model="visualFields.total_price = total_cost" />
              </vx-input-group>
              <span class="absolute text-danger alerttext">{{ errors.first('step-2.total_price') }}</span>
            </div>
          </vs-col>
        </vs-col>
        <vs-col vs-type="flex" vs-lg="3" vs-sm="6" vs-xs="12" class="mb-base">
          <vs-col class="pr-1" vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12">
            <div class="w-full pt-2 lg:mx-3 sm:mx-2 sm:pr-2">
              <!-- TITLE -->
              <label for=""><small>تامینات</small></label>
              <vx-input-group class="">
                <template slot="prepend">
                  <div class="prepend-text bg-primary">
                    <span>٪</span>
                  </div>
                </template>
                <vs-input autocomplete="off" type="number" v-model="pForm.deposit" v-validate="'required'" name="deposit" />
              </vx-input-group>
              <span class="absolute text-danger alerttext">{{ errors.first('step-2.deposit') }}</span>
              <has-error :form="pForm" field="deposit"></has-error>
            </div>
          </vs-col>
          <vs-col class="pl-1" vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12">
            <div class="w-full pt-2 lg:mx-3 md:mx-3 sm:mx-2">
              <label for=""><small>مالیات</small></label>
              <vx-input-group class="">
                <template slot="prepend">
                  <div class="prepend-text bg-primary">
                    <span>٪</span>
                  </div>
                </template>
                <vs-input autocomplete="off" type="number" v-model="pForm.tax" v-validate="'required'" name="tax" />
              </vx-input-group>
              <span class="absolute text-danger alerttext">{{ errors.first('step-2.tax') }}</span>
              <has-error :form="pForm" field="tax"></has-error>
            </div>
          </vs-col>

        </vs-col>
        <vs-col class="ms-tab-show pl-3" vs-type="flex" vs-justify="center" vs-lg="4" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 lg:mx-3">
            <label for=""><small>ارزش قرارداد</small></label>
            <vx-input-group class="">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>AFN</span>
                </div>
              </template>
              <vs-input autocomplete="off" v-model="visualFields.pr_worth" @input="formatToEnPrice($event, pForm, 'pr_worth', visualFields)" v-validate="'required'" name="pr_worth" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{ errors.first('step-2.pr_worth') }}</span>
            <has-error :form="pForm" field="pr_worth"></has-error>
          </div>
        </vs-col>
      </vs-row>
      <vs-row vs-w="12" class="sm-tab-hide mb-base">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 lg:mx-3 md:mx-3 sm:mx-2">
            <label for=""><small>ارزش قرارداد</small></label>
            <vx-input-group class="">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>AFN</span>
                </div>
              </template>
              <vs-input autocomplete="off" v-model="visualFields.pr_worth" @input="formatToEnPrice($event, pForm, 'pr_worth', visualFields)" v-validate="'required'" name="pr_worth" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{ errors.first('step-2.pr_worth') }}</span>
            <has-error :form="pForm" field="pr_worth"></has-error>
          </div>
        </vs-col>
      </vs-row>
    </form>
  </tab-content>
  <tab-content title="بررسی" class="mb-5">
    <vs-row vs-w="12" style="background-color: #f3f5f7; border-color: #42b983; padding: 1rem 0;border-right-width:0.6rem;border-right-style: solid;margin: 1rem 0">
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="12" vs-sm="12" vs-xs="12">
        <h4>&nbsp;مرور بخش معلومات عمومی&nbsp;</h4>
      </vs-col>
    </vs-row>
    <vs-row vs-w="12">
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            سریال نمبر:
          </strong>
          <small class="mb-5" v-text="pForm.serial_no" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            انتخاب اعلان:
          </strong>
          <small class="mb-5" v-text="(pForm.proposal_id) ? pForm.proposal_id.text : ''" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            تاریخ عقد قرارداد:
          </strong>
          <small class="mb-5" v-text="pForm.contract_date" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-1">
          <strong class="mr-4">
            مرجع مربوطه :
          </strong>
          <small class="mb-5" v-text="pForm.client_id.name" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            عنوان قرارداد:
          </strong>
          <small class="mb-5" v-text="pForm.title" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            نوعیت قرارداد:
          </strong>
          <small v-if="pForm.status== 2" class="mb-5" vs-justify="right" vs-align="right">معین</small>
          <small v-if="pForm.status== 1" class="mb-5" vs-justify="right" vs-align="right">چارچوبی</small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            شماره شناسایی قرارداد :
          </strong>
          <small class="mb-5" v-text="pForm.reference_no" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            تاریخ ختم قرارداد:
          </strong>
          <small class="mb-5" v-text="pForm.contract_end_date" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            تضمین حسن اجرا:
          </strong>
          <span class="mb-5" vs-justify="right" vs-align="right">{{ pForm.project_guarantee | NumThreeDigit }}</span>
          <small style="color:#42b983;"><b>افغانی </b></small>
        </h6>
      </vs-col>
    </vs-row>
    <br>
    <vs-row vs-w="12" class="project-view-header">
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="12" vs-sm="12" vs-xs="12">
        <h4>&nbsp;مرور بخش اکمالات /اقلام&nbsp;</h4>
      </vs-col>
    </vs-row>
      <ekmalat-view-list :items="pForm.item" />
    <vs-row vs-w="12" class="expense-section">
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="12" vs-sm="12" vs-xs="12">
        <h4>&nbsp;مرور بخش مصارف&nbsp;</h4>
      </vs-col>
    </vs-row>
    <vs-row ws-w="12">
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            تامینات:
          </strong>
          <span class="mb-5" v-text="pForm.deposit" vs-justify="right" vs-align="right"></span>
          <small style="color:#42b983;"><b>% </b></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            مالیات:
          </strong>
          <small class="mb-5" v-text="pForm.tax" vs-justify="right" vs-align="right"></small>
          <small style="color:#42b983;"><b>% </b></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            متفرقه:
          </strong>
          <span class="mb-5" vs-justify="right" vs-align="right">{{ pForm.others | NumThreeDigit }}</span>
          <small style="color:#42b983;"><b>افغانی </b></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            ارزش قرارداد:
          </strong>
          <span class="mb-5" vs-justify="right" vs-align="right">{{ pForm.pr_worth | NumThreeDigit }}</span>
          <small style="color:#42b983;"><b>افغانی </b></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            انتقالات:
          </strong>
          <span class="mb-5" vs-justify="right" vs-align="right">{{ pForm.transit | NumThreeDigit }}</span>
          <small style="color:#42b983;"><b>افغانی </b></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            نرخ دهی:
          </strong>
          <span class="mb-5" vs-justify="right" vs-align="right">{{ pForm.total_price | NumThreeDigit }}</span>
          <small style="color:#42b983;"><b>افغانی </b></small>
        </h6>
      </vs-col>
    </vs-row>
    <br>
    <vs-row vs-w="12" class="pt-6 pb-6">
      <vs-checkbox color="success" size="large" v-model="is_accepted">تایید مینمایم که معلومات فوق درست میباشد.</vs-checkbox>
    </vs-row>
  </tab-content>
  <vs-button v-if="currentIndex == 2" :disabled="!is_accepted && pForm.total_price == 0" slot="finish">ثبت معلومات</vs-button>
</form-wizard>
</template>

<script>
import vSelect from "vue-select";
import Ekmalat from "../shared/Ekmalat";

import {
  FormWizard,
  TabContent
} from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import {
  Validator
} from 'vee-validate'
import ProSerialNumber from '../shared/ProSerialNumber.vue';
import EkmalatViewList from "../shared/EkmalatViewList";

export default {
  components: {
    "v-select": vSelect,
    FormWizard,
    TabContent,
    ProSerialNumber,
    Ekmalat,
    EkmalatViewList
  },
  props: ['clients', 'newClient'],
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      currentIndex: 0,
      is_accepted: false,
      // init values
      mainSNumber: 0,
      // Project Form
      pForm: new Form({
        user_id: localStorage.getItem('id'),
        serial_no: '',
        proposal_id: '',
        client_id: '',
        company_id: null,
        title: '',
        reference_no: 0,
        status: "1",
        contract_date: this.momentj().format('jYYYY/jMM/jDD'),
        contract_end_date: this.momentj().format('jYYYY/jMM/jDD'),
        project_guarantee: 0,
        item: [{
          item_id: "",
          unit_id: "",
          operation_id: null,
          equivalent: 1,
          ammount: 0,
          unit_price: 0,
          total_price: 0,
          density: 1,
        }],

        deposit: 0,
        tax: 0,
        others: 0,
        pr_worth: 0,
        transit: 0,
        total_price: 0,
      }),
      visualFields: {
        deposit: 0,
        tax: 0,
        others: 0,
        pr_worth: 0,
        transit: 0,
        total_price: 0,
        project_guarantee: 0,
      },
      items: [],
      mesure_unit: [],
      proposals: [],
      itemType: [],
      // End Project Form

      // Data Sidebar
      addNewDataSidebar: false,
      sidebarData: {},
      statusFa: {
        on_hold: "درجریان",
        delivered: "تکمیل",
        canceled: "نا موفق",
      },
      selected: [],
      // products: [],
      itemsPerPage: 4,
      isMounted: false,
      addNewDataSidebar: false,
      sidebarData: {},
      proposalList: [],
      companies: [],

      // From Valisation Custom Massage
      dict: {
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
        }
      },
    };
  },
  created() {
    // register custom messages
    Validator.localize('en', this.dict)

    this.$Progress.start()
    this.getAllItems();
    this.getAllUnites();
    this.getProposals();
    if (this.$route.params.id == undefined) {
      // Serial number should be added by company 
      // this.getNextSerialNo();
    } else {
      this.getProject();
    }
  },
  computed: {
    isFormValid() {
      return Object.keys(this.fields).some(key => this.fields[key].validated) && Object.keys(this.fields).some(key => this.fields[key].valid);
    },
  },
  methods: {
    setProjectData(data) {
      if (data) {
        if (data.pro_items.length) {
          if (this.$refs.ekmalat) {
            this.$refs.ekmalat.resetArrays();

            for (let [key, data] of Object.entries(data.pro_items)) {
              this.pForm.item[key] = data;
              // this.pForm.item[key].increment_equiv = 0
              // this.pForm.item[key].increment = 0
              // this.pForm.item[key].density = 0
              // this.pForm.item[key].ammount = 0
              // this.pForm.item[key].equivalent = 0
              // this.pForm.item[key].unit_price = 0
              // this.pForm.item[key].total_price = 0

              // data['increment'] = 0
              // data['increment_equiv'] = 0
              // data['ammount'] = 0
              // data['equivalent'] = 0
              // data['unit_price'] = 0
              // data['total_price'] = 0
              this.$refs.ekmalat.addRow({
                'key': key,
                'data': data
              });
              this.$refs.ekmalat.operationChange(this.pForm.item[key].operation_id, key);
              this.$refs.ekmalat.itemSelected('', this.pForm.item[key].item_id.id, key, this.pForm.item[key].item_id.uom_id.acronym);
            }
          }
        } else {
          this.pForm.item = [{
            item_id: "",
            unit_id: "",
            operation_id: null,
            equivalent: 1,
            ammount: 0,
            unit_price: 0,
            total_price: 0,
            density: "1.00",
          }];
        }
        if (data.pro_data) {
          this.pForm.client_id = data.pro_data.client;
          this.pForm.reference_no = data.pro_data.reference_no;
          this.pForm.title = data.pro_data.title;
          this.pForm.deposit = data.pro_data.deposit;
          this.pForm.others = data.pro_data.others;
          this.pForm.pr_worth = data.pro_data.pr_worth;
          this.pForm.tax = data.pro_data.tax;
          this.pForm.total_price = data.pro_data.total_price;
          this.pForm.transit = data.pro_data.transit;

          // Assign to the visualFields
          this.visualFields.deposit = this.formatToEnPriceSimple(data.pro_data.deposit);
          this.visualFields.others = this.formatToEnPriceSimple(data.pro_data.others);
          this.visualFields.pr_worth = this.formatToEnPriceSimple(data.pro_data.pr_worth);
          this.visualFields.tax = this.formatToEnPriceSimple(data.pro_data.tax);
          this.visualFields.total_price = this.formatToEnPriceSimple(data.pro_data.total_price);
          this.visualFields.transit = this.formatToEnPriceSimple(data.pro_data.transit);
          // this.pForm.status = (data.status == 'A') ? "1" : "2";
          this.pForm.company_id = data.pro_data.company_id;
          this.getNextSerialNo(data.pro_data.company_id.sign, true);
        }
      }
    },
    // for getting measure unit of the item
    getAllUnites() {
      this.axios.get('/api/m-units')
        .then((response) => {
          this.mesure_unit = response.data;
          this.getCompanies();
        })
    },

    findItem(id) {
      let item = '';
      Object.keys(this.items).some(key => (this.items[key].id == id) ? item = this.items[key].name : null);
      return item;
    },
    findUom(id) {
      let name = '';
      Object.keys(this.mesure_unit).some(key => (this.mesure_unit[key].id == id) ? name = this.mesure_unit[key].acronym : null);
      return name;
    },
    // for items to be bought
    getCompanies() {
      this.$Progress.start()
      this.axios.get('/api/companies')
        .then((response) => {
          this.companies = response.data;
          this.$Progress.set(100)
        })
    },
    // 'asdfdsfds', for getting the next serian number
    getNextSerialNo(sign = null, has_clint = false) {
      this.axios.get(`/api/serial-num?type=pro-${sign}`)
        .then((response) => {
          this.pForm.serial_no = response.data;
          if (!has_clint && this.newClient) {
            this.pForm.client_id = this.clients.find(e => !!e);
          }
        })
    },

    // for Organs that implement the ad
    getAllClients() {
      this.axios.get('/api/clients')
        .then((response) => {
          this.clients = response.data;
        })
    },

    // for items to be bought
    getAllItems() {
      this.axios.get('/api/items')
        .then((response) => {
          this.items = response.data;

        })
    },
    getProposals() {
      this.axios.get('/api/proposal?no_project=yes')
        .then((response) => {
          this.proposals = response.data;
          // Object.keys(this.proposals).some(key => this.proposals[key]['title'] = this.proposals[key].pro_data.title);
          // 
          this.$Progress.set(100)
        })
    },

    // set the id of Selected Organ
    setClientId(arr) {
      this.pForm.client_id = arr.text;
    },

    // set the id of Selected Goods/Product name
    setItemId(arr) {
      this.pForm.item.item_id = arr.id;
    },

    // set the id of Selected Measure Unite
    setUnitId(arr) {
      this.pForm.item.unit_id = arr.id;
    },

    addNewRow() {
      this.pForm.item.push({
        item_id: "",
        unit_id: "",
        operation_id: null,
        equivalent: 1,
        ammount: "3",
        unit_price: "302",
        total_price: "906",
        density: 1,
      })
    },
    validateStep1() {
      return new Promise((resolve, reject) => {
        this.$validator.validateAll('step-1').then(result => {
          if (result) {
            resolve(true)
          } else {
            reject('correct all values')
          }
        })
      })
    },
    validateStep2() {
      this.$refs.ekmalat.validateEkmalatForm();
      return new Promise((resolve, reject) => {
        this.$validator.validateAll('step-2').then(result => {
          if (result) {
            resolve(true)
          } else {
            reject('correct all values')
          }
        })
      })
    },
    submitForm() {
      // Start the Progress Bar
      if (this.is_accepted) {
        swal.fire({
          title: 'آیا مطمئن هستید؟',
          icon: 'question',
          showDenyButton: true,
          confirmButtonColor: 'rgb(54 34 119)',
          denyButtonColor: 'rgb(229 83 85)',
          confirmButtonText: '<span>بله، ثبت شود!</span>',
          denyButtonText: '<span>خیر، لغو عملیه!</span>'
        }).then((result) => {
          if (result.isConfirmed) {
            this.$Progress.start()
            if (this.$route.params.id !== undefined) {
              this.pForm.patch('/api/project/' + this.$route.params.id)
                .then(({
                  data
                }) => {
                  this.$Progress.set(100)
                  this.$vs.notify({
                    title: 'موفقیت!',
                    text: 'قرارداد موفقانه آپدیت شد.',
                    color: 'success',
                    iconPack: 'feather',
                    icon: 'icon-check',
                    position: 'top-right'
                  })
                  this.$router.push(`/projects/project/${data.id}`).catch(() => {})
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
              this.pForm.post('/api/project')
                .then(({
                  data
                }) => {
                  // Finish the Progress Bar
                  this.$refs.wizard.reset();
                  this.pForm.reset();
                  this.errors.clear();
                  this.$Progress.set(100)
                  this.$vs.notify({
                    title: 'موفقیت!',
                    text: 'قرارداد موفقانه ثبت شد.',
                    color: 'success',
                    iconPack: 'feather',
                    icon: 'icon-check',
                    position: 'top-right'
                  })
                  this.$router.push(`/projects/project/${data.id}`).catch(() => {})
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
            }
          }
        })

      } else {
        swal.fire({
          title: 'عدم تاییدی معلومات!',
          text: 'لطفا ابتداصحت معلومات را تایید کنید.',
          icon: 'error',
        })
      }
    },
    formReset() {
      this.pForm.reset();
      this.pForm.s_number = this.mainSNumber;
    },
    getProject() {
      this.axios.get('/api/project/' + this.$route.params.id)
        .then((response) => {
          this.setProjectDataEdit(response.data);
          this.$Progress.set(100)
        })
    },
    setPFromValue(resp) {
      // for (let [key,value] of Object.entries(resp)) {
      //   this.pForm[key] = value;
      // }
      for (let [key, value] of Object.entries(resp.pro_data)) {
        this.pForm[key] = value;
      }
      for (let [key, value] of Object.entries(resp)) {
        if (key == 'status') {
          this.pForm[key] = (value == 'income') ? 1 : 2;
        } else {

          this.pForm[key] = value;
        }
      }
      if (resp.pro_items) {
        this.pForm.item = resp.pro_items;
      }
      this.pForm.client_id = resp.pro_data.client;
    },
    companySelected(data) {
      this.pForm.company_id = data;
      this.getNextSerialNo(data.sign);
    },
    setProjectDataEdit(pdata) {
      let p = pdata;
      let pd = p.pro_data;
      this.pForm.client_id = pd.client,
        this.pForm.serial_no = p.serial_no,
        this.pForm.company_id = pd.company_id,
        this.pForm.offer_guarantee_type = p.offer_guarantee_type,
        this.pForm.total_price = pd.total_price,
        this.pForm.proposal_id = p.proposal_id,
        this.pForm.title = pd.title,
        this.pForm.reference_no = pd.reference_no,
        this.pForm.contract_end_date = p.contract_end_date,
        this.pForm.contract_end_date = p.contract_end_date,
        this.pForm.project_guarantee = p.project_guarantee,
        this.pForm.discount = pd.discount,
        this.pForm.deposit = pd.deposit,
        this.pForm.tax = pd.tax,
        this.pForm.others = pd.others,
        this.pForm.pr_worth = pd.pr_worth,
        this.pForm.transit = pd.transit,
        this.visualFields = {
          others: this.formatToEnPriceSimple(pd['others']),
          pr_worth: this.formatToEnPriceSimple(pd['pr_worth']),
          transit: this.formatToEnPriceSimple(pd['transit']),
          total_price: this.formatToEnPriceSimple(pd['total_price']),
          project_guarantee: this.formatToEnPriceSimple(p['project_guarantee']),
        };
      this.pForm.status = (p.status == 'A') ? "1" : "2";
      if (p.pro_items.length) {
        if (this.$refs.ekmalat) {
          this.$refs.ekmalat.resetArrays();
          for (let [key, data] of Object.entries(p.pro_items)) {
            this.pForm['item'][key] = data;
            this.$refs.ekmalat.addRow({
              'key': key,
              'data': data
            });
            this.$refs.ekmalat.operationChange(this.pForm.item[key].operation_id, key);
            this.$refs.ekmalat.itemSelected('', this.pForm.item[key].item_id.id, key, this.pForm.item[key].item_id.uom_id.acronym);
          }
        }

      } else {
        this.pForm.item = [{
          item_id: "",
          unit_id: "",
          operation_id: null,
          equivalent: 1,
          ammount: 0,
          unit_price: 0,
          total_price: 0,
          density: "1.00",
        }];
      }

    }
  },
  // End Of methods
  computed: {
    // To calculate the total price for :the proposal
    total_cost: function () {
      let others = (this.pForm.others) ? parseInt(this.pForm.others) : 0;
      let transit = (this.pForm.transit) ? parseInt(this.pForm.transit) : 0;
      // let pr_worth = (this.pForm.pr_worth) ? parseInt(this.pForm.pr_worth) : 0;

      let total_items = 0;
      this.pForm.item.filter(function (item) {
        if (item && item.total_price) {
          total_items += parseInt(item.total_price);
        }
      })
      var total = total_items;
      var nStr = total;
      if (nStr > 0) {
        nStr = nStr.toString()
        nStr = (nStr > 0) ? nStr.replace(/^0+/, '') : nStr;
        nStr = nStr.replace(/\,/g, "");
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
          x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        var value = x1 + x2;
        this.pForm['total_price'] = value.replace(/[^0-9.]/g, '');
        return value;
      } else {
        this.pForm['total_price'] = 0;
        return 0;
      }
    },
  },

};
</script>

<style lang="scss">
#data-list-thumb-view0 {
  .vs-con-table {
    .product-name {
      max-width: 23rem;
    }

    .vs-table--header {
      display: flex;
      flex-wrap: wrap-reverse;
      margin-left: 1.5rem;
      margin-right: 1.5rem;

      >span {
        display: flex;
        flex-grow: 1;
      }

      .vs-table--search {
        padding-top: 0;

        .vs-table--search-input {
          padding: 0.9rem 2.5rem;
          font-size: 1rem;

          &+i {
            left: 1rem;
          }

          &:focus+i {
            left: 1rem;
          }
        }
      }
    }

    .vs-table {
      border-collapse: separate;
      border-spacing: 0 1.3rem;
      padding: 0 1rem;

      tr {
        // box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);

        td {
          padding: 10px;

          &:first-child {
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
          }

          &:last-child {
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
          }

          &.img-container {

            // width: 1rem;
            // background: #fff;
            span {
              display: flex;
              justify-content: flex-start;
            }

            .product-img {
              height: 110px;
            }
          }
        }

        td.td-check {
          padding: 20px !important;
        }
      }
    }

    .vs-table--thead {
      th {
        padding-top: 0;
        padding-bottom: 0;

        .vs-table-text {
          text-transform: uppercase;
          font-weight: 600;
        }
      }

      th.td-check {
        padding: 0 15px !important;
      }

      tr {
        background: none;
        box-shadow: none;
      }
    }

    .vs-table--pagination {
      justify-content: center;
    }
  }
}
</style>
