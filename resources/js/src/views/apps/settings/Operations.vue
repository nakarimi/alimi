<template>
<vs-row vs-w="12" class="mb-4">
  <div class="w-full">
    <div class="vx-card">
      <div class="vx-card__header">
        <div class="vx-card__title">
          <h4 class="">تنظیمات عملیه های اساسی</h4>
        </div>
        <vs-button v-if="operationActiveForm" @click="operationActiveForm = !operationActiveForm, operationForm.reset()">بستن فورم </vs-button>
      </div>
      <!--<vs-button type="border" @click="blue=true"></vs-button>-->
      <component :is="scrollbarTag" :key="$vs.rtl" class="setting-height-scroll">
        <div class="pt-6 pr-6 pl-6 pb-6">
          <div class="vx-card__body">
            <div class="vx-col w-full mb-base" v-if="operationActiveForm">
              <div class="vx-col w-full">
                <div class="vx-col w-full">
                  <span>عنوان</span>
                </div>
                <div class="vx-col w-full">
                  <vs-input class="w-full" v-model="operationForm.title" />
                  <has-error :form="operationForm" field="title"></has-error>
                </div>
              </div>
              <div class="vx-col w-full">
                <div class="vx-col w-full">
                  <span>فرمول</span>
                </div>
                <div class="vx-col w-full">
                  <vs-input class="w-full" v-model="operationForm.formula" />
                  <has-error :form="operationForm" field="formula"></has-error>
                </div>
              </div>
              <div class="vx-col w-full">
                <div class="vx-col w-full">
                  <span>معلومات</span>
                </div>
                <div class="vx-col w-full">
                  <vs-input class="w-full" v-model="operationForm.description" />
                  <has-error :form="operationForm" field="description"></has-error>
                </div>
              </div>
              <div class="vx-col w-full mb-base mt-5 float-right">
                <vs-button v-if="!operationForm.id" class="shadow-md w-full lg:mt-0 mt-4 mr-3 mb-2" @click="storeOperation">ثبت عملیه</vs-button>
                <vs-button v-if="operationForm.id" class="shadow-md w-full lg:mt-0 mt-4 mb-2" @click="updateOperation">آپدیت عملیه</vs-button>
              </div>
            </div>
            <vs-table :data="operations" stripe>
              <template slot="thead">
                <vs-th>#</vs-th>
                <vs-th>عنوان</vs-th>
                <vs-th>فرمول</vs-th>
                <vs-th>معلومات</vs-th>
                <vs-th v-if="user_permissions.includes('setting_operations')">عملیه</vs-th>
              </template>
              <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="tr.id">
                    {{ tr.id }}
                  </vs-td>
                  <vs-td :data="tr.id">
                    {{ tr.title }}
                  </vs-td>

                  <vs-td :data="tr.id">
                    {{ tr.formula }}
                  </vs-td>

                  <vs-td :data="tr.id">
                    {{ tr.description }}
                  </vs-td>
                  <vs-td :data="tr.id" v-if="user_permissions.includes('setting_operations')">
                    <div class="inline-flex">
                      <feather-icon icon="EditIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click.stop="editOperation(tr)" />
                      <!-- <vs-button @click="deleteOperation(tr.id)" color="warning" type="flat" icon-pack="feather" icon="icon-trash"></vs-button> -->
                    </div>
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </div>
      </component>
    </div>
  </div>
</vs-row>
</template>

<script>
export default {
  name: "operations",
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      operationActiveForm: false,
      operations: [],
      operationForm: new Form({
        title: '',
        formula: '',
        description: '',
        id: null,
      }),
    }
  },
  computed: {
    scrollbarTag() {
      return this.$store.getters.scrollbarTag;
    },
  },
  created() {
    this.getAllOperation();
  },
  methods: {
    getAllOperation() {
      this.axios.get('/api/operation')
        .then((response) => {
          this.operations = response.data;
        })

    },
    editOperation(data) {
      this.operationActiveForm = true;
      this.operationForm.title = data.title;
      this.operationForm.formula = data.formula;
      this.operationForm.description = data.description;
      this.operationForm.id = data.id;
      // 

    },
    storeOperation() {
      // Start the Progress Bar
      this.operationForm.post('/api/operation')
        .then(({
          data
        }) => {
          // 
          this.getAllOperation();
          this.operationForm.reset();
          this.$vs.notify({
            title: 'موفقیت!',
            text: 'معلومات موفقانه ثبت سیستم شد.',
            color: 'success',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
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
    },
    updateOperation() {
      this.operationForm.patch('/api/operation/' + this.operationForm.id)
        .then(({
          data
        }) => {
          // Finish the Progress Bar
          this.getAllOperation();
          // toast notification
          this.$vs.notify({
            title: 'موفقیت!',
            text: 'عملیه موفقانه آپدیت شد.',
            color: 'success',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
        });
    },
    deleteOperation(id) {
      swal.fire({
        title: 'آیا  مطمئن هستید؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'rgb(54 34 119)',
        cancelButtonColor: 'rgb(229 83 85)',
        confirmButtonText: '<span>بله، حذف شود!</span>',
        cancelButtonText: '<span>خیر، لغو عملیه!</span>'
      }).then((result) => {
        if (result.isConfirmed) {
          this.operationForm.delete('/api/operation/' + id).then((id) => {
              swal.fire({
                title: 'عملیه موفقانه انجام شد.',
                icon: 'success',
              })
              this.getAllOperation();
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

    }
  }
}
</script>
