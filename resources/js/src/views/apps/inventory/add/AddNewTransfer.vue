<template>
<component :is="scrollbarTag" class="scroll-area--data-list-add-new">
  <vx-card class="no-shadow">
    <form data-vv-scope="transferAddForm">
      <div class="vx-row">
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="6">
          <div class="w-full pb-2 ml-3 mr-3">
            <label for=""><small>سریال نمبر</small></label>
            <vs-input class="w-full" disabled :value="tForm.serial_no" autocomplete="off" type="number" />
          </div>
        </vs-col>

        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="6">
          <div class="w-full pb-2 ml-3 mr-3">
            <label for="date" class="mt-3"><small>تاریخ </small></label>
            <date-picker name="trans_date" v-validate="'required'" inputFormat="jYYYY/jMM/jDD HH:mm" display-format="jYYYY/jMM/jDD hh:mm" color="#e85454" :auto-submit="true" v-model="tForm.datetime" type="datetime" />
            <span class="absolute text-danger alerttext">{{ errors.first('transferAddForm.trans_date') }}</span>
          </div>
        </vs-col>

        <!-- Must only consist of numbers -->

        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="12" vs-sm="12" vs-xs="12">
          <div class="w-full pb-2 ml-3 mr-3">
            <vs-input size="medium" name="trans_title" v-validate="'required|min:2'" v-model="tForm.title" label="عنوان انتقال" class="w-full" />
            <span class="absolute text-danger alerttext">{{ errors.first('transferAddForm.trans_title') }}</span>
          </div>
        </vs-col>

        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="6">
          <div class="w-full pb-2 ml-3 mr-3">
            <label for=""><small>منبع</small></label>
            <source-select ref="str" :parentForm="tForm" @updateItems="update_items" name="source" v-validate="'required'" v-model="tForm.source_id"></source-select>
            <span class="absolute text-danger alerttext">{{ errors.first('transferAddForm.source') }}</span>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="6">
          <div class="w-full pb-2 ml-3 mr-3">
            <label for=""><small>مقصد</small></label>
            <destination-select ref="destr" :parentForm="tForm" name="trans_destination" v-validate="'required'" v-model="tForm.destination"></destination-select>
            <span class="absolute text-danger alerttext">{{ errors.first('transferAddForm.trans_destination') }}</span>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="12" vs-sm="12" vs-xs="12">
          <!-- EkmalatStock -->
          <div class="mr-3 pb-2 ml-3">
            <ekmalat-stock :disabledFields="['total_price', 'unit_price']" :items="tForm.item" :form="tForm" :grid="[6, 6, 12]" ref="ekmalat"></ekmalat-stock>
          </div>
        </vs-col>

        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="6">
          <div class="w-full pb-2 ml-3 mr-3 ">
            <label for>
              <small>مصارف انتقال</small>
            </label>
            <vx-input-group>
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>AFN</span>
                </div>
              </template>

              <vs-input v-model="tForm.transit" name="trans_transit" v-validate="'required'" type="number" />
            </vx-input-group>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="6">
          <div class="w-full pb-2 ml-3 mr-3 ">
            <label for>
              <small>مصارف ترازو</small>
            </label>
            <vx-input-group>
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>AFN</span>
                </div>
              </template>

              <vs-input v-model="tForm.scale" name="trans_scale" v-validate="'required'" type="number" />
            </vx-input-group>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="6">
          <div class="w-full pb-2 ml-3 mr-3 ">
            <label for>
              <small>مصارف متفرقه</small>
            </label>
            <vx-input-group>
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>AFN</span>
                </div>
              </template>

              <vs-input v-model="tForm.others" name="trans_others" v-validate="'required'" type="number" />
            </vx-input-group>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="6">
          <div class="w-full pb-2 ml-3 mr-3 ">
            <label for>
              <small>مصارف مجموعی</small>
            </label>
            <vx-input-group>
              <template slot="prepend">
                <div class="prepend-text bg-primary">
                  <span>AFN</span>
                </div>
              </template>

              <vs-input :value="tForm.total = total_cost" name="trans_total" v-validate="'required'" v-model="tForm.total" type="number" />
            </vx-input-group>
          </div>
        </vs-col>
        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6" vs-xs="6">
          <div class="w-full pb-2 ml-3 mr-3">
            <vs-input size="medium" v-model="tForm.supervisor" name="trans_supervisor" v-validate="'required'" label="شخص مسول" class="w-full" />
          </div>
        </vs-col>

        <div class="w-full mt-4 mr-3 ml-3">
          <vs-textarea v-model="tForm.description" label="تفصیلات" :rows="tForm.description && tForm.description.split(`\n`).length > 2 ? tForm.description.split(`\n`).length + 1 : 3"></vs-textarea>
        </div>
      </div>
      <vs-button :disabled="tForm.busy" v-if="!entityData" type="filled" @click.prevent="submitForm" class="mt-5 block">ثبت</vs-button>
      <vs-button :disabled="tForm.busy" v-if="entityData" type="filled" @click.stop="submitEditData" class="mt-5 block">ویرایش</vs-button>
      <vs-button :disabled="tForm.busy" type="filled" color="danger" @click.stop="$emit('editCompleted')" class="mt-5 block">لغو</vs-button>
    </form>
  </vx-card>
