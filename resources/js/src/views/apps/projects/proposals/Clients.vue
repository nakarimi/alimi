<template>
<div>
  <vs-sidebar position-right parent="body" default-index="1" color="primary" class="add-new-data-sidebar items-no-padding" spacer v-model="isSidebarActiveLocal">
    <div class="mt-6 flex items-center justify-between px-6 float-right">
      <feather-icon icon="XIcon" @click.stop="isSidebarActiveLocal = false" class="cursor-pointer"></feather-icon>
    </div>
    <vs-tabs>
      <vs-tab v-if="user_permissions.includes('proposal_view')" label="لست نهادها" icon="list" class="leftScrol">
        <div class="scroll-area--data-list-add-new" :key="$vs.rtl" v-if="orgActiveForm">
          <form>
            <vs-divider>
              <h4>
                ویرایش معلومات نهاد
              </h4>
            </vs-divider>
            <div class="p-2">
              <template>
                <div class="vx-row">
                  <div class="w-16 mx-auto flex items-center justify-center ml-5">
                    <img v-if="oldImage" :src="'/images/img/clients/'+orgFormEdit.logo" alt="نشان نهاد" class="responsive">
                    <img v-if="!oldImage" :src="logoToChange" alt="نشان نهاد" class="responsive">
                  </div>
                  <div class="modify-img flex justify-between mt-5 pt-5 ml-4">
                    <div class="mr-5 pr-5">
                      <input type="file" class="hidden" ref="updateImgInput1" @change="updateCurrImg1" accept="image/*">
                      <vs-button class="" icon="edit" color="primary" type="border" @click="$refs.updateImgInput1.click()">تبدیل نشان نهاد</vs-button>
                    </div>
                    <div class="mr-5 pr-5">
                      <vs-button v-if="!oldImage" icon="delete" type="border" color="warning" @click="deletOnChangeLogo()">حذف این نشان نهاد</vs-button>
                    </div>
                  </div>
                </div>
              </template>

              <vs-input label="نام نهاد" class="w-full" v-model="orgFormEdit.name" />
              <has-error :form="orgFormEdit" field="name"></has-error>

              <vs-input label="ایمیل" type="email" class="mt-2 w-full" v-model="orgFormEdit.email" />
              <has-error :form="orgFormEdit" field="email"></has-error>
              <vs-input label=" شماره تماس " type="text" class="mt-2 w-full" v-model="orgFormEdit.phone" />
              <has-error :form="orgFormEdit" field="phone"></has-error>
              <vs-input label="ویب سایت" type="text" class="mt-2 w-full" v-model="orgFormEdit.website" />
              <has-error :form="orgFormEdit" field="website"></has-error>
              <vs-input label=" آدرس" type="text" class="mt-2 w-full" v-model="orgFormEdit.address" />
              <has-error :form="orgFormEdit" field="address"></has-error>
              <!-- Upload -->
              <!-- <vs-upload text="Upload Image" class="img-upload" ref="fileUpload" /> -->

              <!--<div class="upload-img mt-5" v-if="!orgFormEdit.logo">
                  <input type="file" class="hidden" ref="uploadImgInput1" @change="updateCurrImg1" accept="image/*">
                  <vs-button icon="image" @click="$refs.uploadImgInput.click()">اپلود نشان نهاد</vs-button>
                </div> -->
            </div>
            <div class="flex flex-wrap items-center p-2" slot="footer">
              <vs-button type="border" icon="edit" color="success" class="mr-6" @click="updateData()">ویرایش</vs-button>
              <vs-button type="border" icon="close" color="danger" @click="resetAllState()">بستن فورم ویرایش</vs-button>
            </div>
            <vs-divider>---</vs-divider>
          </form>
        </div>
        <div id="data-list-thumb-view" class="w-full data-list-container">

          <vs-table class="w-full" ref="table" id="clientList" pagination :max-items="9" :data="clients">
            <template slot="thead">
              <vs-th>نشان نهاد</vs-th>
              <vs-th>نام نهاد</vs-th>
              <!-- <vs-th>ایمیل</vs-th> 
              <vs-th>جزییات</vs-th>-->
              <vs-th>بررسی</vs-th>
            </template>
            <template slot-scope="{data}">
              <tbody>
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td class="img-container">
                    <img :src="'/images/img/clients/'+tr.logo" class="product-img client_list_logo cursor-pointer" @click.stop="showClientData(tr.id)" />
                  </vs-td>
                  <vs-td>
                    <span v-text="tr.name" class="cursor-pointer" @click.stop="showClientData(tr.id)"></span>
                  </vs-td>
                  <!--   <vs-td>
                      <span v-text="tr.email"></span>
                    </vs-td>
                  <vs-td>
                    <span>
                      <vs-button type="border" icon="visibility" size="small" @click="(tr.id)" color="primary"></vs-button>
                    </span>
                  </vs-td> -->
                  <vs-td class="whitespace-no-wrap notupfromall">
                    <feather-icon v-if="user_permissions.includes('client_view')" icon="MoreVerticalIcon" svgClasses="w-6 h-6 hover:text-primary stroke-current" class="mr-2" @click.stop="showClientData(tr.id)" />
                    <feather-icon v-if="user_permissions.includes('client_edit')" icon="EditIcon" svgClasses="w-6 h-6 hover:text-primary stroke-current" class="mr-2" @click.stop="editData(tr)" />
                    <feather-icon v-if="user_permissions.includes('client_remove')" icon="TrashIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current" class="ml-2" @click.stop="deleteData(tr.id)" />
                  </vs-td>
                </vs-tr>
              </tbody>
            </template>
          </vs-table>
          <vs-popup class="holamundo" title="جزییات معلومات نهاد" :active.sync="popupActive">
            <div :key="indextr" v-for="(tr, indextr) in client">
              <div class="con-expand-clients w-full">
                <div class="con-btns-client flex items-center justify-between">
                  <div class="con-clientx flex items-center justify-start">
                    <vs-avatar size="60px" :src="'/images/img/clients/'+tr.logo" />
                    <span><strong class="client_label">{{ tr.name }}</strong></span>
                  </div>
                  <vs-divider></vs-divider>
                  <div class="flex">
                  </div>
                </div>
                <vs-list>
                  <vs-list-item icon-pack="feather" icon="icon-mail" color="success" :title="tr.email"></vs-list-item>
                  <vs-list-item icon-pack="feather" icon="icon-globe" :title="tr.website"></vs-list-item>
                  <vs-list-item icon-pack="feather" icon="icon-map-pin" :title="tr.address"></vs-list-item>
                  <vs-list-item icon-pack="feather" icon="icon-phone" class="ltr-number" :title="tr.phone"></vs-list-item>
                </vs-list>
              </div>
            </div>
          </vs-popup>
        </div>
      </vs-tab>
      <vs-tab v-if="user_permissions.includes('client_add')" label=" اضافه کردن نهاد جدید" icon="add" class="leftScrol">
        <div class="scroll-area--data-list-add-new" :is="scrollbarTag" :key="$vs.rtl">
          <form data-vv-scope="clientForm">
            <div class="p-2">
              <!-- Product Image -->
              <template v-if="orgForm.logo">
                <div class="vx-row">
                  <!-- Image Container -->
                  <div class="img-container w-32 mx-auto flex items-center justify-center ml-5">
                    <img :src="orgForm.logo" alt="نشان نهاد" class="responsive">
                  </div>
                  <!-- Image upload Buttons -->
                  <div class="modify-img flex justify-between mt-5 pt-5 ml-4">
                    <div class="mr-5 pr-5">
                      <input type="file" class="hidden" ref="updateImgInput" @change="updateCurrImg" accept="image/*">
                      <vs-button class="" icon="edit" color="primary" type="border" @click="$refs.updateImgInput.click()">تبدیل نشان نهاد</vs-button>
                    </div>
                    <div class="mr-5 pr-5">
                      <vs-button icon="delete" type="border" color="warning" @click="orgForm.logo = null">حذف این نشان نهاد</vs-button>
                    </div>
                  </div>
                </div>
              </template>

              <vs-input name="name" autocomplete="off" autofill="off" v-validate="'required|min:3'" label="نام نهاد" class="mt-3 w-full" v-model="orgForm.name" />
              <span class="absolute text-danger alerttext">{{ errors.first('clientForm.name') }}</span>
              <!--<has-error :form="orgForm" field="name"></has-error>-->

              <vs-input name="email" autocomplete="off" autofill="off" v-validate="'required|email'" label="ایمیل" type="email" class="mt-4 w-full" v-model="orgForm.email" />
              <span class="absolute text-danger alerttext">{{ errors.first('clientForm.email') }}</span>
              <!--<has-error :form="orgForm" field="email"></has-error>-->

              <vs-input name="phone" autocomplete="off" autofill="off" v-validate="'required|min:3'" label=" شماره تماس " type="text" class="mt-4 w-full" v-model="orgForm.phone" />
              <span class="absolute text-danger alerttext">{{ errors.first('clientForm.phone') }}</span>
              <!--<has-error :form="orgForm" field="phone"></has-error>-->

              <vs-input name="websitee" autocomplete="off" autofill="off" v-validate="'required|min:2'" label="ویب سایت" type="text" class="mt-4 w-full" v-model="orgForm.website" />
              <span class="absolute text-danger alerttext">{{ errors.first('clientForm.websitee') }}</span>
              <!--<has-error :form="orgForm" field="website"></has-error>-->

              <vs-input name="address" autocomplete="off" autofill="off" v-validate="'required|min:3'" label=" آدرس" type="text" class="mt-4 w-full" v-model="orgForm.address" />
              <span class="absolute text-danger alerttext">{{ errors.first('clientForm.address') }}</span>
              <!--<has-error :form="orgForm" field="address"></has-error>-->
              <!-- Upload -->
              <!-- <vs-upload text="Upload Image" class="img-upload" ref="fileUpload" /> -->

              <div class="upload-img mt-3 float-left" v-if="!orgForm.logo">
                <input type="file" name="logo" class="hidden mt-4" ref="uploadImgInput" @change="updateCurrImg" accept="image/*">
                <vs-button icon="image" class="mt-4 mb-2" @click="$refs.uploadImgInput.click()">اپلود نشان نهاد</vs-button>
                <!--<has-error :form="orgForm" field="logo"></has-error> 
                <span class="absolute text-danger alerttext">{{ errors.first('clientForm.logo') }}</span>-->
              </div>
              <vs-divider></vs-divider>
              <div class="items-center p-2 mt-2 float-left">
                <vs-button type="border" color="success" :disabled="orgForm.busy" class="mr-6" @click="submitData" icon="save">ذخیره</vs-button>
              </div>
            </div>
            <!--<vs-list v-if="(errors.items.length > 0)">
              <vs-list-header color="danger" title="مشکلات"></vs-list-header>
              <div :key="indextr" v-for="(error, indextr) in errors.items">
                <vs-list-item icon="verified_user" style="color:red;" :subtitle="error.msg"></vs-list-item>
              </div>
              <vs-list-item title="" subtitle=""></vs-list-item> 
            </vs-list>-->
          </form>
          <br><br>
        </div>
      </vs-tab>
    </vs-tabs>
  </vs-sidebar>
