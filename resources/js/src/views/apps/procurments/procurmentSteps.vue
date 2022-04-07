<template>
<form-wizard color="rgba(var(--vs-primary), 1)" :start-index.sync="step" :hideButtons="true" :title="null" :subtitle="null" ref="wizardproc" @on-complete="formSubmitted">
  <tab-content v-if="procurment" title="اطلاعات خریداری" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>اطلاعات خریداری</vs-divider>
      </vs-row>

      <vs-row vs-w="12">
        <vs-col :key="i" v-for="(field, i) in display_fields" vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              {{ (typeof field === 'object') ? $t(field[0]) : $t(field) }}:
            </strong>
            <small class="mb-5" v-if="(typeof field !== 'object')">{{ procurment[field] }}</small>
            <small class="mb-5" v-if="(typeof field === 'object')">
              <span :key="i" v-for="(sub, i) in field[1]"> {{ procurment[field[0]][sub] }} </span>
            </small>
          </h6>
        </vs-col>
      </vs-row>
    </vs-row>
  </tab-content>
  <tab-content v-if="procurment" title="اطلاعات مالی" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>اطلاعات خریداری</vs-divider>
      </vs-row>

      <vs-row vs-w="12">
        <vs-col :key="i" v-for="(field, i) in display_fields" vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              {{ (typeof field === 'object') ? $t(field[0]) : $t(field) }}:
            </strong>
            <small class="mb-5" v-if="(typeof field !== 'object')">{{ procurment[field] }}</small>
            <small class="mb-5" v-if="(typeof field === 'object')">
              <span :key="i" v-for="(sub, i) in field[1]"> {{ procurment[field[0]][sub] }} </span>
            </small>
          </h6>
        </vs-col>
      </vs-row>
    </vs-row>
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>اطلاعات مالی</vs-divider>
      </vs-row>
      <vs-table :data="procurment.stock" class="w-full">
        <template slot="thead">
          <vs-th>جنس / محصول</vs-th>
          <vs-th>مقدار</vs-th>
          <vs-th>قیمت فی واحد</vs-th>
          <vs-th>قیمت مجموعی</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr v-for="(tr, i) in data" :key="i">
            <vs-td :data="tr.item_id">
              <p> {{ (tr.item_id && tr.item_id.type) ? tr.item_id.type.type : '' }} {{ tr.item_id.name }} </p>
            </vs-td>
            <vs-td :data="tr.increment_equiv">
              {{tr.increment_equiv}} {{ tr.item_id.uom_equiv_id.title }}
            </vs-td>
            <vs-td :data="tr.unit_price">
              {{tr.unit_price | NumThreeDigit}} <small style="color:#42b983;"><b>افغانی </b></small>
            </vs-td>
            <vs-td :data="tr.total_price">
              {{tr.total_price | NumThreeDigit}} <small style="color:#42b983;"><b>افغانی </b></small>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </vs-row>
  </tab-content>
  <tab-content v-if="procurment" title="تصفیه حسابات" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>اطلاعات خریداری</vs-divider>
      </vs-row>
      <vs-row vs-w="12">
        <vs-col :key="i" v-for="(field, i) in display_fields" vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              {{ (typeof field === 'object') ? $t(field[0]) : $t(field) }}:
            </strong>
            <small class="mb-5" v-if="(typeof field !== 'object')">{{ procurment[field] }}</small>
            <small class="mb-5" v-if="(typeof field === 'object')">
              <span :key="i" v-for="(sub, i) in field[1]"> {{ procurment[field[0]][sub] }} </span>
            </small>
          </h6>
        </vs-col>
      </vs-row>
    </vs-row>
    <vs-row vs-w="12" class="mb-1">
      <vs-divider>تصفیه حسابات</vs-divider>
      <vs-row>

        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 mr-3">
            <!-- TITLE -->
            <label for=""><small>مصارف انتقال</small></label>
            <vx-input-group class="number-rtl">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>AFN</span>
                </div>
              </template>
              <vs-input autocomplete="off" v-model="costForm.service_cost" type="number" v-validate="'required'" name="deposit" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{
              errors.first("s1Form.deposit")}}</span>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 lg:ml-3 md:ml-0 mr-3">
            <label for=""><small>قیمت مجموعی</small></label>
            <vx-input-group class="number-rtl">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>AFN</span>
                </div>
              </template>
              <vs-input autocomplete="off" v-model="costForm.total_price" type="number" v-validate="'required'" name="tax" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{
              errors.first("s1Form.tax")
            }}</span>
          </div>
        </vs-col>
      </vs-row>
    </vs-row>
  </tab-content>
  <tab-content v-if="procurment" title="تاییدی" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>اطلاعات خریداری</vs-divider>
      </vs-row>
      <vs-row vs-w="12">
        <vs-col :key="i" v-for="(field, i) in display_fields" vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              {{ (typeof field === 'object') ? $t(field[0]) : $t(field) }}:
            </strong>
            <small class="mb-5" v-if="(typeof field !== 'object')">{{ procurment[field] }}</small>
            <small class="mb-5" v-if="(typeof field === 'object')">
              <span :key="i" v-for="(sub, i) in field[1]"> {{ procurment[field[0]][sub] }} </span>
            </small>
          </h6>
        </vs-col>
      </vs-row>
      <vs-row vs-w="12">
        <vs-divider>تاییدی</vs-divider>
      </vs-row>
      <vs-col class="pl-5 my-3">
        <vs-checkbox v-model="is_confirmed" color="success" class="float-left" size="large">
          <strong>تمام اطلاعات درج شده توسط مدیر سیستم تایید میگردد.</strong>
        </vs-checkbox>
      </vs-col>
    </vs-row>
  </tab-content>
  <div :key="com_key" v-bind:class="{ 'float-right': step == 0 }" class="flex space-between mt-2" v-if="$refs.wizardproc">
    <vs-button v-if="step > 0" @click="$refs.wizardproc.prevTab">قبلی</vs-button>
    <div class="flex">
      <vs-button v-if="!$refs.wizardproc.isLastStep && step == 2" color="success" @click.prevent="submitServiceCost" style="float: left;margin-top: 2px;" class="mx-2">ثبت</vs-button>
      <vs-button v-if="$refs.wizardproc.isLastStep" color="success" @click.prevent="submitConfirmation" class="mx-2">ثبت</vs-button>
      <vs-button v-if="!$refs.wizardproc.isLastStep" @click="$refs.wizardproc.nextTab">بعدی</vs-button>
      <vs-button v-if="$refs.wizardproc.isLastStep" @click="popupModalActive = false, sale = [], $refs.wizardproc.hideButtons = true">بستن صفحه</vs-button>
    </div>
  </div>
