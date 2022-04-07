<template>
<form-wizard color="rgba(var(--vs-primary), 1)" :start-index.sync="pStepForm.step" :title="null" :hideButtons="true" :subtitle="null" ref="wizarde" @on-complete="$emit('closesteps')">
  <tab-content v-if="proposal" title="ثبت اطلاعات" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>بررسی بخش ثبت اطلاعات</vs-divider>
      </vs-row>
      <vs-row vs-w="12">
        <vs-col class="pr-5" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="vx-row">
            <vs-col vs-lg="3" vs-sm="4" vs-xs="12">
              <div class="img-container">
                <img :src="'/images/img/clients/' + proposal.pro_data.client.logo
               " style="height: 80px; margin: -1px 10px" class="product-img" @click.stop="showClientData(tr.id)" />
              </div>
            </vs-col>
            <vs-col vs-lg="9" class="pl-3" vs-align="right" vs-sm="8" vs-xs="12">
              <div class="vx-row w-full">
                <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                  <p clas="w-full" v-if="proposal.pro_data">
                    <strong>نام نهاد: </strong><span v-text="proposal.pro_data.client.name"></span>
                  </p>
                </vs-col>
              </div>
              <div class="vx-row">
                <vs-col class="mb-1" vs-justify="righ" vs-align="right" vs-w="12">
                  <p class="w-full" v-if="proposal.pro_data">
                    <strong>شماره قرارداد: </strong><span v-text="proposal.pro_data.reference_no"></span>
                  </p>
                </vs-col>
              </div>
              <div class="vx-row">
                <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                  <p class="w-full pr-5" v-if="proposal.pro_data">
                    <strong>عنوان قرارداد: </strong><span v-text="proposal.pro_data.title"></span>
                  </p>
                </vs-col>
              </div>
            </vs-col>
          </div>
        </vs-col>
        <vs-col class="pl-5" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="vx-row w-full">
            <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
              <p clas="w-full" v-if="proposal">
                <strong class="mr-4">تاریخ نشراعلان: </strong><span v-text="proposal.publish_date"></span>
              </p>
            </vs-col>
          </div>
          <div class="vx-row">
            <vs-col class="mb-1" vs-justify="righ" vs-align="right" vs-w="12">
              <p class="w-full" v-if="proposal">
                <strong class="mr-4">تاریخ ختم پیشنهادات: </strong><span v-text="proposal.submission_date"></span>
              </p>
            </vs-col>
          </div>
          <div class="vx-row">
            <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
              <p class="w-full pr-5" v-if="proposal">
                <strong class="mr-4">تاریخ داوطلبی: </strong><span v-text="proposal.bidding_date"></span>
              </p>
            </vs-col>
          </div>
          <div class="vx-row">
            <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
              <p class="w-full pr-5" v-if="proposal">
                <strong class="mr-4">مقدار تضمین: </strong><span v-text="proposal.offer_guarantee"></span><small style="color: #42b983"><b> افغانی </b></small>
              </p>
            </vs-col>
          </div>
        </vs-col>
        <!-- <div class="flex justify-between float-right">
              <vs-button size="small" color="success" icon="save" type="border" @click.prevent="submitForm" class="mb-2 ml-2 mr-2">ثبت</vs-button>
            </div>-->
      </vs-row>
      <vs-divider>بخش اکمالات</vs-divider>
      <vs-table class="responsive" :data="proposal.pro_items">
        <template slot="thead">
          <vs-th>جنس / محصول</vs-th>
          <vs-th>مقدار</vs-th>
          <vs-th>عملیه</vs-th>
          <vs-th>قیمت فی واحد</vs-th>
          <vs-th>قیمت مجموعی</vs-th>
        </template>
        <template slot-scope="{ data }">
          <vs-tr v-for="(tr, i) in data" :key="i">
            <vs-td :data="tr.item_id">
              <p>{{ tr.item_id.name }}</p>
            </vs-td>
            <vs-td :data="tr.equivalent">
              {{ tr.equivalent }}
              {{ tr.item_id.uom_equiv_id.title }}
            </vs-td>
            <vs-td :data="tr.operation_id">
              <p>{{ tr.operation_id.formula }}</p>
            </vs-td>
            <vs-td :data="tr.unit_price">
              {{ tr.unit_price }}
              <small style="color: #42b983"><b>افغانی </b></small>
            </vs-td>
            <vs-td :data="tr.total_price">
              {{ tr.total_price }}
              <small style="color: #42b983"><b>افغانی </b></small>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
    </vs-row>
  </tab-content>
  <tab-content v-if="proposal" title="ارسال درخواستی" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>بررسی بخش ارسال درخواستی</vs-divider>
      </vs-row>
      <vs-row vs-w="12">
        <vs-col class="pr-5" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="vx-row">
            <vs-col vs-lg="3" vs-sm="4" vs-xs="12">
              <div class="img-container">
                <img :src="
                                            '/images/img/clients/' +
                                            proposal.pro_data.client.logo
                                        " style="height: 80px; margin: -1px 10px" class="product-img" @click.stop="showClientData(tr.id)" />
              </div>
            </vs-col>
            <vs-col vs-lg="9" class="pl-3" vs-align="right" vs-sm="8" vs-xs="12">
              <div class="vx-row w-full">
                <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                  <p clas="w-full" v-if="proposal.pro_data">
                    <strong>نام نهاد: </strong><span v-text="
                                                    proposal.pro_data.client
                                                        .name
                                                "></span>
                  </p>
                </vs-col>
              </div>
              <div class="vx-row">
                <vs-col class="mb-1" vs-justify="righ" vs-align="right" vs-w="12">
                  <p class="w-full" v-if="proposal.pro_data">
                    <strong>شماره قرارداد: </strong><span v-text="
                                                    proposal.pro_data
                                                        .reference_no
                                                "></span>
                  </p>
                </vs-col>
              </div>
              <div class="vx-row">
                <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                  <p class="w-full pr-5" v-if="proposal.pro_data">
                    <strong>عنوان قرارداد: </strong><span v-text="proposal.pro_data.title"></span>
                  </p>
                </vs-col>
              </div>
            </vs-col>
          </div>
        </vs-col>
        <vs-col class="pl-5" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="vx-row">
            <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
              <p clas="w-full" v-if="proposal.pro_data">
                <strong class="mr-4"> شماره تماس: </strong>{{ proposal.pro_data.client.phone }}
              </p>
            </vs-col>
          </div>
          <div class="vx-row">
            <vs-col class="mb-1" vs-justify="righ" vs-align="right" vs-w="12">
              <p class="w-full" v-if="proposal.pro_data">
                <strong class="mr-4"> آدرس: </strong><span v-text="
                                            proposal.pro_data.client.address
                                        "></span>
              </p>
            </vs-col>
          </div>
          <div class="vx-row">
            <vs-col vs-type="flex" class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
              <!--<div class="mt-2 mr-5">
                <vs-input label=" اسم شخص مسول را وارد نمایید" v-model="pStepForm.res_person" />
              </div>
              -->
            </vs-col>
          </div>
        </vs-col>
      </vs-row>
      <vs-divider />
      <vs-row vs-w="12">
        <div class="w-full">
          <quill-editor v-model="pStepForm.res_person" :options="editorOption">
            <div id="toolbar" slot="toolbar">
              <button class="ql-bold">Bold</button>
              <button class="ql-italic">Italic</button>
              <button class="ql-underline">Underline</button>

              <select class="ql-size">
                <option value="small"></option>
                <option selected></option>
                <option value="large"></option>
                <option value="huge"></option>
              </select>

              <!-- <select class="ql-font">
                <option selected="selected"></option>
                <option value="serif"></option>
                <option value="monospace"></option>
              </select> -->

              <!-- <select class="ql-align">
                <option selected="justify"></option>
                <option value="center"></option>
                <option value="right"></option>
              </select> -->

              <select class="ql-header">
                <option selected="1"></option>
                <option value="2"></option>
                <option value="3"></option>
                <option value="4"></option>
                <option value="5"></option>
                <option value="6"></option>
                <option value="false"></option>
              </select>

              <button class="ql-direction" value="rtl"></button>

              <button class="ql-list" value="ordered"></button>

              <button class="ql-list" value="bullet"></button>

              <button class="ql-indent" value="+1"></button>

              <button class="ql-indent" value="-1"></button>
            </div>
          </quill-editor>
        </div>
      </vs-row>
    </vs-row>
  </tab-content>
  <tab-content v-if="proposal" title="دریافت شرطنامه/آفر" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>بررسی بخش دریافت شرطنامه </vs-divider>
      </vs-row>
      <vs-row vs-w="12">
        <vs-col class="pr-5" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="vx-row">
            <vs-col vs-lg="3" vs-sm="4" vs-xs="12">
              <div class="img-container">
                <img :src="'/images/img/clients/' + proposal.pro_data.client.logo" style="height: 80px; margin: -1px 10px" class="product-img" @click.stop="showClientData(tr.id)" />
              </div>
            </vs-col>
            <vs-col vs-lg="9" class="pl-3" vs-align="right" vs-sm="8" vs-xs="12">
              <div class="vx-row w-full">
                <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                  <p clas="w-full" v-if="proposal.pro_data">
                    <strong>نام نهاد: </strong><span v-text="proposal.pro_data.client.name"></span>
                  </p>
                </vs-col>
              </div>
              <div class="vx-row">
                <vs-col class="mb-1" vs-justify="righ" vs-align="right" vs-w="12">
                  <p class="w-full" v-if="proposal.pro_data">
                    <strong>شماره قرارداد: </strong><span v-text="proposal.pro_data.reference_no"></span>
                  </p>
                </vs-col>
              </div>
              <div class="vx-row">
                <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                  <p class="w-full pr-5" v-if="proposal.pro_data">
                    <strong>عنوان قرارداد: </strong><span v-text="proposal.pro_data.title"></span>
                  </p>
                </vs-col>
              </div>
            </vs-col>
          </div>
        </vs-col>
        <vs-col class="pl-5" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="vx-row pl-3">
            <vs-col class="mb-3 ml-2" vs-justify="right" vs-align="right" vs-w="12">
              <p clas="mb-1" v-if="proposal.pro_data">
                <strong class="mr-4">
                  تاریخ دریافت شرطنامه: </strong><span></span>
              </p>
            </vs-col>
            <vs-col class="mb-2" vs-justify="right" vs-align="right" vs-w="12">
              <vs-checkbox color="success" size="large" v-model="pStepForm.is_recieved_cont"><strong>
                  شرطنامه دریافت گردید ؟
                </strong></vs-checkbox>
              <!--<vs-button size="small" color="success" icon="save" type="border" @click.prevent="submitForm" class="mb-2 mt-2 ml-1"> ویرایش معلومات </vs-button>-->
            </vs-col>
          </div>
        </vs-col>
      </vs-row>
      <vs-divider></vs-divider>
    </vs-row>
  </tab-content>
  <tab-content v-if="proposal" title="اشتراک" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>بررسی بخش اشتراک</vs-divider>
      </vs-row>
      <vs-row vs-w="12">
        <ekmalat :key="ekmalatKey" :items="aForm.item" :form="aForm" :listOfFields="dict" ref="ekmalat"></ekmalat>
      </vs-row>
      <vs-row vs-w="12">
        <vs-col class="pl-5" vs-lg="9" vs-sm="9" vs-xs="12">
        </vs-col>
        <vs-col class="pl-5" vs-lg="3" vs-sm="3" vs-xs="12">
          <vs-checkbox color="success" class="float-left" size="large" v-model="pStepForm.is_participated"><strong> اشتراک میشود ؟ </strong></vs-checkbox>
        </vs-col>
      </vs-row>
    </vs-row>
  </tab-content>
  <tab-content v-if="proposal" title="مرحله داوطلبی" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>بررسی بخش داوطلبی</vs-divider>
      </vs-row>
      <vs-row vs-w="12">
        <vs-col class="pr-5" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="vx-row">
            <vs-col vs-lg="3" vs-sm="4" vs-xs="12">
              <div class="img-container">
                <img :src="'/images/img/clients/' + proposal.pro_data.client.logo " style="height: 80px; margin: -1px 10px" class="product-img" @click.stop="showClientData(tr.id)" />
              </div>
            </vs-col>
            <vs-col vs-lg="9" class="pl-3" vs-align="right" vs-sm="8" vs-xs="12">
              <div class="vx-row w-full">
                <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                  <p clas="w-full" v-if="proposal.pro_data">
                    <strong>نام نهاد: </strong><span v-text="proposal.pro_data.client.name"></span>
                  </p>
                </vs-col>
              </div>
              <div class="vx-row">
                <vs-col class="mb-1" vs-justify="righ" vs-align="right" vs-w="12">
                  <p class="w-full" v-if="proposal.pro_data">
                    <strong>شماره قرارداد: </strong><span v-text="proposal.pro_data.reference_no"></span>
                  </p>
                </vs-col>
              </div>
              <div class="vx-row">
                <vs-col class="mb-1" vs-justify="right" vs-align="right" vs-w="12">
                  <p class="w-full pr-5" v-if="proposal.pro_data">
                    <strong>عنوان قرارداد: </strong><span v-text="proposal.pro_data.title"></span>
                  </p>
                </vs-col>
              </div>
            </vs-col>
          </div>
        </vs-col>
        <vs-col class="pl-5" vs-lg="6" vs-sm="6" vs-xs="12">
          <div class="vx-row pl-3">
            <vs-col class="mb-3" vs-justify="right" vs-align="right" vs-w="12">
              <p clas="mb-1" v-if="proposal">
                <strong class="mr-4">
                  تاریخ داوطلبی: </strong><span v-text="proposal.bidding_date"></span>
              </p>
            </vs-col>
            <vs-col class="mb-2" vs-justify="right" vs-align="right" vs-w="12">
              <p class="w-full" v-if="proposal">
                <strong class="mr-4"> آدرس داوطلبی: </strong><span v-text="proposal.bidding_address"></span>
              </p>
            </vs-col>
            <vs-col class="mb-2" vs-justify="right" vs-align="right" vs-w="12">
              <p class="w-full" v-if="proposal">
                <strong class="mr-4"> مقدارتضمین: </strong><span v-text="proposal.offer_guarantee"></span><b> افغانی </b>__<b style="color: green">بانکی</b>
              </p>
            </vs-col>
          </div>
        </vs-col>
        <!--<div class="flex justify-between float-right">
             <vs-button size="small" color="success" icon="save" type="border" @click.prevent="submitForm" class="mb-2 ml-2 mr-2">ثبت</vs-button>
            </div>-->
      </vs-row>
      <vs-divider>اطلاعات اشتراک کننده گان داوطلبی</vs-divider>
      <vs-row vs-w="12">
        <form>
          <div v-for="i in participators" :key="i.id" class="mb-6">
            <vs-row vs-w="12">
              <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="3" vs-xs="6">
                <div class="w-full pt-2 ml-1 mr-1 mb-3">
                  <vs-input autocomplete="off" label="نام شرکت" name="co_name" v-model="i.name" class="w-full" />
                </div>
              </vs-col>
              <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="3" vs-xs="6">
                <div class="w-full pt-2 ml-1 mr-1 mb-3">
                  <vs-input autocomplete="off" v-model="i.same_dificult" label="مشابه / مشکلات" name="same_dificult" class="w-full" />
                </div>
              </vs-col>
              <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="6">
                <div class="w-full pt-2 ml-1 mr-1 mb-3">
                  <vs-input autocomplete="off" v-model="i.statement" label="استیتمنت" name="statement" class="w-full" />
                </div>
              </vs-col>
              <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="3" vs-xs="6">
                <div class="w-full pt-2 ml-1 mr-1 mb-3">
                  <vs-input autocomplete="off" v-model="i.feyat" label="فیات" name="feyat" class="w-full" />
                </div>
              </vs-col>
              <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="3" vs-xs="6">
                <div class="w-full pt-2 ml-1 mr-1 mb-3">
                  <vs-input autocomplete="off" v-model="i.offer_price" label="قیمت آفر" name="offer_price" class="w-full" />
                </div>
              </vs-col>
              <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="3" vs-xs="6">
                <div class="w-full pt-2 ml-1 mr-1 mb-3">
                  <vs-input autocomplete="off" v-model="i.discount" label="تخفیف" name="discount" class="w-full" />
                </div>
              </vs-col>
              <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="3" vs-xs="6">
                <div class="w-full pt-2 ml-1 mr-1 mb-3">
                  <vs-input autocomplete="off" v-model="i.final_price" label="قیمت نهایی" name="final_price" class="w-full" />
                </div>
              </vs-col>
            </vs-row>
          </div>
        </form>
        <vs-row vs-w="12">
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="4" vs-xs="12" class="pt-2 mb-2 ml-3 mr-3">
            <vs-button type="border" @click.stop="addRow" color="success" icon="add"></vs-button>
            &nbsp;&nbsp;
            <vs-button type="border" id="delete-btn" @click.stop="removeRow" color="danger" icon="delete" :disabled="participators.length <= 1"></vs-button>
          </vs-col>
        </vs-row>
      </vs-row>
      <vs-divider></vs-divider>
    </vs-row>
  </tab-content>
  <tab-content v-if="proposal" title="نتیجه قرارداد" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>بررسی بخش نتیجه قرارداد</vs-divider>
      </vs-row>
      <vs-row vs-w="12" vs-type="flex" vs-justify="center">
        <div class="radio-group1 w-full">
          <vs-col class="pr-5" vs-lg="6" vs-sm="6" vs-xs="12">
            <vs-row vs-w="12" vs-type="flex" vs-justify="center" :class="{'activated': pStepForm.prop_recieved_or_allow == 1}">
              <input type="radio" v-model="pStepForm.prop_recieved_or_allow" value="1" id="struct" name="prop_recieved_or_allow" />
              <div class="meter-btn">
                <span style="width: 100%" @click="changePropStepStatus5(pStepForm.prop_recieved_or_allow, false)" class="cursor-pointer">
                  <label for="struct" id="recieve" @click="changePropStepStatus5(pStepForm.prop_recieved_or_allow, false)" style="font-size: 35px" class="w-full text-center p-24">دریافت قرارداد</label>
                </span>
              </div>
            </vs-row>
          </vs-col>
          <vs-col class="pr-5" vs-lg="6" vs-sm="6" vs-xs="12">
            <vs-row vs-w="12" vs-type="flex" vs-justify="center">
              <input type="radio" v-model="pStepForm.prop_recieved_or_allow" value="2" id="specific" name="prop_recieved_or_allow" />
              <label for="specific" id="allowto" @click="changePropStepStatus5(pStepForm.prop_recieved_or_allow)" style="font-size: 35px" class="w-full text-center p-12">واگذاری قرارداد</label>
              <vs-divider><span for="winner">برنده قرار داد</span></vs-divider>
              <vs-input id="winner" autocomplete="off" v-model="pStepForm.winner" name="winner" class="w-full" />
            </vs-row>
          </vs-col>
        </div>
      </vs-row>
      <vs-divider></vs-divider>
    </vs-row>
  </tab-content>
  <div :key="com_key" v-bind:class="{ 'float-right': pStepForm.step == 0 }" class="flex space-between mt-2" v-if="$refs.wizarde">
    <vs-button v-if="pStepForm.step > 0" @click="$refs.wizarde.prevTab">قبلی</vs-button>
    <div class="flex">
      <vs-button v-if="!$refs.wizarde.isLastStep && pStepForm.step == 1" :disabled="!pStepForm.res_person" style="float: left; margin-top: 2px" color="warning" @click="cprint">چاپ عریضه</vs-button>
      <vs-button v-if="!$refs.wizarde.isLastStep && pStepForm.step == 1" color="success" style="float: left; margin-top: 2px" class="mx-2" @click="changePropStepStatus1">ثبت</vs-button>
      <vs-button v-if="!$refs.wizarde.isLastStep && pStepForm.step == 2" color="success" style="float: left; margin-top: 2px" class="mx-2" @click.prevent="changePropStepStatus2">ثبت</vs-button>
      <vs-button v-if="!$refs.wizarde.isLastStep && pStepForm.step == 3" style="float: left; margin-top: 2px" class="mx-2" @click.stop="updateProposalItems" color="success">ثبت</vs-button>
      <vs-button v-if="!$refs.wizarde.isLastStep && pStepForm.step == 4" style="float: left; margin-top: 2px" class="mx-2" @click.stop="storeParticipators" color="success">ثبت</vs-button>
      <vs-button v-if="!$refs.wizarde.isLastStep" @click="$refs.wizarde.nextTab">بعدی</vs-button>
      <vs-button v-if="$refs.wizarde.isLastStep" @click="$emit('closesteps')">بستن صفحه</vs-button>
    </div>
  </div>
