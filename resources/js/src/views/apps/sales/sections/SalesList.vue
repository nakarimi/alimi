<template>
<div id="data-list-list-view" class="data-list-container">
  <div v-if="!isloaded">
    <TableLoading></TableLoading>
  </div>
  <vs-table v-if="isloaded" ref="table" pagination :max-items="itemsPerPage" search :data="sales">
    <div slot="header" class="flex flex-wrap-reverse items-center flex-grow justify-between">
      <div class="flex flex-wrap-reverse items-center data-list-btn-container">
        <!-- ITEMS PER PAGE -->
        <vs-dropdown vs-trigger-click class="cursor-pointer mb-4 mr-4 items-per-page-handler float-right">
          <div class="p-2 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
            <!-- <span class="mr-2">{{ currentPage * itemsPerPage - (itemsPerPage - 1) }} - {{ sales.length - currentPage * itemsPerPage > 0 ? currentPage * itemsPerPage : sales.length }} of {{ queriedItems }}</span> -->
            <span class="mr-2">
              {{ currentPage * itemsPerPage - (itemsPerPage - 1) }} -
              {{
              sales.length - currentPage * itemsPerPage > 0
              ? currentPage * itemsPerPage
              : sales.length
              }}
              از {{ queriedItems }}
            </span>
            <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
          </div>
          <!-- <vs-button class="btn-drop" type="line" color="primary" icon-pack="feather" icon="icon-chevron-down"></vs-button> -->
          <vs-dropdown-menu>
            <vs-dropdown-item @click="itemsPerPage=4">
              <span>4</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage=10">
              <span>10</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage=15">
              <span>15</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage=20">
              <span>20</span>
            </vs-dropdown-item>
          </vs-dropdown-menu>
        </vs-dropdown>
      </div>
    </div>
    <template slot="thead">
      <vs-th>#</vs-th>
      <vs-th>سریال نمبر</vs-th>
      <vs-th>قیمت کلی</vs-th>
      <vs-th>قیمت خدمات</vs-th>
      <vs-th>نوع فروش</vs-th>
      <vs-th>منبع</vs-th>
      <vs-th v-if="can_do_this">تنظیمات</vs-th>
    </template>
    <template slot-scope="{data}">
      <tbody>
        <vs-tr :data="tr" :key="i" v-for="(tr, i) in data">
          <vs-td>
            <p @click.stop="viewData(tr)" class="cursor-pointer">{{i + 1 }}</p>
          </vs-td>
          <vs-td>
            <p @click.stop="viewData(tr)" class="cursor-pointer">
              <small class="mb-5">{{tr.type}}-</small>
              <small class="mb-5" v-if="(tr.sales && tr.sales.project) && tr.sales.project.pro_data">{{tr.sales.project.pro_data.reference_no}}-</small>
              <small class="mb-5" v-text="tr.serial_no"></small>
            </p>
          </vs-td>
          <vs-td>
            <p @click.stop="viewData(tr)" class="cursor-pointer">{{ tr.total | NumThreeDigit}} افغانی</p>
          </vs-td>
          <vs-td>
            <p @click.stop="viewData(tr)" class="cursor-pointer">{{ tr.service_cost | NumThreeDigit }} افغانی</p>
          </vs-td>
          <vs-td>
            <p @click.stop="viewData(tr)" class="cursor-pointer">{{ $t(tr.type) }}</p>
          </vs-td>
          <vs-td>
            <p @click.stop="viewData(tr)" class="cursor-pointer">{{ tr.source_type }}</p>
          </vs-td>
          <vs-td v-if="can_do_this">
            <feather-icon v-if="user_permissions.includes(`sale${tr.type}_steps`)" icon="CheckSquareIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current cursor-pointer" class="ml-2" @click.stop="showCheckModal(tr.sales_id)" />&nbsp;&nbsp;
            <feather-icon v-if="user_permissions.includes(`sale${tr.type}_view`)" icon="PrinterIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current cursor-pointer" class="ml-2" @click.stop="showPrintData(tr)" />&nbsp;&nbsp;
            <feather-icon v-if="user_permissions.includes(`sale${tr.type}_edit`)" icon="EditIcon" svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer" @click.stop="goToEdit(tr)" />
            <feather-icon v-if="user_permissions.includes(`sale${tr.type}_remove`)" icon="TrashIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current cursor-pointer" class="ml-2" @click.stop="deleteData(tr.sales_id)" />
            <feather-icon v-if="user_permissions.includes(`sale${tr.type}_view`)" icon="EyeIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current cursor-pointer" class="ml-2" @click.stop="viewData(tr)" />
          </vs-td>
        </vs-tr>
      </tbody>
    </template>
  </vs-table>
  <vs-popup class="holamundo" title="تنظیمات مربوط به فروشات" :active.sync="popupModalActive">
    <form-wizard :key="sale.id" v-if="sale.type" color="rgba(var(--vs-primary), 1)" :title="null" :subtitle="null" :start-index.sync="current_tab" ref="wizard" class="sales_step_wizard">
      <tab-content title="تنظیم اطلاعات فروش" class="mb-5">
        <vs-divider>اطلاعات فروش</vs-divider>
        <vs-row v-if="sale.type" vs-w="12">
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                سریال نمبر:
              </strong>
              <small class="mb-5">{{sale.type}}-</small>
              <small class="mb-5" v-if="sale.sales.project">{{sale.sales.project.pro_data.reference_no}}-</small>
              <small class="mb-5" v-text="sale.sales.serial_no" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نوعیت:
              </strong>
              <small class="mb-5" v-text="$t(sale.type)" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نوع منبع:
              </strong>
              <small class="mb-5" v-text="$t(sale.source_type)" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.source_id">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                منبع:
              </strong>
              <small class="mb-5" v-text="(sale.source_id) ? $t(sale.source_id.name) : ''" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                واحد پولی:
              </strong>
              <small class="mb-5" v-text="sale.currency_id.sign_fa" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>

          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                حساب بانک:
              </strong>
              <small class="mb-5" v-text="(sale.bank_account) ? sale.bank_account.name : ''" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                تاریخ:
              </strong>
              <small class="mb-5" v-text="sale.datatime" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.project">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                پروژه:
              </strong>
              <small class="mb-5" v-text="sale.sales.project.pro_data.title" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.driver_name">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نام دریور:
              </strong>
              <small class="mb-5" v-text="sale.sales.driver_name" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.plate_no">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نمبر پلیت:
              </strong>
              <small class="mb-5" v-text="sale.sales.plate_no" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.driver_phone">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                شماره تماس دریور :
              </strong>
              <small class="number-rtl-view" v-text="sale.sales.driver_phone"></small>
            </h6>
          </vs-col>
        </vs-row>

        <!-- Ekmalat section -->
        <vs-divider>اکمالات</vs-divider>
        <vs-table :data="sale.stock_records">
          <template slot="thead">
            <vs-th>جنس / محصول</vs-th>
            <vs-th>مقدار</vs-th>
            <vs-th>قیمت فی واحد</vs-th>
            <vs-th>قیمت مجموعی</vs-th>
          </template>
          <template slot-scope="{data}">
            <vs-tr v-for="(tr, i) in data" :key="i">
              <vs-td :data="tr.item_id">
                <p> {{ (tr.item_id && tr.item_id.type) ? tr.item_id.type.type : '' }} - {{ tr.item_id.name }} </p>
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
      </tab-content>
      <tab-content v-if="sale.type == 's1' || sale.type == 's2'" title="اطلاعات مالی" class="mb-5">
        <vs-row v-if="sale.type" vs-w="12">
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                سریال نمبر:
              </strong>
              <small class="mb-5">{{sale.type}}-</small>
              <small class="mb-5" v-if="sale.sales.project">{{sale.sales.project.pro_data.reference_no}}-</small>
              <small class="mb-5" v-text="sale.sales.serial_no" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نوعیت:
              </strong>
              <small class="mb-5" v-text="$t(sale.type)" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نوع منبع:
              </strong>
              <small class="mb-5" v-text="$t(sale.source_type)" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.source_id">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                منبع:
              </strong>
              <small class="mb-5" v-text="(sale.source_id) ? $t(sale.source_id.name) : ''" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                واحد پولی:
              </strong>
              <small class="mb-5" v-text="sale.currency_id.sign_fa" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>

          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                حساب بانک:
              </strong>
              <small class="mb-5" v-text="(sale.bank_account) ? sale.bank_account.name : ''" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                تاریخ:
              </strong>
              <small class="mb-5" v-text="sale.datatime" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.project">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                پروژه:
              </strong>
              <small class="mb-5" v-text="sale.sales.project.pro_data.title" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.driver_name">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نام دریور:
              </strong>
              <small class="mb-5" v-text="sale.sales.driver_name" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.plate_no">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نمبر پلیت:
              </strong>
              <small class="mb-5" v-text="sale.sales.plate_no" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.driver_phone">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                شماره تماس دریور :
              </strong>
              <small class="number-rtl-view" v-text="sale.sales.driver_phone"></small>
            </h6>
          </vs-col>
        </vs-row>
        <vs-row v-if="sale.type" vs-w="12" class="mb-1">
          <vs-divider>اطلاعات مالی</vs-divider>
          <div>
            <vs-row>
              <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
                <h6 class="mb-5 mt-3 ml-2"> <strong class="mr-4"> مصارف خدمات : </strong> <small class="mb-5" v-text="sale.sales.service_cost" vs-justify="right" vs-align="right"></small> {{sale.currency_id.sign_fa}}</h6>
              </vs-col>
              <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.transport_cost">
                <h6 class="mb-5 mt-3 ml-2"> <strong class="mr-4"> مصارف انتقالات : </strong> <small class="mb-5" v-text="sale.sales.transport_cost" vs-justify="right" vs-align="right"></small> {{sale.currency_id.sign_fa}}</h6>
              </vs-col>
              <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.tax">
                <h6 class="mb-5 mt-3 ml-2"> <strong class="mr-4"> مالیات : </strong> <small class="mb-5" v-text="sale.sales.tax" vs-justify="right" vs-align="right"></small> %</h6>
              </vs-col>
              <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.deposit">
                <h6 class="mb-5 mt-3 ml-2"> <strong class="mr-4"> تامینات : </strong> <small class="mb-5" v-text="sale.sales.deposit" vs-justify="right" vs-align="right"></small> %</h6>
              </vs-col>
              <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
                <h6 class="mb-5 mt-3 ml-2"> <strong class="mr-4"> قیمت نهایی : </strong> <small class="mb-5" v-text="sale.sales.total" vs-justify="right" vs-align="right"></small> {{ sale.currency_id.sign_fa }}</h6>
              </vs-col>
              <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.steps">
                <h6 class="mb-5 mt-3 ml-2"> <strong class="mr-4"> پیشرفت : </strong> <small class="mb-5" v-text="calcProgress(sale.sales.steps)" vs-justify="right" vs-align="right"></small> %</h6>
              </vs-col>
              <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="12" vs-sm="12" vs-xs="12">
                <h6 class="mb-5 mt-3 ml-2"> <strong class="mr-4"> تفصیلات : </strong> <small class="mb-5" v-text="sale.sales.description" vs-justify="right" vs-align="right"></small> </h6>
              </vs-col>

            </vs-row>
          </div>
          <!--<vs-divider></vs-divider> -->
        </vs-row>
      </tab-content>
      <tab-content v-if="sale.type == 's1' || sale.type == 's2'" :title="sale.type == 's2' ? 'مصارف' : 'فورم(م-16)'" class="mb-5">
        <vs-row v-if="sale.type" vs-w="12">
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                سریال نمبر:
              </strong>
              <small class="mb-5">{{sale.type}}-</small>
              <small class="mb-5" v-if="sale.sales.project">{{sale.sales.project.pro_data.reference_no}}-</small>
              <small class="mb-5" v-text="sale.sales.serial_no" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نوعیت:
              </strong>
              <small class="mb-5" v-text="$t(sale.type)" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نوع منبع:
              </strong>
              <small class="mb-5" v-text="$t(sale.source_type)" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.source_id">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                منبع:
              </strong>
              <small class="mb-5" v-text="(sale.source_id) ? $t(sale.source_id.name) : ''" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                واحد پولی:
              </strong>
              <small class="mb-5" v-text="sale.currency_id.sign_fa" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>

          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                حساب بانک:
              </strong>
              <small class="mb-5" v-text="(sale.bank_account) ? sale.bank_account.name : ''" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                تاریخ:
              </strong>
              <small class="mb-5" v-text="sale.datatime" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.project">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                پروژه:
              </strong>
              <small class="mb-5" v-text="sale.sales.project.pro_data.title" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.driver_name">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نام دریور:
              </strong>
              <small class="mb-5" v-text="sale.sales.driver_name" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.plate_no">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نمبر پلیت:
              </strong>
              <small class="mb-5" v-text="sale.sales.plate_no" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.driver_phone">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                شماره تماس دریور :
              </strong>
              <small class="number-rtl-view" v-text="sale.sales.driver_phone"></small>
            </h6>
          </vs-col>
        </vs-row>
        <vs-row v-if="sale.type == 's2'" vs-w="12" class="mb-1">
          <vs-divider>مصارف</vs-divider>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12">
            <div class="w-full pt-2 lg:ml-3 md:ml-0 mr-3">
              <label for=""><small>مصارف انتقال</small></label>
              <vx-input-group class="number-rtl">
                <template slot="prepend">
                  <div class="prepend-text bg-primary">
                    <span>AFN</span>
                  </div>
                </template>
                <vs-input v-model="s2CostForm.transport_cost" autocomplete="off" type="number" v-validate="'required'" name="tax" />
              </vx-input-group>
              <span class="absolute text-danger alerttext">{{
              errors.first("s1Form.tax")
            }}</span>
            </div>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12">
            <div class="w-full pt-2 mr-3">
              <!-- TITLE -->
              <label for=""><small>مصارف متفرقه</small></label>
              <vx-input-group class="number-rtl">
                <template slot="prepend">
                  <div class="prepend-text bg-primary">
                    <span>AFN</span>
                  </div>
                </template>
                <vs-input v-model="s2CostForm.service_cost" autocomplete="off" type="number" v-validate="'required'" name="deposit" />
              </vx-input-group>
              <span class="absolute text-danger alerttext">{{
              errors.first("s1Form.deposit")}}</span>
            </div>
          </vs-col>

        </vs-row>
        <vs-row v-if="sale.type != 's2'" vs-w="12" class="mb-1">
          <vs-divider>فورم(م-16)</vs-divider>
          <vs-col vs-lg="6" vs-sm="12" vs-xs="12">
            <vs-row>
              <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12">
                <div class="w-full pt-2 lg:mx-3 md:mx-3 sm:mx-2">
                  <vs-input v-model="m16Form.m16_number" label="شماره M16" name="m16_number" class="w-full" />
                  <span class="absolute text-danger alerttext">{{ errors.first('step-1.m16_number') }}</span>
                  <has-error :form="m16Form" field="m16_number"></has-error>
                </div>
              </vs-col>
              <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12">
                <div class="w-full pt-2 lg:mx-3 md:mx-3 sm:mx-2">
                  <vs-input v-model="m16Form.delay_charge" label="جرایم تاخیری" name="delay_charge" class="w-full" />
                  <span class="absolute text-danger alerttext">{{ errors.first('step-1.delay_charge') }}</span>
                  <has-error :form="m16Form" field="delay_charge"></has-error>
                </div>
              </vs-col>
            </vs-row>
            <vs-row>
              <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="12">
                <div class="w-full pt-2 lg:ml-3 md:ml-0 mr-3">
                  <label for=""><small>مالیات</small></label>
                  <vx-input-group class="number-rtl">
                    <template slot="prepend">
                      <div class="prepend-text bg-primary">
                        <span>AFN</span>
                      </div>
                    </template>
                    <vs-input v-model="m16Form.tax" autocomplete="off" type="number" v-validate="'required'" name="tax" />
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
                        <span>AFN</span>
                      </div>
                    </template>
                    <vs-input v-model="m16Form.deposit" autocomplete="off" type="number" v-validate="'required'" name="deposit" />
                  </vx-input-group>
                  <span class="absolute text-danger alerttext">{{
              errors.first("s1Form.deposit")}}</span>
                </div>
              </vs-col>

            </vs-row>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="12" vs-xs="12">
            <div class="w-full pt-2 lg:mx-3 md:mx-3 sm:mx-2">
              <label for=""><small>تفصیلات/یادداشت</small></label>
              <vs-textarea rows="4" v-model="m16Form.description" name="description" class="w-full" />
              <span class="absolute text-danger alerttext">{{ errors.first('step-1.description') }}</span>
              <has-error :form="m16Form" field="description"></has-error>
            </div>
          </vs-col>
        </vs-row>
      </tab-content>
      <tab-content v-if="sale.type == 's1'" title="طی مراحل دولتی" class="mb-5">
        <vs-row v-if="sale.type" vs-w="12">
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                سریال نمبر:
              </strong>
              <small class="mb-5">{{sale.type}}-</small>
              <small class="mb-5" v-if="sale.sales.project">{{sale.sales.project.pro_data.reference_no}}-</small>
              <small class="mb-5" v-text="sale.sales.serial_no" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نوعیت:
              </strong>
              <small class="mb-5" v-text="$t(sale.type)" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نوع منبع:
              </strong>
              <small class="mb-5" v-text="$t(sale.source_type)" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.source_id">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                منبع:
              </strong>
              <small class="mb-5" v-text="(sale.source_id) ? $t(sale.source_id.name) : ''" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                واحد پولی:
              </strong>
              <small class="mb-5" v-text="sale.currency_id.sign_fa" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>

          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                حساب بانک:
              </strong>
              <small class="mb-5" v-text="(sale.bank_account) ? sale.bank_account.name : ''" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                تاریخ:
              </strong>
              <small class="mb-5" v-text="sale.datatime" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.project">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                پروژه:
              </strong>
              <small class="mb-5" v-text="sale.sales.project.pro_data.title" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.driver_name">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نام دریور:
              </strong>
              <small class="mb-5" v-text="sale.sales.driver_name" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.plate_no">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نمبر پلیت:
              </strong>
              <small class="mb-5" v-text="sale.sales.plate_no" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.driver_phone">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                شماره تماس دریور :
              </strong>
              <small class="number-rtl-view" v-text="sale.sales.driver_phone"></small>
            </h6>
          </vs-col>
        </vs-row>
        <vs-row vs-w="12" class="mb-1">
          <vs-divider>طی مراحل دولتی</vs-divider>
          <vs-col class="pl-5 space-between" vs-type="flex">
            <vs-checkbox color="success" class="float-left" size="large" v-model="saleCheckListForm.official_steps"><strong>طی مراحل اداری</strong></vs-checkbox>
            <vs-checkbox color="success" class="float-left" size="large" v-model="saleCheckListForm.mof_gov"><strong>وزارت مالیه</strong></vs-checkbox>
            <vs-checkbox color="success" class="float-left" size="large" v-model="saleCheckListForm.bank_operations"><strong>اجرائات بانکی</strong></vs-checkbox>
            <vs-checkbox color="success" class="float-left" size="large" v-model="saleCheckListForm.receive_money"><strong>دریافت پول</strong></vs-checkbox>
          </vs-col>
        </vs-row>
      </tab-content>
      <tab-content title="تاییدی" class="mb-5">
        <vs-row vs-w="12">
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                سریال نمبر:
              </strong>
              <small class="mb-5">{{sale.type}}-</small>
              <small class="mb-5" v-if="sale.sales.project">{{sale.sales.project.pro_data.reference_no}}-</small>
              <small class="mb-5" v-text="sale.sales.serial_no" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نوعیت:
              </strong>
              <small class="mb-5" v-text="$t(sale.type)" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نوع منبع:
              </strong>
              <small class="mb-5" v-text="$t(sale.source_type)" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.source_id">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                منبع:
              </strong>
              <small class="mb-5" v-text="(sale.source_id) ? $t(sale.source_id.name) : ''" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                واحد پولی:
              </strong>
              <small class="mb-5" v-text="sale.currency_id.sign_fa" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>

          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                حساب بانک:
              </strong>
              <small class="mb-5" v-text="(sale.bank_account) ? sale.bank_account.name : ''" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                تاریخ:
              </strong>
              <small class="mb-5" v-text="sale.datatime" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.project">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                پروژه:
              </strong>
              <small class="mb-5" v-text="sale.sales.project.pro_data.title" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.driver_name">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نام دریور:
              </strong>
              <small class="mb-5" v-text="sale.sales.driver_name" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.plate_no">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                نمبر پلیت:
              </strong>
              <small class="mb-5" v-text="sale.sales.plate_no" vs-justify="right" vs-align="right"></small>
            </h6>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12" v-if="sale.sales.driver_phone">
            <h6 class="mb-5 mt-3 ml-2">
              <strong class="mr-4">
                شماره تماس دریور :
              </strong>
              <small class="number-rtl-view" v-text="sale.sales.driver_phone"></small>
            </h6>
          </vs-col>
        </vs-row>
        <vs-row vs-w="12" class="my-3">
          <vs-divider>تاییدی</vs-divider>
          <vs-col class="pl-5 my-3">
            <vs-checkbox v-if="sale.type == 's3'" color="success" class="float-left" size="large" v-model="saleCheckListForm.get_documents"><strong>دریافت پارچه/مکتوب اداره</strong></vs-checkbox>
          </vs-col>
          <vs-col class="pl-5 my-3">
            <vs-checkbox v-if="user_permissions.includes('steps_confirmation_allow')" color="success" class="float-left" size="large" v-model="is_confirmed"><strong>تمام اطلاعات درج شده توسط مدیر سیستم تایید میگردد.</strong></vs-checkbox>
          </vs-col>
        </vs-row>
      </tab-content>
      <div class="flex space-between mt-2" v-if="$refs.wizard">
        <vs-button @click="$refs.wizard.prevTab">قبلی</vs-button>
        <div class="flex">
          <vs-button v-if="!$refs.wizard.isLastStep && current_tab == 2 && sale.type == 's2'" color="success" @click.prevent="submitS2Form" style="float: left;margin-top: 2px;" class="mx-2">ثبت</vs-button>
          <vs-button v-if="!$refs.wizard.isLastStep && current_tab == 2 && sale.type != 's2'" color="success" @click.prevent="submitM16Form" style="float: left;margin-top: 2px;" class="mx-2">ثبت</vs-button>
          <vs-button v-if="!$refs.wizard.isLastStep && current_tab == 3" color="success" @click.prevent="submitStepsForm" style="float: left;margin-top: 2px;" class="mx-2">ثبت</vs-button>
          <vs-button v-if="$refs.wizard.isLastStep" color="success" @click.prevent="submitConfirmation" class="mx-2">ثبت</vs-button>
          <vs-button v-if="!$refs.wizard.isLastStep" @click="$refs.wizard.nextTab">بعدی</vs-button>
          <vs-button v-if="$refs.wizard.isLastStep" @click="popupModalActive = false, sale = [], $refs.wizard.hideButtons = true">بستن صفحه</vs-button>
        </div>
      </div>
    </form-wizard>
  </vs-popup>
  <vs-popup class="holamundo" title="معلومات فروشات" :active.sync="saleModalActive">
    <sale-view :sale="sale_to_view" />
  </vs-popup>