</component>
</template>

<script>
import vSelect from "vue-select";
import EkmalatStock from "../../shared/EkmalatStock";
import SourceSelect from "../../shared/SourceSelect";
import DestinationSelect from "../../shared/DestinationSelect";
import {
  Validator
} from 'vee-validate'

export default {
  props: {
    entityData: {
      required: false,
    },
  },
  components: {
    'v-select': vSelect,
    EkmalatStock,
    DestinationSelect,
    SourceSelect,
    Validator
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      // Main INIT
      tForm: new Form({
        serial_no: '',
        source: '',
        source_id: "", // The Id of the Source.
        source_type: "", // Type Source
        datetime: this.momentj().format('jYYYY/jMM/jDD HH:mm'),
        title: '',
        supervisor: '',
        description: '',
        destination_id: '',
        destination: '',
        currency_id: 1,
        // Costs ....
        transit: '0',
        scale: '0',
        others: '0',
        total: '0',

        user_id: localStorage.getItem('id'),
        // Item for the ekmalat section
        item: [{
          item_id: "",
          unit_id: "",
          operation_id: null,
          increment_equiv: "",
          increment: "",
          unit_price: "",
          total_price: "",
          density: "",
        }],

      }),
      items: [],
      // End Main
      settings: { // perfectscrollbar settings
        maxScrollbarLength: 70,
        wheelSpeed: .60
      },
      dict: {
        custom: {
          trans_date: {
            required: ' تاریخ انتقال الزامی میباشد.'
          },
          trans_title: {
            required: ' عنوان انتقال الزامی میباشد.',
            min: 'عنوان انتقال باید بیشتر از 2 حرف باشد.'
          },
          source: {
            required: 'منبع انتقال الزامی میباشد.'
          },
          trans_destination: {
            required: ' مقصد انتقال الزامی میباشد.'
          },
          // trans_transit: { required: ' مصارف انتقال الزامی میباشد.' },
          // trans_scale: { required: ' مصارف ترازو انتقال الزامی میباشد.' },
          // trans_others: { required: ' مصارف متفرقه انتقال الزامی میباشد.' },
          // trans_total: { required: ' مصارف مجموعی انتقال الزامی میباشد.' },
          // trans_supervisor: { required: ' شخص مسول انتقال الزامی میباشد.' }
        }
      }
    }
  },
  computed: {
    itFormValid() {
      //   return !this.errors.any() && this.dataName && this.dataCategory && this.dataPrice > 0
    },
    scrollbarTag() {
      return this.$store.getters.scrollbarTag
    },
    // To calculate the total price for :the proposal
    total_cost: function () {
      let others = (this.tForm.others) ? parseInt(this.tForm.others) : 0;
      let transit = (this.tForm.transit) ? parseInt(this.tForm.transit) : 0;
      let scale = (this.tForm.scale) ? parseInt(this.tForm.scale) : 0;

      let total_items = 0;
      this.tForm.item.filter(function (item) {
        if (item && item.total_price) {
          total_items += parseInt(item.total_price);
        }
      })
      return others + scale + transit + total_items;
    },
  },
  created() {
    Validator.localize('en', this.dict);
    if (this.entityData) {
      this.axios.get(`/api/transfer/${this.entityData.id}/edit`)
        .then((response) => {
          this.getTransfer(response.data);
        })

    } else {
      this.getNextSerialNo();
    }
  },
  methods: {
    getTransfer(d) {
      this.tForm.serial_no = d.serial_no
      this.tForm.source = d.source
      this.tForm.source_id = d.source_id
      this.tForm.source_type = d.source_type
      this.tForm.datetime = d.datetime
      this.tForm.title = d.title
      this.tForm.supervisor = d.supervisor
      this.tForm.description = d.description
      this.tForm.destination_id = d.destination_id
      this.tForm.destination = d.destination
      this.tForm.currency_id = d.currency_id
      this.tForm.transit = d.transit
      this.tForm.scale = d.scale
      this.tForm.others = d.others
      this.tForm.total = d.total
      this.tForm.item = d.item
      if (this.$refs.str) {
        this.$refs.str.findAndSet(d.source_id, d.source_type);
      }
      if (this.$refs.destr) {
        this.$refs.destr.findAndSet(d.destination_id, d.destination);
      }
      for (const [index, item] of Object.values(this.tForm.item).entries()) {
        this.tForm.item[index].increment = this.tForm.item[index].decrement;
        this.tForm.item[index].increment_equiv = this.tForm.item[index].decrement_equiv;
        var data = {
          increment: this.tForm.item[index].increment,
          increment_equiv: this.tForm.item[index].increment_equiv,
          unit_price: this.tForm.item[index].unit_price,
          total_price: this.tForm.item[index].total_price,
        }
        console.log('data', data, d.item);
        if (this.$refs.ekmalat) {
          this.$refs.ekmalat.addRow({
            'key': index,
            'data': data
          });
          this.$refs.ekmalat.itemSelected('', this.tForm.item[index].item_id.id, index, this.tForm.item[index].item_id.uom_id.acronym);
          this.$refs.ekmalat.operationChange(this.tForm.item[index].operation_id, index);
        }
      }
    },
    update_items(matched_items) {
      this.$refs.ekmalat.getAllItems(matched_items);
    },
    // for getting the next serian number
    getNextSerialNo() {
      this.$Progress.start()
      this.axios.get('/api/serial-num?type=transfer')
        .then((response) => {
          this.tForm.serial_no = response.data;
          this.tForm.user_id = localStorage.getItem('id');
          // Item for the ekmalat section
          this.tForm.item = [{
            item_id: "",
            unit_id: "",
            operation_id: null,
            increment_equiv: "",
            increment: "",
            unit_price: "0",
            total_price: "0",
            density: 1,
          }];
        })
    },
    submitEditData() {
      this.$validator.validateAll('transferAddForm').then(result => {
        if (result) {
          this.$Progress.start()
          this.tForm.patch('/api/transfer/' + this.entityData.id)
            .then(({
              data
            }) => {
              this.$vs.notify({
                title: 'موفقیت!',
                text: 'انتقال موفقانه آپدیت شد.',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
              this.$Progress.set(100)
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

          // form have errors
        }
      })
      // Start the Progress Bar

    },
    submitForm() {
      this.$validator.validateAll('transferAddForm').then(result => {
        if (result) {
          this.$Progress.start()
          this.tForm.post('/api/transfer')
            .then(({
              data
            }) => {
              this.$vs.notify({
                title: 'موفقیت!',
                text: 'انتقال موفقانه ثبت شد.',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
              this.tForm.reset();
              this.$validator.reset();
              this.getNextSerialNo();
              this.$Progress.set(100)
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

          // form have errors
        }
      })
      // Start the Progress Bar

    }
  },
}
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
