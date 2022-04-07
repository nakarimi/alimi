<template>
<form-wizard :hideButtons="true" v-if="transaction" color="rgba(var(--vs-primary), 1)" :start-index.sync="current" :title="null" :subtitle="null" ref="wizardexpen" @on-complete="formSubmitted">
  <tab-content title="اطلاعات" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>اطلاعات معامله تجارتی</vs-divider>
      </vs-row>
      <vs-row vs-w="12">
        <vs-col :key="i" v-for="(field, i) in fields_display" vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              {{ (typeof field === 'object') ? $t(field[0]) : $t(field) }}:
            </strong>
            <small class="mb-5" v-if="(typeof field !== 'object')">{{ transaction[field] }}</small>
            <small class="mb-5" v-if="(typeof field === 'object')">
              <span :key="i" v-for="(sub, i) in field[1]"> {{ (transaction[field[0]]) ? transaction[field[0]][sub] : '' }} </span>
            </small>
          </h6>
        </vs-col>
      </vs-row>
      <vs-divider>اطلاعات مالی</vs-divider>

      <vs-row vs-w="12">
        <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="6" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              حساب کردیت:
            </strong>
            <small>{{ (transaction.credit_account) ? transaction.credit_account.name : '' }}</small>
          </h6>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="6" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              حساب دیبت:
            </strong>
            <small>{{ (transaction.debit_account) ? transaction.debit_account.name : '' }}</small>
          </h6>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="6" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              مقدار:
            </strong>
            <small>{{ transaction.debit }} {{ (transaction && transaction.currency) ? transaction.currency.sign_fa : 'AFN'}}</small>
          </h6>
        </vs-col>
      </vs-row>

    </vs-row>
  </tab-content>
  <tab-content title="تاییدی" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>تاییدی</vs-divider>
      </vs-row>
      <vs-row vs-w="12">
        <vs-checkbox color="success" size="large" v-model="transForm.is_approved"><strong> معلومات معامله تجارتی تایید ادمین است؟ </strong></vs-checkbox>
      </vs-row>
      <vs-row vs-w="12">
        <vs-divider></vs-divider>
      </vs-row>
    </vs-row>
  </tab-content>
  <div :key="com_key" v-bind:class="{ 'float-right': current == 0 }" class="flex space-between mt-2" v-if="$refs.wizardexpen">
    <vs-button v-if="current != 0" @click="$refs.wizardexpen.prevTab">قبلی</vs-button>
    <div class="flex">
      <vs-button v-if="$refs.wizardexpen.isLastStep" color="success" @click.prevent="transactionConfirmation" class="mx-2">ثبت</vs-button>
      <vs-button v-if="!$refs.wizardexpen.isLastStep" @click="$refs.wizardexpen.nextTab">بعدی</vs-button>
      <vs-button v-if="$refs.wizardexpen.isLastStep" @click="$emit('closesteps')">بستن صفحه</vs-button>
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
  props: ['data'],
  data() {
    return {
      transaction: this.data,
      com_key: 0,
      transaction_id: null,
      user_permissions: localStorage.getItem('user_permissions'),
      transForm: new Form({
        step: 0,
        is_approved: 0
      }),
      current: 0,
      fields_display: [
        'serial_no',
        'title',
        'datetime',
        'transaction_status',
        'ammount',
        ['currency', ['sign_fa']],
        ['user', ['firstName', 'lastName']],
        'description',
      ],
    }
  },
  created() {
  },
  components: {
    FormWizard,
    TabContent
  },
  methods: {
    formSubmitted() {
      this.$emit('closesteps');
    },
    transactionConfirmation() {
      this.$Progress.start()
      this.transForm.post('/api/transaction_confirm/' + this.transaction_id, {
          confirmed: true
        })
        .then((response) => {
          this.$Progress.set(100);
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
  }
}
</script>

<style>
[dir] .vs-button:not(.vs-radius):not(.includeIconOnly):not(.small):not(.large) {
  padding: 0.75rem 2rem;
  float: left;
}
</style>