</div>
</template>

<script>
import TableLoading from './../../shared/TableLoading'
import SaleView from '../view/SaleView'
import {
  FormWizard,
  TabContent
} from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
export default {
  components: {
    FormWizard,
    TabContent,
    TableLoading,
    SaleView
  },
  data() {
    return {
      current_tab: 0,
      is_confirmed: false,
      user_permissions: localStorage.getItem('user_permissions'),
      popupModalActive: false,
      saleModalActive: false,
      sales: [],
      sale: [],
      sale_to_view: null,
      popupActive: false,
      isloaded: false,
      selected: [],
      itemsPerPage: 10,
      isMounted: false,
      addNewDataSidebar: false,
      sidebarData: {},
      saleCheckListForm: new Form({
        official_steps: false,
        mof_gov: false,
        bank_operations: false,
        receive_money: false,
        sale_id: false,
        sale_type: false,
        get_documents: false
      }),
      s2CostForm: new Form({
        transport_cost: 0,
        service_cost: 0,
        sale_id: false,
        sale_type: false,
      }),
      m16Form: new Form({
        m16_number: '',
        delay_charge: '',
        tax: '',
        deposit: '',
        description: '',
        sale_id: '',
        sale_type: ''
      }),
    }
  },
  computed: {
    currentPage() {
      if (this.isMounted) {
        return this.$refs.table.currentx
      }
      return 0
    },
    queriedItems() {
      return this.$refs.table ? this.$refs.table.queriedResults.length : this.sales.length
    }
  },
  methods: {
    calcProgress(step) {
      if (this.sale.type == 's1') {
        return ((step * 100) / 8).toFixed(0);
      } else if (this.sale.type == 's2') {
        return ((step * 100) / 6).toFixed(0);
      } else if (this.sale.type == 's3') {
        return ((step * 100) / 3).toFixed(0);
      } else {
        return 0;
      }
    },
    can_do_this() {
      return (
        user_permissions.includes('sales1_view') ||
        user_permissions.includes('sales2_view') ||
        user_permissions.includes('sales3_view') ||
        user_permissions.includes('sales4_view') ||
        user_permissions.includes('sales1_steps') ||
        user_permissions.includes('sales2_steps') ||
        user_permissions.includes('sales3_steps') ||
        user_permissions.includes('sales4_steps') ||
        user_permissions.includes('sales1_edit') ||
        user_permissions.includes('sales2_edit') ||
        user_permissions.includes('sales3_edit') ||
        user_permissions.includes('sales4_edit')
      )
    },
    showPrintData(data) {
      window.open(`/print?entity=sale&type=${data.type}&id=${data.sales_id}&name=${localStorage.getItem("name")} ${localStorage.getItem("lastname")}`, '_blank');
    },
    showCheckModal(id) {
      this.s2CostForm.reset();
      this.saleCheckListForm.reset();
      this.m16Form.reset();
      this.$Progress.start()
      this.axios.get('/api/sale/' + id)
        .then((response) => {
          this.current_tab = 0;
          this.sale = response.data;
          if (this.sale.sale_checklists) {
            this.saleCheckListForm.fill(this.sale.sale_checklists);
          }
          if (this.sale.m16s) {
            this.m16Form.fill(this.sale.m16s);
          }
          if (this.sale.financial_records) {
            this.is_confirmed = (this.sale.financial_records.valid) ? true : false;
          }
          this.s2CostForm.transport_cost = this.sale.sales.transport_cost;
          this.s2CostForm.service_cost = this.sale.sales.service_cost;
          this.$refs.wizard.navigateToTab(this.current_tab);
          this.popupModalActive = true;
          this.$Progress.set(100)
        })
        .catch(() => {
          document.getElementById("loading-bg").style.display = "none";
        });
    },
    submitConfirmation() {
      this.axios
        .post(`/api/sale_info_confirmation/${this.sale.type}/${this.sale.id}`, {
          confirmed: this.is_confirmed,
          form: this.saleCheckListForm
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
    submitStepsForm() {
      this.saleCheckListForm.sale_id = this.sale.id;
      this.saleCheckListForm.sale_type = this.sale.type;
      this.saleCheckListForm.post("/api/sale_check_list")
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
          document.getElementById("loading-bg").style.display = "none";
        });
    },
    submitS2Form() {
      this.s2CostForm.sale_id = this.sale.id;
      this.s2CostForm.sale_type = this.sale.type;
      this.s2CostForm.post("/api/s2costform")
        .then((data) => {
          swal.fire(
            'موفق!',
            'معلومات مصارف موفقانه آپدیت شد!',
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
    submitM16Form() {
      this.m16Form.sale_id = this.sale.id;
      this.m16Form.sale_type = this.sale.type;
      this.m16Form.post("/api/m16")
        .then((data) => {
          swal.fire(
            'موفق!',
            'فورم ام۱۶ موفقانه ثبت شد!',
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
    getAllSales() {
      this.$Progress.start()
      this.axios
        .get("/api/sales")
        .then((data) => {
          this.sales = data.data;
          this.isloaded = true;
          this.$Progress.set(100);
          if(this.sales.length){
            this.showCheckModal(this.sales[0]['sales_id']);
          }
        })
        .catch(() => {
          document.getElementById("loading-bg").style.display = "none";
        });
    },
    viewData(sale) {
      this.$Progress.start()
      this.axios
        .get("/api/sale/" + sale.sales_id)
        .then((data) => {
          this.sale_to_view = data.data;
          this.saleModalActive = true;
          this.$Progress.set(100);
        })
        .catch(() => {
          document.getElementById("loading-bg").style.display = "none";
        });
    },
    deleteData(id) {
      swal.fire({
        title: 'آیا  مطمئن هستید؟',
        text: "ریکارد فروش حذف خواهد شد",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'rgb(54 34 119)',
        cancelButtonColor: 'rgb(229 83 85)',
        confirmButtonText: '<span>بله، حذف شود!</span>',
        cancelButtonText: '<span>خیر، لغو عملیه!</span>'
      }).then((result) => {
        if (result.isConfirmed) {
          this.axios.delete('/api/sale1/' + id).then((id) => {
              swal.fire({
                title: 'عملیه موفقانه انجام شد.',
                text: "مورد فروش از سیستم پاک شد!",
                icon: 'success',
              })
              this.getAllSales();
            })
            .catch(() => {
              swal.fire(
                'ناموفق !',
                'سیستم قادر به حذف نیست. وابستگی های آیتم را بررسی نمایید!',
                'error'
              )

              document.getElementById("loading-bg").style.display = "none";
            });
        }
      })
    },
    // Demo codes
    addNewData() {
      this.sidebarData = {}
      this.toggleDataSidebar(true)
    },
    editData(data) {
      // this.sidebarData = JSON.parse(JSON.stringify(this.blankData))
      this.sidebarData = data
      this.toggleDataSidebar(true)
    },
    toggleDataSidebar(val = false) {
      this.addNewDataSidebar = val
    },
    goToEdit(data) {
      this.$router
        .push({
          path: `/sales/sale/${data.sales_id}/edit/${data.type}`,
          params: {
            id: data.id,
            dyTitle: data.title
          },
        })
        .catch(() => {
          document.getElementById("loading-bg").style.display = "none";
        });
    },
  },
  created() {
    this.getAllSales();
  },
  mounted() {
    this.isMounted = false
  }
}
</script>

<style>
[dir] .vs-button.includeIcon {
  float: right;
}
</style><style lang="scss">
#data-list-list-view {
  .vs-con-table {

    /*
      Below media-queries is fix for responsiveness of action buttons
      Note: If you change action buttons or layout of this page, Please remove below style
    */
    @media (max-width: 689px) {
      .vs-table--search {
        margin-left: 0;
        max-width: unset;
        width: 100%;

        .vs-table--search-input {
          width: 100%;
        }
      }
    }

    @media (max-width: 461px) {
      .items-per-page-handler {
        display: none;
      }
    }

    @media (max-width: 341px) {
      .data-list-btn-container {
        width: 100%;

        .dd-actions,
        .btn-add-new {
          width: 100%;
          margin-right: 0 !important;
        }
      }
    }

    .product-name {
      max-width: 23rem;
    }

    .vs-table--header {
      display: flex;
      flex-wrap: wrap;
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
        box-shadow: 0 4px 20px 0 rgba(0, 0, 0, .05);

        td {
          padding: 20px;

          &:first-child {
            border-top-left-radius: .5rem;
            border-bottom-left-radius: .5rem;
          }

          &:last-child {
            border-top-right-radius: .5rem;
            border-bottom-right-radius: .5rem;
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

.sales_step_wizard .wizard-card-footer.clearfix {
  display: none !important;
}

.con-vs-popup .vs-popup {
  width: 70%;
}

[dir] .vue-form-wizard .wizard-tab-content {
  padding: 30px 20px 2px 10px !important;
}
</style>