</form-wizard>
</template>

<script>
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";
import {
  quillEditor
} from "vue-quill-editor";

import Ekmalat from "../../shared/Ekmalat.vue";
import {
  FormWizard,
  TabContent
} from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
export default {
  props: ["proposal", "participators"],
  data() {
    return {
      com_key: 0,
      ekmalatKey: 0,
      user_permissions: localStorage.getItem("user_permissions"),
      editorOption: {
        modules: {
          toolbar: "#toolbar",
        },
        placeholder: "عریضه را بنویسید ...",
      },
      content: "",
      firstloadstep: 0,
      pStepForm: new Form({
        step: 0,
        proposal_id: "",
        res_person: "",
        is_recieved_cont: "",
        is_participated: "",
        prop_recieved_or_allow: 1,
        winner: "",
      }),
      aForm: new Form({
        user_id: localStorage.getItem("id"),
        serial_no: "",
        client_id: "",
        company_id: null,
        offer_guarantee_type: 1,
        total_price: 0,
        discount: 0,
        same_pro: "",
        deal_value: "0",
        financial_power: "0",
        receive_office: "",
        bank_distribute: "",
        title: "",
        reference_no: "0",
        submission_date: "",
        bidding_date: "",
        bidding_address: "",
        publish_date: "",
        publish_address: "",
        status: "1",
        offer_guarantee: "0",
        item: [{
          item_id: "",
          unit_id: "",
          operation_id: null,
          equivalent: "0",
          ammount: "0",
          unit_price: "0",
          total_price: "0",
          density: 1,
        }, ],
        deposit: "0",
        tax: "0",
        others: "0",
        pr_worth: "0",
        transit: "0",
      }),
      dict: {
        custom: {
          sf: {
            required: "سریال نمبر الزامی میباشد.",
            number: "سریال نمبر باید نمبر باشد.",
          },
        },
      },
    };
  },
  components: {
    FormWizard,
    TabContent,
    Ekmalat,
    quillEditor,
  },
  created() {},
  methods: {
    setItemsList(proposal) {
      return new Promise((resolve) => {
        let p = proposal;
        let pd = p.pro_data;
        (this.aForm.client_id = pd.client),
        (this.aForm.serial_no = p.serial_no),
        (this.aForm.company_id = pd.company_id),
        (this.aForm.offer_guarantee_type = p.offer_guarantee_type),
        (this.aForm.total_price = pd.total_price),
        (this.aForm.same_pro = p.same_pro),
        (this.aForm.deal_value = p.deal_value),
        (this.aForm.financial_power = p.financial_power),
        (this.aForm.receive_office = p.receive_office),
        (this.aForm.bank_distribute = p.bank_distribute),
        (this.aForm.title = pd.title),
        (this.aForm.reference_no = pd.reference_no),
        (this.aForm.submission_date = p.submission_date),
        (this.aForm.bidding_date = p.bidding_date),
        (this.aForm.bidding_address = p.bidding_address),
        (this.aForm.publish_date = p.publish_date),
        (this.aForm.publish_address = p.publish_address),
        (this.aForm.offer_guarantee = p.offer_guarantee),
        (this.aForm.discount = pd.discount),
        (this.aForm.deposit = pd.deposit),
        (this.aForm.tax = pd.tax),
        (this.aForm.others = pd.others),
        (this.aForm.pr_worth = pd.pr_worth),
        (this.aForm.transit = pd.transit),
        (this.visualFields = {
          others: this.formatToEnPriceSimple(pd["others"]),
          pr_worth: this.formatToEnPriceSimple(pd["pr_worth"]),
          transit: this.formatToEnPriceSimple(pd["transit"]),
          total_price: this.formatToEnPriceSimple(
            pd["total_price"]
          ),
          offer_guarantee: this.formatToEnPriceSimple(
            p["offer_guarantee"]
          ),
          same_pro: this.formatToEnPriceSimple(p["same_pro"]),
          deal_value: this.formatToEnPriceSimple(p["deal_value"]),
          financial_power: this.formatToEnPriceSimple(
            p["financial_power"]
          ),
        });
        if (p.pro_items.length) {
          this.$refs.ekmalat.resetArrays();

          for (let [key, data] of Object.entries(p.pro_items)) {
            this.aForm["item"][key] = data;
            this.$refs.ekmalat.addRow({
              key: key,
              data: data,
            });
            this.$refs.ekmalat.operationChange(
              this.aForm.item[key].operation_id,
              key
            );
            this.$refs.ekmalat.itemSelected(
              "",
              this.aForm.item[key].item_id.id,
              key,
              this.aForm.item[key].item_id.uom_id.acronym
            );
          }
        } else {
          this.aForm.item = [{
            item_id: "",
            unit_id: "",
            operation_id: null,
            equivalent: 1,
            ammount: 0,
            unit_price: 0,
            total_price: 0,
            density: "1.00",
          }, ];
        }
        setTimeout(() => {
          resolve("rejected");
        }, 500);
      });
    },
    updateProposalItems() {
      this.aForm
        .patch("/api/proposal-items/" + this.proposal.id)
        .then(({
          data
        }) => {
          // this.reloadData();
          this.$vs.notify({
            title: "موفقیت!",
            text: "پروپوزل موفقانه آپدیت شد",
            color: "success",
            iconPack: "feather",
            icon: "icon-check",
            position: "top-right",
          });
        })
        .catch((errors) => {
          this.$Progress.set(100);
          this.$vs.notify({
            title: "ناموفق!",
            text: "لطفاً معلومات پروپوزل را چک کنید و دوباره امتحان کنید!",
            color: "danger",
            iconPack: "feather",
            icon: "icon-alert-triangle",
            position: "top-right",
          });
        });
      this.changePropStepStatus3();
    },
    storeParticipators() {
      var valid = true;
      for (let [key, data] of Object.entries(this.participators)) {
        if (data.name == null) {
          this.$vs.notify({
            title: "ناموفق!",
            text: "لطفاً معلومات را چک کنید و دوباره امتحان کنید!",
            color: "danger",
            iconPack: "feather",
            icon: "icon-alert-triangle",
            position: "top-right",
          });
          valid = false;
        }
      }
      if (valid) {
        this.axios
          .post(
            "/api/participators/" + this.proposal.id,
            this.participators
          )
          .then((response) => {
            this.$vs.notify({
              title: "موفقیت!",
              text: "اطلاعات اشتراک کننده گان داوطلبی موفقانه ثبت شد.",
              color: "success",
              iconPack: "feather",
              icon: "icon-check",
              position: "top-right",
            });
            this.changePropStepStatus4();
          });
      }
    },
    changeItTo(proposalid, st) {
      this.$Progress.start();
      this.pStepForm
        .post("/api/proposlstchange/" + proposalid)
        .then((response) => {
          this.$Progress.set(100);
          if (this.firstloadstep == 1) {
            if (st == 7) {
              this.$vs.notify({
                title: "موفقیت!",
                text: "تمام مراحل اعلان ختم گردید",
                color: "success",
                iconPack: "feather",
                icon: "icon-check",
                position: "top-right",
              });
            } else {
              this.$vs.notify({
                title: "موفقیت!",
                text: " مرحله موفقانه به " + st + " تغیر یافت.",
                color: "success",
                iconPack: "feather",
                icon: "icon-check",
                position: "top-right",
              });
            }
          } else if (this.firstloadstep > 1) {
            this.firstloadstep = this.firstloadstep - 1;
          }
        })
        .catch(() => {
          document.getElementById("loading-bg").style.display =
            "none";
          this.$vs.notify({
            title: "ناموفق!",
            text: "لطفاً معلومات را چک کنید و دوباره امتحان کنید!",
            color: "danger",
            iconPack: "feather",
            icon: "icon-alert-triangle",
            position: "top-right",
          });
        });
    },
    changePropStepStatus() {
      this.changeItTo(this.proposal.id, this.pStepForm.step);
      return true;
    },
    changePropStepStatus1() {
      this.changeItTo(this.proposal.id, this.pStepForm.step);
      return true;
    },
    changePropStepStatus2(e) {
      if (e.target.checked) {
        this.changeItTo(this.proposal.id, this.pStepForm.step);
        return true;
      } else {
        this.changePropStepStatus1();
      }
    },
    changePropStepStatus3() {
      this.changeItTo(this.proposal.id, this.pStepForm.step);
      return true;
    },
    changePropStepStatus4() {
      this.changeItTo(this.proposal.id, this.pStepForm.step);
      return true;
    },
    changePropStepStatus5(value, check = true) {
      if (!this.pStepForm.winner && check) {
        swal.mixin({
          input: 'text',
          confirmButtonColor: "#362277",
          cancelButtonColor: "#e85454",
          showCancelButton: true,
        }).queue([{
          title: 'برنده قرارداد',
        }]).then((result) => {
          if (result.value) {
            const answers = result.value
            this.pStepForm.winner = answers[0];
            if(this.pStepForm.winner){
              this.pStepForm.prop_recieved_or_allow = value;
              this.changeItTo(this.proposal.id, this.pStepForm.step);
            }
            else{this.pStepForm.prop_recieved_or_allow = null}
          }
        }).catch(() => {
          this.pStepForm.prop_recieved_or_allow = null
        });

      } else {
        this.pStepForm.prop_recieved_or_allow = value;
        this.changeItTo(this.proposal.id, this.pStepForm.step);
      }
    },
    cprint() {
      let src = "/img/default/navelogo.png";
      var win = window.open();
      self.focus();
      win.document.open();
      win.document.write(`
      <html><head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/print.css" rel="stylesheet">

<style>
body {
  direction: rtl !important;
  font-size: 18px
}

.main-container {
  margin-top: 50px;
}
</style>
      </head>`);
      win.document.write(
        `<body onload="window.print()" onafterprint="window.close()">
            <div class="container main-container">
                ${this.pStepForm.res_person}
            </div>
           </body>
        </html>
      `
      );
      win.document.close();
    },
    addRow() {
      this.participators.push({
        proposal_id: this.pStepForm.proposal_id,
        name: "",
        same_dificult: "",
        statement: "",
        feyat: "",
        offer_price: "",
        discount: "",
        final_price: "",
      });
    },
    removeRow() {
      swal.fire({
        title: "آیا شما مطمئن هستید ؟",
        text: "شما قادر به برگردادن این عملیه پس از حذف نمی باشید !",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#362277",
        cancelButtonColor: "#e85454",
        confirmButtonText: "بلی مطمئن هستم",
        cancelButtonText: "خیر",
      }).then((result) => {
        if (result.value) {
          var deleted = this.participators.splice(
            this.participators.length - 1,
            1
          );
          this.axios
            .get("/api/participator/" + deleted[0].id)
            .then(() => {
              swal.fire(
                "حذف شد !",
                "موفقانه عملیه حذف انجام شد",
                "success"
              );
            })
            .catch(() => {
              swal.fire(
                "Failed!",
                "سیستم قادر به حذف نیست دوباره تلاش نماید.",
                "warning"
              );
            });
        }
      });
    },
    setWizardStep1(index = 1, proposalstepdata) {
      this.firstloadstep = parseInt(index);
      this.pStepForm.fill(proposalstepdata);
      this.$refs.wizarde.activateAll();
      this.$refs.wizarde.navigateToTab(index * 1);
      if (!this.participators.length) {
        this.addRow();
      }
    },
  },
  mounted() {},
};
</script>

