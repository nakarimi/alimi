<template>
<form-wizard color="rgba(var(--vs-primary), 1)" :title="null" :subtitle="null" :start-index.sync="curTabIndex" ref="wizard" :hideButtons="true" @on-complete="$emit('closesteps')">
  <tab-content title="دریافت قرار داد" vs-w="12" class="mb-1">
    <vs-row vs-w="12">
      <vs-divider>دریافت قرار داد</vs-divider>
    </vs-row>
    <vs-row vs-w="12">
      <vs-col class="pr-5" vs-lg="6" vs-sm="6" vs-xs="12">
        <div class="vx-row">
          <vs-col vs-lg="3" vs-sm="4" vs-xs="12">
            <div class="img-container" v-if="project.pro_data">
              <img :src="'/images/img/clients/'+project.pro_data.client.logo" style="height:80px;margin:-1px 10px" class="product-img" />
            </div>
          </vs-col>
          <vs-col vs-lg="9" class="pl-3" vs-align="right" vs-sm="8" vs-xs="12">
            <div class="vx-row w-full">
              <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                <p clas="w-full" v-if="project.pro_data"><strong>نام نهاد: </strong><span v-text="project.pro_data.client.name"></span></p>
              </vs-col>
            </div>
            <div class="vx-row">
              <vs-col class="mb-1" vs-justify="righ" vs-align="right" vs-w="12">
                <p class="w-full" v-if="project.pro_data"><strong>شماره قرارداد: </strong><span v-text="project.pro_data.reference_no"></span></p>
              </vs-col>
            </div>
            <div class="vx-row">
              <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                <p class="w-full pr-5" v-if="project.pro_data"><strong>عنوان قرارداد: </strong><span v-text="project.pro_data.title"></span></p>
              </vs-col>
            </div>
            <div class="vx-row">
              <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                <p class="w-full pr-5" v-if="project.pro_data"><strong>تاریخ قرارداد: </strong>(<span v-text="project.contract_date"></span>&nbsp;-&nbsp;<span v-text="project.contract_end_date"></span>)</p>
              </vs-col>
            </div>
          </vs-col>
        </div>
      </vs-col>
      <vs-col class="pr-5" vs-lg="6" vs-sm="6" vs-xs="12">
        <div class="w-full pt-2 ml-3 mr-3 p-3">
          <!--<label for class="ml-4 mr-4 mb-2">نوعیت قرارداد</label>-->
          <div class="radio-group w-full">
            <div class="w-1/2">
              <input type="radio" v-model="stepForm.statusActive" value="1" id="active" name="statusActive" />
              <label @click="changeStepStatus(1)" for="active" style="font-size:15px;" class="w-full text-center p-6">قرارداد فعال است</label>
            </div>
            <div class="w-1/2">
              <input type="radio" v-model="stepForm.statusActive" value="2" id="deactive" name="statusActive" />
              <label @click="changeStepStatus(2)" for="deactive" style="font-size:14px;" class="w-full text-center p-6">قرارداد داد غیر فعال است</label>
            </div>
          </div>
        </div>
      </vs-col>
    </vs-row>
    <vs-divider>بخش اکمالات</vs-divider>
    <vs-table class="responsive" :data="project.pro_items">
      <template slot="thead">
        <vs-th>جنس / محصول</vs-th>
        <vs-th>مقدار</vs-th>
        <vs-th>عملیه</vs-th>
        <vs-th>قیمت فی واحد</vs-th>
        <vs-th>قیمت مجموعی</vs-th>
      </template>
      <template slot-scope="{data}">
        <vs-tr v-for="(tr, i) in data" :key="i">
          <vs-td>
            <span v-if="tr.item_id">
              <p> {{ tr.item_id.name }} </p>
            </span>
          </vs-td>
          <vs-td>
            <span v-if="tr.equivalent">
              {{ tr.equivalent | NumThreeDigit }} {{ tr.item_id.uom_equiv_id.title }}
            </span>
          </vs-td>
          <vs-td>
            <span v-if="tr.operation_id">
              <p> {{ tr.operation_id.formula }} </p>
            </span>
          </vs-td>
          <vs-td>
            <span v-if="tr.unit_price">
              {{tr.unit_price | NumThreeDigit}} <small style="color:#42b983;"><b>افغانی </b></small>
            </span>
          </vs-td>
          <vs-td>
            <span v-if="tr.total_price">
              {{tr.total_price | NumThreeDigit}} <small style="color:#42b983;"><b>افغانی </b></small>
            </span>
          </vs-td>
        </vs-tr>
      </template>
    </vs-table>
  </tab-content>
  <tab-content title="اطلاعات مالی" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-divider>اطلاعات مالی</vs-divider>
      <vs-row ws-w="12">
        <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              تامینات:
            </strong>
            <span class="mb-5" v-if="project.pro_data" vs-justify="right" vs-align="right"> {{ project.pro_data.deposit | NumThreeDigit}}</span>
            <small style="color:#42b983;"><b>% </b></small>
            <small>({{project.pro_data.total_price * (project.pro_data.deposit / 100)}})</small>
          </h6>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              مالیات:
            </strong>
            <small class="mb-5" v-if="project.pro_data" v-text="project.pro_data.tax" vs-justify="right" vs-align="right"></small>
            <small style="color:#42b983;"><b>% </b></small>
            <small>({{project.pro_data.total_price * (project.pro_data.tax/ 100)}})</small>
          </h6>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              متفرقه:
            </strong>
            <span class="mb-5" v-if="project.pro_data" vs-justify="right" vs-align="right"> {{ project.pro_data.others | NumThreeDigit}}</span>
            <small style="color:#42b983;"><b>افغانی </b></small>
          </h6>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              ارزش قرارداد:
            </strong>
            <span class="mb-5" v-if="project.pro_data" vs-justify="right" vs-align="right"> {{ project.pro_data.pr_worth | NumThreeDigit}}</span>
            <small style="color:#42b983;"><b>افغانی </b></small>
          </h6>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              انتقالات:
            </strong>
            <span class="mb-5" v-if="project.pro_data" vs-justify="right" vs-align="right"> {{ project.pro_data.transit | NumThreeDigit}}</span>
            <small style="color:#42b983;"><b>افغانی </b></small>
          </h6>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
          <h6 class="mb-5 mt-3 ml-2">
            <strong class="mr-4">
              نرخ دهی:
            </strong>
            <span class="mb-5" v-if="project.pro_data" vs-justify="right" vs-align="right"> {{ project.pro_data.total_price | NumThreeDigit}}</span>
            <small style="color:#42b983;"><b>افغانی </b></small>
          </h6>
        </vs-col>
      </vs-row>
    </vs-row>
  </tab-content>
  <tab-content title="مراحل ختم پروژه" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>مراحل ختم پروژه</vs-divider>
      </vs-row>
      <vs-row vs-w="12">
        <vs-col class="pr-5" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="vx-row">
            <vs-col vs-lg="3" vs-sm="4" vs-xs="12">
              <div class="img-container" v-if="project.pro_data">
                <img :src="'/images/img/clients/'+project.pro_data.client.logo" style="height:80px;margin:-1px 10px" class="product-img" />
              </div>
            </vs-col>
            <vs-col vs-lg="9" class="pl-3" vs-align="right" vs-sm="8" vs-xs="12">
              <div class="vx-row w-full">
                <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                  <p clas="w-full" v-if="project.pro_data"><strong>نام نهاد: </strong><span v-text="project.pro_data.client.name"></span></p>
                </vs-col>
              </div>
              <div class="vx-row">
                <vs-col class="mb-1" vs-justify="righ" vs-align="right" vs-w="12">
                  <p class="w-full" v-if="project.pro_data"><strong>شماره قرارداد: </strong><span v-text="project.pro_data.reference_no"></span></p>
                </vs-col>
              </div>
              <div class="vx-row">
                <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                  <p class="w-full pr-5" v-if="project.pro_data"><strong>عنوان قرارداد: </strong><span v-text="project.pro_data.title"></span></p>
                </vs-col>
              </div>
              <div class="vx-row">
                <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                  <p class="w-full pr-5" v-if="project.pro_data"><strong>تاریخ قرارداد: </strong>(<span v-text="project.contract_date"></span>&nbsp;-&nbsp;<span v-text="project.contract_end_date"></span>)</p>
                </vs-col>
              </div>
            </vs-col>
          </div>
        </vs-col>
        <vs-col class="pr-5" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="p-2">
            <div class="p-2">
              <vs-checkbox color="success" size="large" v-model="stepForm.mactob_sending"><strong>ارسال مکتوبات </strong></vs-checkbox>
            </div>
            <div class="p-2">
              <vs-checkbox color="success" size="large" v-model="stepForm.adminis_prove"><strong>طی مراحل اداری</strong></vs-checkbox>
            </div>
            <div class="p-2">
              <vs-checkbox color="success" size="large" v-model="stepForm.setting_and_baqyat"><strong>دریافت تضمینات و باقیات</strong></vs-checkbox>
            </div>
          </div>
        </vs-col>
      </vs-row>
      <vs-divider></vs-divider>
    </vs-row>
  </tab-content>
  <tab-content title="تکمیل پروژه" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>تکمیل پروژه</vs-divider>
      </vs-row>
      <vs-row vs-w="12">
        <vs-col class="pr-5" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="vx-row">
            <vs-col vs-lg="3" vs-sm="4" vs-xs="12">
              <div class="img-container" v-if="project.pro_data">
                <img :src="'/images/img/clients/'+project.pro_data.client.logo" style="height:80px;margin:-1px 10px" class="product-img" />
              </div>
            </vs-col>
            <vs-col vs-lg="9" class="pl-3" vs-align="right" vs-sm="8" vs-xs="12">
              <div class="vx-row w-full">
                <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                  <p clas="w-full" v-if="project.pro_data"><strong>نام نهاد: </strong><span v-text="project.pro_data.client.name"></span></p>
                </vs-col>
              </div>
              <div class="vx-row">
                <vs-col class="mb-1" vs-justify="righ" vs-align="right" vs-w="12">
                  <p class="w-full" v-if="project.pro_data"><strong>شماره قرارداد: </strong><span v-text="project.pro_data.reference_no"></span></p>
                </vs-col>
              </div>
              <div class="vx-row">
                <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                  <p class="w-full pr-5" v-if="project.pro_data"><strong>عنوان قرارداد: </strong><span v-text="project.pro_data.title"></span></p>
                </vs-col>
              </div>
              <div class="vx-row">
                <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                  <p class="w-full pr-5" v-if="project.pro_data"><strong>تاریخ قرارداد: </strong>(<span v-text="project.contract_date"></span>&nbsp;-&nbsp;<span v-text="project.contract_end_date"></span>)</p>
                </vs-col>
              </div>
            </vs-col>
          </div>
        </vs-col>
        <vs-col class="pr-5" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="p-5">
            <vs-checkbox color="success" size="large" v-model="stepForm.finishcontract"><strong>ختم قرارداد</strong></vs-checkbox>
          </div>
        </vs-col>
      </vs-row>
      <vs-divider>بخش اکمالات</vs-divider>
      <vs-table class="responsive" :data="project.pro_items">
        <template slot="thead">
          <vs-th>جنس / محصول</vs-th>
          <vs-th>مقدار</vs-th>
          <vs-th>عملیه</vs-th>
          <vs-th>قیمت فی واحد</vs-th>
          <vs-th>قیمت مجموعی</vs-th>
        </template>
        <template slot-scope="{data}">
          <vs-tr v-for="(tr, i) in data" :key="i">
            <vs-td :data="tr.item_id">
              <p> {{ tr.item_id.name }} </p>
            </vs-td>
            <vs-td :data="tr.equivalent">
              {{ tr.equivalent | NumThreeDigit }} {{ tr.item_id.uom_equiv_id.title }}
            </vs-td>
            <vs-td :data="tr.operation_id">
              <p> {{ tr.operation_id.formula }} </p>
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
      <vs-divider></vs-divider>
    </vs-row>
  </tab-content>
  <div v-bind:class="{ 'float-right': curTabIndex == 0 }" class="flex space-between mt-2" v-if="$refs.wizard">
    <vs-button v-if="curTabIndex > 0" @click="$refs.wizard.prevTab">قبلی</vs-button>
    <div class="flex">
      <vs-button v-if="!$refs.wizard.isLastStep && curTabIndex == 2" class="mx-3" @click.stop="changeStepStatus2" color="success">ثبت</vs-button>
      <vs-button v-if="curTabIndex == 3" @click="formSubmitted" class="mx-3" color="success">ثبت نهایی</vs-button>
      <vs-button v-if="!$refs.wizard.isLastStep" @click="$refs.wizard.nextTab">بعدی</vs-button>
      <vs-button v-if="$refs.wizard.isLastStep" @click="$emit('closesteps')">بستن صفحه</vs-button>
    </div>
  </div>
