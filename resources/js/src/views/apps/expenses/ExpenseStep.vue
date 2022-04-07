<template>
<form-wizard :hideButtons="true" v-if="expense" color="rgba(var(--vs-primary), 1)" :start-index.sync="current" :title="null" :subtitle="null" ref="wizardexpen" @on-complete="formSubmitted">
  <tab-content title="اطلاعات" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>اطلاعات مصرف</vs-divider>
      </vs-row>
      <vs-row vs-w="12">
        <vs-col :key="i" v-for="(field, i) in fields_display" vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              {{ (typeof field === 'object') ? $t(field[0]) : $t(field) }}:
            </strong>
            <small class="mb-5" v-if="(typeof field !== 'object')">{{ expense[field] }}</small>
            <small class="mb-5" v-if="(typeof field === 'object')">
              <span :key="i" v-for="(sub, i) in field[1]"> {{ (expense[field[0]]) ? expense[field[0]][sub] : '' }} </span>
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
            <small>{{ (expense.credit_account) ? expense.credit_account.name : '' }}</small>
          </h6>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="6" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              حساب دیبت:
            </strong>
            <small>{{ (expense.debit_account) ? expense.debit_account.name : '' }}</small>
          </h6>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="6" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              مقدار:
            </strong>
            <small>{{ expense.debit }} {{(expense.currency) ? expense.currency.sign_fa : 'AFN'}}</small>
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
        <vs-checkbox color="success" size="large" v-model="expForm.is_approved"><strong> معلومات مصارف تایید ادمین است؟ </strong></vs-checkbox>
      </vs-row>
      <vs-row vs-w="12">
        <vs-divider></vs-divider>
      </vs-row>
    </vs-row>
  </tab-content>
  <div :key="com_key" v-bind:class="{ 'float-right': current == 0 }" class="flex space-between mt-2" v-if="$refs.wizardexpen">
    <vs-button v-if="current != 0" @click="$refs.wizardexpen.prevTab">قبلی</vs-button>
    <div class="flex">
      <vs-button v-if="$refs.wizardexpen.isLastStep" color="success" @click.prevent="expenseConfirmation" class="mx-2">ثبت</vs-button>
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
      com_key: 0,
      expense: this.data,
      expense_id: null,
      user_permissions: localStorage.getItem('user_permissions'),
      expForm: new Form({
        step: 0,
        is_approved: 0
      }),
      current: 0,
      fields_display: [
        'serial_no',
        'datetime',
        'title',
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
    setWizardStepExpense(expense) {
      this.expForm.reset();
      this.expense_id = expense.id;
      this.expForm.step = expense.step;
      this.expForm.is_approved = expense.fr.valid;
      this.current = expense.step - 1;
      this.$refs.wizardexpen.activateAll();
      this.$refs.wizardexpen.navigateToTab(expense.step - 1);
    },
    expenseConfirmation() {
      this.$Progress.start()
      this.expForm.post('/api/expense_confirm/' + this.expense_id, {
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