<style>
.con-vs-popup .vs-popup {
    width: 80%;
}

[dir] .vue-form-wizard .wizard-tab-content {
    padding: 30px 20px 2px 10px !important;
}

.radio-group1 {
    display: inline-flex;
    overflow: visible;
}

.radio-group1 div:first-child label {
    overflow: hidden;
}

[dir="ltr"] .radio-group1 div:first-child label {
    border-bottom-left-radius: 5px;
    border-top-left-radius: 5px;
}

[dir="rtl"] .radio-group1 div:first-child label {
    border-bottom-right-radius: 5px;
    border-top-right-radius: 5px;
}

.radio-group1 div:last-child label {
    overflow: hidden;
}

[dir="ltr"] .radio-group1 div:last-child label {
    border-bottom-right-radius: 5px;
    border-top-right-radius: 5px;
}

[dir="rtl"] .radio-group1 div:last-child label {
    border-bottom-left-radius: 5px;
    border-top-left-radius: 5px;
}

.radio-group1 input[type="radio"] {
    position: absolute;
    visibility: hidden;
    display: none;
}

.radio-group1 label {
    display: inline-block;
    font-weight: bold;
    transition: 0.4s;
}

[dir] .radio-group1 label#recieve {
    cursor: pointer;
    padding: 30px 18px;
    border: solid 1px rgba(var(--vs-success), 1);
}

[dir] .radio-group1 label#allowto {
    cursor: pointer;
    padding: 30px 18px;
    border: solid 1px rgba(var(--vs-danger), 1);
}

.radio-group1 input[type="radio"]:checked + label {
    color: white;
}

[dir] .radio-group1 input[type="radio"]:checked + label#recieve {
    background: rgba(var(--vs-success), 1);
    box-shadow: 0 8px 25px -8px #e85454;
    border-color: rgba(var(--vs-success), 1);
}

[dir] .radio-group1 input[type="radio"]:checked + label#allowto {
    background: rgba(var(--vs-danger), 1);
    box-shadow: 0 8px 25px -8px #e85454;
    border-color: rgba(var(--vs-danger), 1);
}
</style>