</div>
</template>

<script>
import DataViewSidebar from './../DataViewSidebar.vue'
import moduleDataList from './../data-list/moduleDataList.js'
import TableLoading from './../../shared/TableLoading.vue'
import {
  Validator
} from 'vee-validate'
// import VuePerfectScrollbar from 'vue-perfect-scrollbar'
export default {
  props: {
    isSidebarActive: {
      type: Boolean,
      required: true
    },
    data: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      isdata: false,
      orgActiveForm: false,
      oldImage: true,
      logoToChange: null,
      popupActive: false,
      orgForm: new Form({
        name: '',
        email: '',
        phone: '',
        website: '',
        address: '',
        logo: null,
        account_id: null,
        user_id: localStorage.getItem('id'),

      }),
      orgFormEdit: new Form({
        name: '',
        email: '',
        phone: '',
        website: '',
        address: '',
        logo: null,
        account_id: null
      }),
      // kk
      // statusFa: {
      //   on_hold: 'درجریان',
      //   delivered: 'تکمیل',
      //   canceled: 'نا موفق',
      // },
      clients: [],
      client: [],
      itemsPerPage: 4,
      // isMounted: false,
      // addNewDataSidebar: false,
      // sidebarData: {},
      // ll
      // dataId: null,
      // dataName: '',
      // dataCategory: null,
      // dataOrder_status: 'pending',
      // dataPrice: 0,
      // settings: { // perfectscrollbar settings
      //   maxScrollbarLength: 60,
      //   wheelSpeed: .60
      // }
      dict: {
        custom: {
          name: {
            required: ' اسم نهاد الزامی میباشد.',
            min: 'اسم نهاد باید بیشتر از 2 حرف باشد.',
          },
          email: {
            required: 'ایمیل نهاد الزامی میباشد',
            email: 'ایمیل نهاد باید به فارمت ایمیل وارد گردد.'
          },
          phone: {
            required: 'شماره تماس الزامی میباشد',
            min: 'شماره تماس حد اقل 3 حرف باشد.'
          },
          websitee: {
            required: ' ویب سایت نهاد  الزامی میباشد',
            min: '  ویب سایت نهاد حداقل 2 حرف میباشد'
          },
          address: {
            required: 'آدرس نهاد الزامی میباشد',
            min: 'حداقل آدرس 3 حرف است'
          },
          // logo: { required: 'نشان الزامی میباشد' }
        }
      }
    }
  },
  components: {
    // DataViewSidebar,
    // VuePerfectScrollbar
    TableLoading,
    Validator
  },
  created() {
    // if (!moduleDataList.isRegistered) {
    //   this.$store.registerModule('dataList', moduleDataList)
    //   moduleDataList.isRegistered = true
    // }
    // this.$store.dispatch('dataList/fetchDataListItems'),
    Validator.localize('en', this.dict);
    this.getData();
  },

  watch: {
    isSidebarActive(val) {
      if (!val) return
      if (Object.entries(this.data).length === 0) {
        // this.initValues()
        // this.$validator.reset()
      } else {
        // const { category, id, img, name, order_status, price } = JSON.parse(JSON.stringify(this.data))
        // this.dataId = id
        // this.dataCategory = category
        // this.orgForm.logo = img
        // this.dataName = name
        // this.dataOrder_status = order_status
        // this.dataPrice = price
        // this.initValues()
      }
      // Object.entries(this.data).length === 0 ? this.initValues() : { this.dataId, this.dataName, this.dataCategory, this.dataOrder_status, this.dataPrice } = JSON.parse(JSON.stringify(this.data))
    }
  },
  computed: {
    isSidebarActiveLocal: {
      get() {
        return this.isSidebarActive
      },
      set(val) {
        if (!val) {
          this.$emit('closeSidebar')
          this.$emit('customEvent', this.clients.find(e => !!e))
        }
      }
    },
    // scrollbarTag() { return this.$store.getters.scrollbarTag },
    scrollbarTag() {
      return this.$store.state.is_touch_device ? 'div' : 'VuePerfectScrollbar'
    },

    // currentPage() {
    //   if (this.isMounted) {
    //     return this.$refs.table.currentx
    //   }
    //   return 0
    // },
    // clients() {
    //   return this.$store.state.dataList.clients
    // },
    queriedItems() {
      return this.$refs.table ? this.$refs.table.queriedResults.length : this.clients.length
    }
  },
  methods: {
    // validateClientAdd() {
    //   return new Promise((resolve, reject) => {
    //     this.$validator.validateAll('clientForm').then(result => {
    //       if (result) {
    //         resolve(true)
    //       } else {
    //         reject(alert('correct all values'))
    //       }
    //     })
    //   })
    // },
    printClients() {
      window.print();
    },
    getData() {
      this.$Progress.start()
      // this.$vs.loading()
      this.axios.get('/api/clients')
        .then((response) => {
          this.clients = response.data;
          this.$Progress.set(100);
          this.isdata = true;
          // document.getElementById("loading-bg").style.display = "none";;
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
        })
    },
    resetAllState() {
      this.oldImage == true;
      this.orgActiveForm = false
      this.orgFormEdit.reset();

    },
    deletOnChangeLogo() {
      this.logoToChange = null
      this.oldImage = true

    },
    showClientData(id) {
      // 
      this.$Progress.start()
      // this.$vs.loading()
      this.orgActiveForm = false;
      this.orgForm.get('/api/clients/' + id)
        .then((response) => {
          this.client = response.data
          this.$Progress.set(100)
          // document.getElementById("loading-bg").style.display = "none";;
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
          this.popupActive = true;
        })
    },
    updateData() {
      if (!(this.logoToChange == null)) {
        this.orgFormEdit.logo = this.logoToChange;
      }
      this.orgFormEdit.put('/api/clients/' + this.orgFormEdit.id)
        .then(({
          data
        }) => {
          // Finish the Progress Bar
          this.getData();
          this.resetAllState();
          // toast notification
          this.$vs.notify({
            title: 'موفقیت!',
            text: 'نهاد مذکور موفقانه آپدیت شد.',
            color: 'success',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
        });
    },
    // isFormValid() {

    // },
    submitData() {
      // 
      // 
      this.$validator.validateAll('clientForm').then(result => {
        if (result) {
          this.orgForm.post('/api/clients')
            .then(({
              data
            }) => {
              this.getData();
              this.$vs.notify({
                title: 'موفقیت!',
                text: 'نهاد موفقانه ثبت سیستم شد.',
                color: 'success',
                iconPack: 'feather',
                icon: 'icon-check',
                position: 'top-right'
              })
              this.orgForm.reset();
              this.$validator.reset();
            }).catch((errors) => {
              this.$Progress.set(100)
              this.$vs.notify({
                title: 'ناموفق!',
                text: 'لطفاً معلومات نهاد را چک کنید و دوباره امتحان کنید!',
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

      // this.$validator.validateAll().then(result => {
      //   if (result) {
      //     const obj = {
      //       id: this.dataId,
      //       name: this.dataName,
      //       img: this.orgForm.logo,
      //       category: this.dataCategory,
      //       order_status: this.dataOrder_status,
      //       price: this.dataPrice
      //     }

      //     if (this.dataId !== null && this.dataId >= 0) {
      //       this.$store.dispatch('dataList/updateItem', obj).catch(err => { console.error(err) })
      //     } else {
      //       delete obj.id
      //       obj.popularity = 0
      //       this.$store.dispatch('dataList/addItem', obj).catch(err => { console.error(err) })
      //     }

      //     this.$emit('closeSidebar')
      //     this.initValues()
      //   }
      // })
    },
    updateCurrImg(input) {
      if (input.target.files && input.target.files[0]) {
        // this.oldImage = false
        const reader = new FileReader()
        reader.onload = e => {
          this.orgForm.logo = e.target.result
          // this.logoToChange = e.target.result
        }
        reader.readAsDataURL(input.target.files[0])
      }
    },
    updateCurrImg1(input) {
      if (input.target.files && input.target.files[0]) {
        this.oldImage = false
        const reader = new FileReader()
        reader.onload = e => {
          // this.orgForm.logo = e.target.result
          this.logoToChange = e.target.result
        }
        reader.readAsDataURL(input.target.files[0])
      }
    },
    goTo(data) {
      this.$router.push({
        path: '/projects/project/${data.id}',
        name: 'project-view',
        params: {
          id: data.id,
          dyTitle: data.name
        },
      }).catch(() => {})
    },
    viewProject(id) {
      // Vue.$forceUpdate();
      this.$router.push('/projects/project/' + id).catch(() => {})
    },
    // End Custom
    // addNewData() {
    //   this.sidebarData = {}
    //   this.toggleDataSidebar(true)
    // },
    deleteData(id) {
      // this.$store.dispatch('dataList/removeItem', id).catch(err => { console.error(err) })
      this.orgActiveForm = false;
      swal.fire({
        title: 'آیا مطمئن هستید؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'rgb(54 34 119)',
        cancelButtonColor: 'rgb(229 83 85)',
        confirmButtonText: '<span>بله، حذف شود!</span>',
        cancelButtonText: '<span>خیر، لغو عملیه!</span>'
      }).then((result) => {
        if (result.isConfirmed) {
          this.orgForm.delete('/api/clients/' + id).then((id) => {
              swal.fire({
                title: 'عملیه حذف موفقانه انجام شد.',
                icon: 'success',
              })
              this.getData();
              this.resetAllState();
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
    editData(data) {
      // this.sidebarData = JSON.parse(JSON.stringify(this.blankData))
      // this.sidebarData = data
      // this.toggleDataSidebar(true)
      this.orgActiveForm = true;
      this.orgFormEdit.name = data.name;
      this.orgFormEdit.email = data.email;
      this.orgFormEdit.phone = data.phone;
      this.orgFormEdit.website = data.website;
      this.orgFormEdit.address = data.address;
      this.orgFormEdit.logo = data.logo;
      this.orgFormEdit.account_id = data.account_id;
      this.orgFormEdit.id = data.id;

    },
    getOrderStatusColor(status) {
      if (status === 'on_hold') return 'warning'
      if (status === 'delivered') return 'success'
      if (status === 'canceled') return 'danger'
      return 'primary'
    },
    getPopularityColor(num) {
      if (num > 90) return 'success'
      if (num > 70) return 'primary'
      if (num >= 50) return 'warning'
      if (num < 50) return 'danger'
      return 'primary'
    },
    // toggleDataSidebar(val = false) {
    //   this.addNewDataSidebar = val
    // },
  },
  mounted() {
    this.isMounted = false
  },
}
</script>

<style>
#printBTN {
  float: right !important;
  margin: 10px;
}

.leftScrol {
  height: 80vh;
  overflow-y: scroll;
}
</style><style lang="scss" scoped>
.add-new-data-sidebar {
  ::v-deep .vs-sidebar--background {
    z-index: 52010;
  }

  ::v-deep .vs-sidebar {
    z-index: 52010;
    width: 500px;
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
  // height: calc(var(--vh, 1vh) * 100 - 16px - 45px - 82px);

  // &:not(.ps) {
  //   overflow-y: auto;
  // }
}
</style><style lang="scss">
#data-list-thumb-view {
  .vs-con-table {
    // .product-name {
    //   max-width: 23rem;
    // }

    .vs-table--header {
      display: flex;
      flex-wrap: wrap-reverse;
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
      padding: 0 0.6rem;

      tr {
        box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);

        td {
          padding: 0px !important;

          &:first-child {
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
          }

          &:last-child {
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
          }

          &.img-container {
            // width: 1rem;
            // background: #fff;

            span {
              display: flex;
              justify-content: flex-start;
            }

            .product-img {
              height: 70px;
              padding-left: 10px;
            }
          }
        }

        td.td-check {
          padding: 10px !important;
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
</style>