</form-wizard>
</template>

<script>
import {
  FormWizard,
  TabContent
} from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
export default {
  data() {
    return {
      is_confirmed: false,
      com_key: 0,
      user_permissions: localStorage.getItem('user_permissions'),
      step: 0,
      procurment: null,
      costForm: new Form({
        total_price: 0,
        service_cost: 0,
        old_total_price: 0,
      }),
      display_fields: [
        'serial_no',
        'description',
        'date_time',
        ['vendor', ['name']],
        ['user', ['firstName', 'lastName']],
      ],
    }
  },
  created() {},
  components: {
    FormWizard,
    TabContent
  },
  watch: {
    'costForm.service_cost'() {
      let x = parseFloat(this.costForm.service_cost);
      x = (x) ? x : 0;
      this.costForm.total_price = parseFloat(this.costForm.old_total_price) + x;
    }
  },
  methods: {
    submitServiceCost() {
      this.costForm
        .post(`/api/purchase_service_cost/${this.procurment.id}`)
        .then((data) => {
          swal.fire(
            'موفق!',
            'معلومات موفقانه ثبت شد!',
            'success'
          )
        })
        .catch(() => {
          swal.fire(
            'ناموفق !',
            'لطفا معلومات را بررسی کنید!',
            'error'
          )
        });
    },
    submitConfirmation() {
      this.axios
        .post(`/api/sale_info_confirmation/purchase/${this.procurment.id}`, {
          confirmed: this.is_confirmed,
        })
        .then((data) => {
          swal.fire(
            'موفق!',
            'معلومات موفقانه ثبت شد!',
            'success'
          )
        })
        .catch(() => {
          swal.fire(
            'ناموفق !',
            'لطفا معلومات را بررسی کنید!',
            'error'
          )
        });
    },
    getTransaction(id) {
      if (id) {
        this.$Progress.start()
        this.axios.get('/api/procurments/' + id)
          .then((response) => {
            this.procurment = response.data;
            const appLoading = document.getElementById("loading-bg");
            if (appLoading) {
              appLoading.style.display = "none";
            }
            this.costForm.service_cost = this.procurment.fr_service;
            this.costForm.old_total_price = parseFloat(this.procurment.fr.credit) - parseFloat(this.procurment.fr_service);
            this.costForm.total_price = this.procurment.fr.credit;
            this.$Progress.set(100)
            this.com_key += 1;
          })
      }
    },
    formSubmitted() {
      this.$emit('closesteps');
    },
    setWizardStepProc(index) {
      this.step = index;
      this.$refs.wizardproc.activateAll();
      this.$refs.wizardproc.navigateToTab(index - 1);
      // this.$refs.wizard.navigateToTab(2);
    },
  }
}
</script>
