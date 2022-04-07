<template>
<form-wizard color="rgba(var(--vs-primary), 1)" :start-index.sync="currentIndex" :title="null" :subtitle="null" back-button-text="قبلی" next-button-text="بعدی" ref="wizard" finishButtonText="" @on-complete="formSubmitted">
  <tab-content title="معلومات عمومی" class="mb-5" icon="feather icon-home" :before-change="validateStep1">
    <form data-vv-scope="step-1">
      <vs-row vs-w="12">
        <vs-col vs-type="flex" vs-lg="4" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 ml-3 mr-3 mb-3">
            <pro-serial-number v-model="aForm.company_id" v-validate="'required'" name="company_id" :form="aForm" @companySelected="companySelected" :companies="companies"></pro-serial-number>
            <span style="margin-right: -280px;margin-top: 62px;" class="absolute text-danger alerttext">{{ errors.first('step-1.company_id') }}</span>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 ml-3 mr-3 mb-3">
            <label for="date" class="mt-3"><small>تاریخ نشر اعلان</small></label>
            <date-picker inputFormat="jYYYY/jMM/jDD" display-format="jYYYY/jMM/jDD" color="#e85454" name="publish_date" v-validate="'required'" v-model="aForm.publish_date" input-format="YYYY/MM/DD" format="jYYYY/jMM/jDD" :auto-submit="true" size="large"></date-picker>
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.publish_date') }}</span>
            <has-error :form="aForm" field="publish_date"></has-error>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 ml-3 mr-3 mb-3">
            <vs-input autocomplete="off" v-model="aForm.publish_address" label="آدرس نشراعلان" name="publish_address" class="w-full" />
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.publish_address') }}</span>
            <!--<span class="text-danger text-sm" v-show="errors.has('publish_address')">{{ errors.first('publish_address') }}</span> -->
            <has-error :form="aForm" field="publish_address"></has-error>
          </div>
        </vs-col>
      </vs-row>
      <vs-row vs-w="12" class="pt-2">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 ml-3 mr-3 mb-3">
            <label for=""><small>نهاد تطبیق کننده</small></label>
            <v-select label="name" v-model="aForm.client_id" name="client_id" v-validate="'required'" :options="clients" :dir="$vs.rtl ? 'rtl' : 'ltr'" />
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.client_id') }}</span>
            <has-error :form="aForm" field="client_id"></has-error>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="8" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 ml-3 mr-3 mb-3">
            <vs-input autocomplete="off" size="medium" v-model="aForm.title" label="عنوان قرارداد" v-validate="'required|min:3'" name="title" class="w-full" />
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.title') }}</span>
            <has-error :form="aForm" field="title"></has-error>
            <!-- <span class="text-danger text-sm" v-show="errors.has('title')">{{ errors.first('title') }}</span> -->
          </div>
        </vs-col>
      </vs-row>
      <vs-row vs-w="12" class="pt-2">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 ml-3 mb-3">
            <vs-input autocomplete="off" v-model="aForm.reference_no" label="شماره شناسایی قرارداد" name="reference_no" class="w-full" />
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.reference_no') }}</span>
            <has-error :form="aForm" field="reference_no"></has-error>
            <!--<span class="text-danger text-sm" v-show="errors.has('reference_no')">{{ errors.first('reference_no') }}</span>-->
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 ml-3 mb-3">
            <label for="date" class="mt-3"><small>تاریخ تسلیمی</small></label>
            <date-picker inputFormat="jYYYY/jMM/jDD" display-format="jYYYY/jMM/jDD" color="#e85454" name="submission_date" v-validate="'required'" v-model="aForm.submission_date" input-format="YYYY/MM/DD" format="jYYYY/jMM/jDD" :auto-submit="true" size="large"></date-picker>
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.submission_date') }}</span>
            <has-error :form="aForm" field="submission_date"></has-error>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 ml-3 mb-3">
            <label for="date" class="mt-3"><small>تاریخ آفرگشایی</small></label>
            <date-picker inputFormat="jYYYY/jMM/jDD" display-format="jYYYY/jMM/jDD" color="#e85454" v-validate="'required'" v-model="aForm.bidding_date" name="bidding_date" input-format="YYYY/MM/DD" format="jYYYY/jMM/jDD" class="mt-5 w-full" :auto-submit="true" size="large"></date-picker>
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.bidding_date') }}</span>
            <has-error :form="aForm" field="bidding_date"></has-error>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 ml-3 mb-3">
            <vs-input autocomplete="off" size="medium" label="آدرس آفرگشایی" v-model="aForm.bidding_address" name="bidding_address" class="w-full" />
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.bidding_address') }}</span>
            <has-error :form="aForm" field="bidding_address"></has-error>
          </div>
        </vs-col>
              </vs-row>
      <vs-row vs-w="12" class="pt-2">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
          <div class="w-full mt-4 pt-2 ml-3 mb-3">
            <!-- TITLE -->
            <label for=""><small>تضمین آفر</small></label>
            <vx-input-group class="">
              <template slot="prepend">
                <div id="offer" class="prepend-text">
                  <!--<span class="bg-primary">AFN</span>
                  <v-select label="text" v-model="aForm.offer_guarantee_type" :options="optionoffer" :dir="$vs.rtl ? 'rtl' : 'ltr'" />-->
                  <div class="w-full mb-4">
                    <div class="radio-group w-full">
                      <div class="w-1/2">
                        <input type="radio" v-model="aForm.offer_guarantee_type" value="1" id="struct1" name="aForm.offer_guarantee_type" />
                        <label for="struct1" class="w-full text-center">بانکی</label>
                      </div>
                      <div class="w-1/2">
                        <input type="radio" v-model="aForm.offer_guarantee_type" value="2" id="specific1" name="aForm.offer_guarantee_type" />
                        <label for="specific1" id="cache" class="w-full text-center">نقده</label>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
              <template slot="append">
                <div id="offer" class="append-text append-currency">
                  <span class="bg-primary AFGLabel">AFN</span>
                </div>
              </template>
              <vs-input autocomplete="off" name="offer_guarantee" v-validate="'required'" v-model="visualFields.offer_guarantee" @input="formatToEnPrice($event, aForm, 'offer_guarantee', visualFields)" />
              <span class="absolute text-danger alerttext">{{ errors.first('step-1.offer_guarantee') }}</span>
            </vx-input-group>
            <has-error :form="aForm" field="offer_guarantee"></has-error>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
          <div class="w-full mt-4 pt-2 ml-3 mb-3">
            <!-- TITLE -->
            <label for=""><small>قرارداد مشابه</small></label>
            <vx-input-group class="">
              <template slot="prepend">
                <div id="offer" class="prepend-text">
                  <!--<span class="bg-primary">AFN</span> -->
                  <div class="w-full mb-4">
                    <div class="radio-group w-full">
                      <div class="w-1/2">
                        <input type="radio" v-model="samePro" value="1" id="same_contract_yes" name="same_contract" />
                        <label for="same_contract_yes" class="w-full text-center">بلی</label>
                      </div>
                      <div class="w-1/2">
                        <input type="radio" v-model="samePro" value="2" id="same_contract_no" name="same_contract" />
                        <label for="same_contract_no" id="nof" class="w-full text-center">خیر</label>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
              <template slot="append">
                <div id="offer" class="append-text append-currency">
                  <div>
                    <span v-if="(samePro == 1)" class="bg-primary AFGLabel">AFN</span>
                    <span v-if="!(samePro == 1)" class="disabled">AFN</span>
                  </div>
                </div>
              </template>
              <vs-input v-if="!(samePro == 1)" disabled autocomplete="off" name="same_pro" style="background-color:#EBECF0;border-radius: 0 !important;" v-model="visualFields.same_pro" @input="formatToEnPrice($event, aForm, 'same_pro', visualFields)" />
              <vs-input v-if="(samePro == 1)" autocomplete="off" name="same_pro" style="border-radius: 0 !important;" v-model="visualFields.same_pro" @input="formatToEnPrice($event, aForm, 'same_pro', visualFields)" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.same_pro') }}</span>
            <has-error :form="aForm" field="same_pro"></has-error>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 ml-3 mr-3 mb-3">
            <vs-input autocomplete="off" size="medium" label="حجم معاملات" v-model="visualFields.deal_value" @input="formatToEnPrice($event, aForm, 'deal_value', visualFields)" name="deal_value" class="w-full" />
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.deal_value') }}</span>
            <has-error :form="aForm" field="deal_value"></has-error>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
          <div class="w-full mt-4 pt-2 ml-3 mb-3">
            <!-- TITLE -->
            <label for=""><small>توانایی مالی</small></label>
            <vx-input-group class="append-currency-template-group">
              <template slot="prepend">
                <div id="offer" class="prepend-text">
                  <!--<span class="bg-primary">AFN</span>
                  <v-select label="text" v-model="aForm.offer_guarantee_type" :options="optionoffer" :dir="$vs.rtl ? 'rtl' : 'ltr'" />-->
                  <div class="w-full mb-4">
                    <div class="radio-group w-full">
                      <div class="w-1/2">
                        <input type="radio" v-model="financialPower" value="1" id="yesf" name="financialYes" />
                        <label for="yesf" class="w-full text-center">بلی</label>
                      </div>
                      <div class="w-1/2">
                        <input type="radio" v-model="financialPower" value="2" id="no2" name="financialYes" />
                        <label for="no2" id="nof" class="w-full text-center">خیر</label>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
              <template slot="append">
                <div id="offer" class="append-text append-currency">
                  <span v-if="(financialPower ==1)" class="bg-primary AFGLabel">AFN</span>
                  <span v-if="!(financialPower ==1)" class="AFGLabel disabled">AFN</span>
                </div>
              </template>
              <vs-input v-if="!(financialPower ==1)" disabled autocomplete="off" @input="formatToEnPrice($event, aForm, 'financial_power', visualFields)" name="financial_power" style="background-color:#EBECF0;border-radius: 0 !important;" v-model="visualFields.financial_power" />
              <vs-input v-if="(financialPower ==1)" autocomplete="off" @input="formatToEnPrice($event, aForm, 'financial_power', visualFields)" name="financial_power" style="border-radius: 0 !important;" v-model="visualFields.financial_power" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{ errors.first('step-1.financial_power') }}</span>
            <has-error :form="aForm" field="financial_power"></has-error>
          </div>
        </vs-col>
      </vs-row>
    </form>
    <br>
  </tab-content>
  <tab-content title="اکمالات / مصارف " class="mb-5" icon="feather icon-briefcase" :before-change="validateStep2">
    <form data-vv-scope="step-2">
      <ekmalat :items="aForm.item" :form="aForm" :listOfFields="dict" ref="ekmalat"></ekmalat>
      <vs-row vs-w="12" class="mb-base">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 ml-3 mr-3 mb-3">
            <label for=""><small>متفرقه</small></label>
            <vx-input-group class="">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>AFN</span>
                </div>
              </template>
              <vs-input autocomplete="off" v-model="visualFields.others" v-validate="'required'" name="others" @input="formatToEnPrice($event, aForm, 'others', visualFields)" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{ errors.first('step-2.others') }}</span>
            <has-error :form="aForm" field="others"></has-error>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 ml-3 mr-3 mb-3">
            <label for=""><small>نرخ دهی</small></label>
            <vx-input-group class="">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>AFN</span>
                </div>
              </template>
              <vs-input autocomplete="off" :value="visualFields.total_price" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{ errors.first('step-2.total_price') }}</span>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 ml-3 mr-3 mb-3">
            <label for=""><small>تخفیف</small></label>
            <vx-input-group class="">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>AFN</span>
                </div>
              </template>
              <vs-input autocomplete="off" type="number" v-model="aForm.discount" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{ errors.first('step-2.discount') }}</span>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
          <div class="w-full pt-2 ml-3 mr-3 mb-3">
            <label for=""><small>ارزش قرارداد</small></label>
            <vx-input-group class="">
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>AFN</span>
                </div>
              </template>
              <vs-input autocomplete="off" @input="formatToEnPrice($event, aForm, 'pr_worth', visualFields)" v-model="visualFields.pr_worth" v-validate="'required'" name="pr_worth" />
            </vx-input-group>
            <span class="absolute text-danger alerttext">{{ errors.first('step-2.pr_worth') }}</span>
            <has-error :form="aForm" field="pr_worth"></has-error>
          </div>
        </vs-col>

      </vs-row>
      <vs-row vs-w="12" class="mb-base">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" vs-xs="12">
          <div class="w-full pt-5 ml-3 mr-3 mb-3">
            <div class="w-full mt-5">
              <vs-checkbox color="primary" size="large" v-model="aForm.receive_office">رسانده اداره</vs-checkbox>
            </div>
          </div>
          <div class="w-full pt-5 ml-3 mr-3 mb-3">
            <div class="w-full mt-5">
              <vs-checkbox color="primary" size="large" v-model="aForm.bank_distribute">توزیع تانک</vs-checkbox>
            </div>
          </div>
        </vs-col>
      </vs-row>
    </form>
  </tab-content>
  <tab-content title="بررسی و مرور" class="mb-5" icon="feather icon-eye">
    <vs-row vs-w="12" class="information-overview" style="background-color: #f3f5f7; border-color: #42b983; padding: 1rem 0;border-right-width:0.6rem;border-right-style: solid;margin: 1rem 0">
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
          <small class="mb-5" v-text="aForm.serial_no" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            تاریخ نشر اعلان :
          </strong>
          <small class="mb-5" v-text="aForm.publish_date" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            آدرس نشراعلان:
          </strong>
          <small class="mb-5" v-text="aForm.publish_address" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-1">
          <strong class="mr-4">
            نهاد تطبیق کننده:
          </strong>
          <small class="mb-5" v-text="aForm.client_id.name" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            عنوان قرارداد:
          </strong>
          <small class="mb-5" v-text="aForm.title" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            شماره شناسایی قرارداد :
          </strong>
          <small class="mb-5" v-text="aForm.reference_no" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            تاریخ ختم پیشنهادات:
          </strong>
          <small class="mb-5" v-text="aForm.submission_date" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            تاریخ آفرگشایی:
          </strong>
          <small class="mb-5" v-text="aForm.bidding_date" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            آدرس آفرگشایی:
          </strong>
          <small class="mb-5" v-text="aForm.bidding_address" vs-justify="right" vs-align="right"></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            تضمین آفر:
          </strong>
          <span class="mb-5" vs-justify="right" vs-align="right">{{ visualFields.offer_guarantee }}</span>
          <small style="color:#42b983;"><b>افغانی </b></small>
        </h6>
      </vs-col>
    </vs-row>
    <br>
    <vs-row vs-w="12" style="background-color: #f3f5f7; border-color: #42b983; padding: 1rem 0;border-right-width:0.6rem;border-right-style: solid;margin: 1rem 0">
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="12" vs-sm="12" vs-xs="12">
        <h4>&nbsp;مرور بخش اکمالات /اقلام&nbsp;</h4>
      </vs-col>
    </vs-row>
    <vs-table :data="aForm.item">
      <template slot="thead" style="background-color: #f3f5f7">
        <vs-th>جنس / محصول</vs-th>
        <vs-th>مقدار</vs-th>
        <vs-th>قیمت فی واحد</vs-th>
        <vs-th>قیمت مجموعی</vs-th>
      </template>
      <template slot-scope="{data}">
        <vs-tr :key="i" v-for="(tr, i) in data">
          <vs-td :data="tr.item_id">
            {{ findItem(tr.item_id.id) }}
          </vs-td>
          <vs-td :data="tr.ammount">
            {{tr.ammount}}
          </vs-td>
          <!-- <vs-td :data="tr.item_id.uom_id">
            {{ findUom(tr.item_id.uom_id) }}
          </vs-td> -->
          <vs-td :data="tr.unit_price">
            {{tr.unit_price | NumThreeDigit}} <small style="color:#42b983;"><b>افغانی </b></small>
          </vs-td>
          <vs-td :data="tr.total_price">
            {{tr.total_price | NumThreeDigit}} <small style="color:#42b983;"><b>افغانی </b></small>
          </vs-td>
        </vs-tr>
      </template>
    </vs-table>
    <br>
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
          <small class="mb-5" v-text="aForm.deposit" vs-justify="right" vs-align="right"></small>
          <small style="color:#42b983;"><b>% </b></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            مالیات:
          </strong>
          <small class="mb-5" v-text="aForm.tax" vs-justify="right" vs-align="right"></small>
          <small style="color:#42b983;"><b>% </b></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            متفرقه:
          </strong>
          <span class="mb-5" vs-justify="right" vs-align="right">{{ visualFields.others }}</span>
          <small style="color:#42b983;"><b>افغانی </b></small>
        </h6>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            ارزش قرارداد:
          </strong>
          <span class="mb-5" vs-justify="right" vs-align="right">{{ visualFields.pr_worth }}</span>
          <small style="color:#42b983;"><b>افغانی </b></small>
        </h6>
      </vs-col>
      <!-- <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            انتقالات:
          </strong>
          <span class="mb-5" vs-justify="right" vs-align="right">{{ visualFields.transit }}</span>
          <small style="color:#42b983;"><b>افغانی </b></small>
        </h6>
      </vs-col> -->
      <vs-col vs-type="flex" :data="total_cost" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
        <h6 class="mb-5 mt-3 ml-2">
          <strong class="mr-4">
            نرخ دهی:
          </strong>
          <span class="mb-5" vs-justify="right" vs-align="right">{{ visualFields.total_price }}</span>
          <small style="color:#42b983;"><b>افغانی </b></small>
        </h6>
      </vs-col>
    </vs-row>
    <br>
    <vs-row vs-w="12" class="pt-6 pb-6">
      <vs-checkbox color="success" size="large" v-model="is_accepted">تایید مینمایم که معلومات فوق درست میباشد.</vs-checkbox>
    </vs-row>
  </tab-content>
  <vs-button v-if="currentIndex == 2" :disabled="!is_accepted" slot="finish">ثبت معلومات</vs-button>
