<template>
<div>
  <!-- eteration -->
  <div v-for="i in items" :key="i.id">
    <vs-row vs-w="12" class="pb-2 mb-2">
      <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="8" vs-sm="8" vs-xs="12">
        <div class="w-full pt-2 ml-3 mr-3">
          <label for=""><small>جنس / محصول</small></label>
          <v-select label="name" v-model="i.item_id" :options="goods" :dir="$vs.rtl ? 'rtl' : 'ltr'" />
          <has-error :form="form" field="item_id"></has-error>
        </div>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="4" vs-xs="12">
        <div class="w-full pt-2 ml-3 mr-3">
          <label for=""><small>عملیه</small></label>
          <v-select label="title" @input="operationChange" v-model="i.operation_id" :options="operations" :dir="$vs.rtl ? 'rtl' : 'ltr'" />
        </div>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
        <div class="w-full pt-2 ml-3 mr-3">
          <label for>
            <small>مقدار</small>
          </label>
          <vx-input-group class="">
            <template slot="append">
              <div class="append-text bg-primary">
                <span>{{ findUom(i.item_id, 'uom_equiv_id') }}</span>
              </div>
            </template>
            <vs-input type="number" min="0" v-model="i.ammount" />
          </vx-input-group>
          <has-error :form="form" field="ammount"></has-error>
        </div>
      </vs-col>
      <vs-col vs-type="flex" v-if="i.operation_id && i.operation_id.id == 1" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
        <div class="w-full pt-2 ml-3 mr-3">
          <vs-input v-validate="'required'" v-model="i.density" label="ثقلت" class="w-full" />
          <has-error :form="form" field="density"></has-error>
          <!--<span class="text-danger text-sm" v-show="errors.has('reference_no')">{{ errors.first('reference_no') }}</span>-->
        </div>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
        <div class="w-full pt-2 ml-3 mr-3">
          <label for>
            <small>معادل</small>
          </label>
          <vx-input-group class="">
            <template slot="append">
              <div class="append-text bg-primary">
                <span>{{ findUom(i.item_id, 'uom_id') }}</span>
              </div>
            </template>
            <vs-input v-validate="'required|min:2'" type="number" v-model="i.equivalent" />
          </vx-input-group>
          <has-error :form="form" field="equivalent"></has-error>
        </div>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
        <div class="w-full pt-2 ml-3 mr-3">
          <label for>
            <small>قیمت فی واحد</small>
          </label>
          <vx-input-group class="">
            <template slot="prepend">
              <div class="prepend-text bg-primary">
                <span>AFN</span>
              </div>
            </template>
            <vs-input type="number" v-model="i.unit_price" />
          </vx-input-group>
          <has-error :form="form" field="unit_price"></has-error>
        </div>
      </vs-col>
      <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="3" vs-sm="6" vs-xs="12">
        <div class="w-full pt-2 ml-3 mr-3">
          <label for>
            <small>قیمت مجموعی</small>
          </label>
          <vx-input-group class="">
            <template slot="prepend">
              <div class="prepend-text bg-primary">
                <span>AFN</span>
              </div>
            </template>
            <vs-input type="number" v-model="i.total_price" />
          </vx-input-group>
          <has-error :form="form" field="total_price"></has-error>
        </div>
      </vs-col>
    </vs-row>
  </div>
  <vs-row vs-w="12">
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="4" vs-xs="12" class="pt-2 mb-2 ml-3 mr-3">
      <vs-button type="border" @click="addNewRow" color="success" icon="add"></vs-button>
      &nbsp;&nbsp;
      <vs-button type="border" @click="removeRow" color="danger" icon="delete" :disabled="this.items.length <= 1"></vs-button>
    </vs-col>
  </vs-row>
</div>
</template>

<script>
import vSelect from "vue-select";

export default {
  name: "vx-ekmalat",
  props: ["items", "form"],
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      operations: [],
      density: 1,
      item_types: [],
      mesure_unit: [],
      goods: [],
    };
  },
  created() {
    this.getOperations();
    this.getAllItems();
    this.getAllUnites();
  },
  methods: {
    operationChange() {
      // 
    },
    getOperations() {
      this.$Progress.start();
      this.axios.get("/api/operation").then((response) => {
        this.operations = response.data;
      });
    },
    getItemTypes() {
      this.$Progress.start();
      this.axios.get("/api/item-type").then((response) => {
        this.item_types = response.data;
      });
    },
    // for items to be bought
    getAllItems() {
      this.$Progress.start()
      this.axios.get('/api/items')
        .then((response) => {
          this.goods = response.data;
        })
    },
    // for getting measure unit of the item
    getAllUnites() {
      this.axios.get('/api/m-units')
        .then((response) => {
          this.mesure_unit = response.data;
        })
    },
    addNewRow() {
      this.items.push({
        item_id: "",
        operation_id: null,
        equivalent: 1,
        ammount: "3",
        unit_price: "302",
        total_price: "906",
        density: 1,
      });
    },
    findUom(id, field) {
      let unit = null;
      Object.keys(this.goods).some(key => (this.goods[key].id == id) ? unit = this.goods[key][field] : '');
      let resp = '';
      Object.keys(this.mesure_unit).some(key => (this.mesure_unit[key].id == unit) ? resp = this.mesure_unit[key].acronym : '');
      return resp;
    },
    removeRow() {
      swal.fire({
        title: 'آیا  مطمئن هستید؟',
        text: "جنس مورد نظر حذف خواهد شد",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'rgb(54 34 119)',
        cancelButtonColor: 'rgb(229 83 85)',
        confirmButtonText: '<span>بله، حذف شود!</span>',
        cancelButtonText: '<span>خیر، لغو عملیه!</span>'
      }).then((result) => {
        if (result.isConfirmed) {
          let id = this.items[this.items.length - 1].id;
          this.axios.delete('/api/pro-item/' + id).then((id) => {
              swal.fire({
                title: 'عملیه موفقانه انجام شد.',
                text: "جنس مورد نظر از سیستم پاک شد!",
                icon: 'success',
              })
              if (this.items.length > 1) {
                this.items.splice(this.items.length - 1, 1);
              }
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
  },
  components: {
    "v-select": vSelect,
  },
};
</script>