</form-wizard>
</template>

<script>
import {
  FormWizard,
  TabContent
} from 'vue-form-wizard'
export default {
  props: ['project'],
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      curTabIndex: 0,
      firstloadstep: 0,
      stepForm: new Form({
        step: 0,
        project_id: 0,
        statusActive: 2,
        is_ekmalat_allowed: 0,
        adminis_prove: 0,
        finishcontract: 0,
        mactob_sending: 0,
        setting_and_baqyat: 0,
      })
    }
  },
  components: {
    FormWizard,
    TabContent
  },
  created() {
    // 
    // setInterval(this.step = this.step + 1, 2000);
  },
  computed: {},
  methods: {
    formSubmitted() {
      this.stepForm.step = 3;
      this.changeItTo(this.project.id, this.stepForm.step)
    },
    setWizardStep(index = 1, pr) {
      console.log(pr);
      this.firstloadstep = index;
      this.stepForm.step = index;
      this.stepForm.project_id = pr.project_id;
      this.stepForm.is_ekmalat_allowed = pr.is_ekmalat_allowed;
      this.stepForm.mactob_sending = pr.mactob_sending;
      this.stepForm.adminis_prove = pr.adminis_prove;
      this.stepForm.finishcontract = pr.finishcontract;
      this.stepForm.statusActive = pr.statusActive;
      this.stepForm.setting_and_baqyat = pr.setting_and_baqyat;
      this.curTabIndex = index;
      this.$refs.wizard.activateAll();
      this.$refs.wizard.navigateToTab(index);
    },
    changeItTo(id, st) {
      this.$Progress.start()
      this.stepForm.post('/api/projectstchange/' + id)
        .then((response) => {
          this.$Progress.set(100);
            if (st == 3) {
              this.$vs.notify({
                title: 'موفقیت!',
                text: 'تمام مراحل ختم گردید',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
            } else {
              this.$vs.notify({
                title: 'موفقیت!',
                text: ' مرحله موفقانه به ' + st + ' تغیر یافت.',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
            }
        }).catch(() => {
              this.$vs.notify({
                title: 'نا موفقیت!',
                text: 'معلومات را بررسی کنید.',
                color: 'danger',
                iconPack: 'feather',
                icon: 'icon-alert-triangle',
                position: 'top-right'
              })
        });
    },
    changeStepStatus(step) {
      this.stepForm.step = step;
      this.changeItTo(this.project.id, this.stepForm.step);
    },
    changeStepStatus2() {
      if (this.stepForm.is_ekmalat_allowed) {
        this.stepForm.step = 3;
        this.changeItTo(this.project.id, this.stepForm.step)
      }
    },
  },
  mounted() {}
}
</script>

<style>
[dir] .vs-button:not(.vs-radius):not(.includeIconOnly):not(.small):not(.large) {
  padding: 0.75rem 2rem;
  float: left;
}
</style>