</form-wizard>
</template>

<script>
import vSelect from 'vue-select'
import Ekmalat from "../../shared/Ekmalat"
import {
  FormWizard,
  TabContent
} from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
// For custom error message
import {
  Validator
} from 'vee-validate'
import ProSerialNumber from '../../shared/ProSerialNumber';

export default {
  components: {
    'v-select': vSelect,
    FormWizard,
    TabContent,
    Ekmalat,
    ProSerialNumber
  },
  props: ['clients'],
  data() {
    return {
      currentIndex: 0,
      user_permissions: localStorage.getItem('user_permissions'),
      proposalid: 0,
      offsetWidth: 100,
      financialPower: 1,
      samePro: 1,
      serial_no: 'انتخاب شرکت',
      is_accepted: false,
      currentSerialNo: 0,
      aForm: new Form({
        user_id: localStorage.getItem('id'),
        serial_no: '',
        client_id: '',
        company_id: null,
        offer_guarantee_type: 1,
        total_price: 0,
        discount: 0,
        same_pro: '',
        deal_value: '0',
        financial_power: '0',
        receive_office: '',
        bank_distribute: '',
        title: '',
        reference_no: '0',
        submission_date: this.momentj().format('jYYYY/jMM/jDD'),
        bidding_date: this.momentj().format('jYYYY/jMM/jDD'),
        bidding_address: '',
        publish_date: this.momentj().format('jYYYY/jMM/jDD'),
        publish_address: '',
        status: '1',
        offer_guarantee: '0',
        item: [{
          item_id: "",
          unit_id: "",
          operation_id: null,
          equivalent: "0",
          ammount: "0",
          unit_price: "0",
          total_price: "0",
          density: 1,
        }],
        deposit: '0',
        tax: '0',
        others: '0',
        pr_worth: '0',
        transit: '0',
      }),
      visualFields: {
        others: 0,
        pr_worth: 0,
        transit: 0,
        offer_guarantee: 0,
        total_price: 0,
        discount: 0,
        same_pro: '',
        deal_value: 0,
        financial_power: 0,
      },
      items: [],
      mesure_unit: [],
      companies: [],
      // Form field translations
      dict: {
        custom: {
          company_id: {
            required: 'سریال نمبر الزامی میباشد.',
            number: 'سریال نمبر باید نمبر باشد.'
          },
          // serial_no: {
          //   required: 'سریال نمبر الزامی میباشد.',
          //   number: 'سریال نمبر باید نمبر باشد.'
          // },
          publish_date: {
            required: 'تاریخ نشر اعلان را انتخاب کنید.'
          },
          publish_address: {
            required: 'آدرس نشر اعلان الزامی است.',
            min: 'آدرس نشر اعلان باید بیشتر از 3 حرف باشد.',
          },
          client_id: {
            required: 'نهاد را انتخاب کنید.'
          },
          title: {
            required: 'عنوان اعلان الزامی است.',
            min: 'عنوان اعلان باید بیشتر از 3 حرف باشد.',
          },
          reference_no: {
            required: 'شماره شناسایی اعلان ضروری است.',
            min: 'شماره شناسایی اعلان باید بیشتر از 3 حرف باشد.',
          },
          submission_date: {
            required: 'تاریخ ختم پیشنهادات الزامی است.'
          },
          bidding_date: {
            required: 'تاریخ آفرگشایی الزامی است.'
          },
          bidding_address: {
            required: 'آدرس آفرگشایی الزامی است.',
            min: 'آدرس آفرگشایی اعلان باید بیشتر از 3 حرف باشد .',
          },
          offer_guarantee: {
            required: 'تضمین آفر الزامی است'
          },
          deposit: {
            required: 'فیصدی تامینات را وارد کنید.',
          },
          tax: {
            required: 'فیصدی مالیه را وارد کنید',
          },
          others: {
            required: 'قیمت متفرقه بالای اعلان را وارد کنید.',
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
    }
  },
  created() {
    // register custom messages
    Validator.localize('en', this.dict)
    this.getAllItems();
    this.getAllUnites();

  },
  methods: {
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

    reloadData() {
      this.getNextSerialNo();
      this.getAllItems();
      this.$refs.wizard.reset();
      this.$Progress.set(100)
    },
    // for getting the next serian number
    getNextSerialNo(sign = null) {
      this.$Progress.start()
      this.axios.get(`/api/serial-num?type=prop-${sign}`)
        .then((response) => {
          this.currentSerialNo = response.data;
          this.aForm.serial_no = this.currentSerialNo;
          this.aForm.client_id = this.clients.find(e => !!e);
        })
    },

    // for items to be bought
    getAllItems() {
      this.$Progress.start()
      this.axios.get('/api/items')
        .then((response) => {
          this.items = response.data;
          this.$Progress.set(100)
        })
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
    formSubmitted() {
      if (this.is_accepted) {
        swal.fire({
          title: 'آیا مطمئن هستید؟',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: 'rgb(54 34 119)',
          cancelButtonColor: 'rgb(229 83 85)',
          confirmButtonText: '<span>بله، ذخیره شود!</span>',
          cancelButtonText: '<span>خیر، لغو شود!</span>'
        }).then((result) => {
          if (result.isConfirmed) {
            this.$Progress.start()
            this.aForm.post('/api/proposal')
              .then(({
                data
              }) => {
                this.reloadData();
                this.$vs.notify({
                  title: 'موفقیت!',
                  text: 'پروپوزل موفقانه ثبت سیستم گردید',
                  color: 'success',
                  iconPack: 'feather',
                  icon: 'icon-check',
                  position: 'top-right'
                })
                this.$router.push(`/proposal/${data.id}`).catch(() => {})
              }).catch((errors) => {
                this.$Progress.set(100)
                this.$vs.notify({
                  title: 'ناموفق!',
                  text: 'لطفاً معلومات پروپوزل را چک کنید و دوباره امتحان کنید!',
                  color: 'danger',
                  iconPack: 'feather',
                  icon: 'icon-alert-triangle',
                  position: 'top-right'
                })
              });
          }
        })
      } else {
        swal.fire({
          title: 'عدم تاییدی!',
          text: 'لطفاً از صحت معلومات تایید کنید، ویا معلومات را اصلاح کنید',
          icon: 'error',
        })
      }
    },

    companySelected(data) {
      this.aForm.company_id = data;
      this.getNextSerialNo(data.sign);

    }
  },
  // End Of methods
  computed: {
    // To calculate the total price for :the proposal
    total_cost: function () {
      let others = (this.aForm.others) ? parseInt(this.aForm.others) : 0;
      let transit = (this.aForm.transit) ? parseInt(this.aForm.transit) : 0;
      let pr_worth = (this.aForm.pr_worth) ? parseInt(this.aForm.pr_worth) : 0;

      let total_items = 0;
      this.aForm.item.filter(function (item) {
        // 
        if (item && item.total_price) {
          total_items += parseInt(item.total_price);
        }
      })
      this.aForm.total_price = total_items;
      this.visualFields.total_price = this.formatToEnPriceSimple(this.aForm.total_price);
      let discount = (this.aForm.discount) ? parseInt(this.aForm.discount) : 0;
      this.aForm.pr_worth = this.aForm.total_price - discount;
      this.visualFields.pr_worth = this.formatToEnPriceSimple(this.aForm.pr_worth);
      return total_items;
    },
  },
}
</script>

<style scoped>
.vs-icon {
  color: inherit;
  text-align: center;
  font-size: 2rem;
}

#offer {
  padding-right: 0rem;
  padding-left: 0rem;

}

#cache {
  border-bottom-right-radius: 0px !important;
  border-top-right-radius: 0px !important;
}

#nof {
  border-bottom-right-radius: 0px !important;
  border-top-right-radius: 0px !important;
}

.AFGLabel {
  height: 39px !important;
  padding: 10px !important;
  color: white !important;
  float: left !important;
  border-bottom-right-radius: 5px !important;
  border-top-right-radius: 5px !important;
}

.disabled {
  height: 33px;
  padding: 8px;
  background-color: #F8858B;
  border-bottom-right-radius: 5px !important;
  border-top-right-radius: 5px !important;
  color: white !important;
}

.radio-group input[type="radio"]+label {
  color: gray;
}

.radio-group input[type=radio]:checked+label {
  color: white;
}

body {
  color: #626262 !important;
}
</style>
