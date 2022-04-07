<template>
<component :is="scrollbarTag" class="scroll-area--data-list-add-new" :key="$vs.rtl">

  <div id="data-list-thumb-view" class="w-full data-list-container">
    <div v-if="!isloaded">
      <TableLoading></TableLoading>
    </div>
    <vs-table v-if="isloaded" ref="table" pagination :max-items="itemsPerPage" :data="transfers">
      <template slot="thead">
        <vs-th>شماره سریال</vs-th>
        <vs-th>عنوان</vs-th>
        <vs-th>قیمت مجموعی</vs-th>
        <vs-th>مسول</vs-th>
        <vs-th>بررسی</vs-th>
      </template>

      <template slot-scope="{data}">
        <tbody>
          <vs-tr :key="i" v-for="(tr, i) in data">

            <vs-td :data="data[i].serial_no">
              {{ data[i].serial_no }}
            </vs-td>

            <vs-td :data="data[i].title">
              <p>{{ data[i].title }}</p>
            </vs-td>

            <vs-td :data="data[i].total">
              {{ data[i].total }} افغانی
            </vs-td>

            <vs-td :data="data[i].supervisor" class="just-one-line">
              <p>{{ data[i].supervisor }}</p>
            </vs-td>
            <vs-td>
              <div class="flex">
                <feather-icon v-if="user_permissions.includes('transfer_steps')" icon="CheckSquareIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current cursor-pointer" class="ml-2" @click.stop="showStepsModal(tr.id)" />&nbsp;&nbsp;
                <feather-icon v-if="user_permissions.includes('transfer_edit')" icon="EditIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current cursor-pointer" class="ml-2" @click.stop="$emit('loadEditAction', tr)" />
                <feather-icon v-if="user_permissions.includes('transfer_remove')" icon="TrashIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current cursor-pointer" class="ml-2" @click.stop="deleteData(tr.id)" />
              </div>
            </vs-td>
          </vs-tr>
        </tbody>
      </template>
    </vs-table>
    <vs-popup class="holamundo" title="تنظیمات مربوط به انتقالات " :active.sync="popupStepActive">
      <TransferStep @closesteps="closeModel" ref="wizardModalTransf" :transfer="transfer"></TransferStep>
    </vs-popup>
  </div>
</component>
</template>

<script>
import TableLoading from './../../shared/TableLoading.vue'
import TransferStep from './TransferStep.vue'
export default {
  components: {
    TableLoading,
    TransferStep
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      popupStepActive: false,
      transfer: [],
      transfers: [],
      itemsPerPage: 5,
      isloaded: false,
      isloadedrow: false,
      settings: { // perfectscrollbar settings
        maxScrollbarLength: 60,
        wheelSpeed: .60
      }
    }
  },
  created() {
    this.getTransfers();
  },
  methods: {
    closeModel() {
      this.popupStepActive = false;
    },
    getTransfer(id) {
      this.$Progress.start()
      this.axios
        .get("/api/transfer/" + id)
        .then((data) => {
          this.transfer = data.data;
          this.$refs.wizardModalTransf.setWizardStepTransf(this.transfer.step);
          this.$Progress.set(100);
        })
        .catch(() => {
          document.getElementById("loading-bg").style.display = "none";
        });
    },
    showStepsModal(id) {
      this.getTransfer(id)
      this.popupStepActive = true;
    },

    getTransfers() {
      this.axios
        .get("/api/transfer")
        .then((data) => {
          this.transfers = data.data;
          // Finish the Progress Bar
          this.isloaded = true;
          this.$Progress.set(100);
        })
        .catch(() => {
          document.getElementById("loading-bg").style.display = "none";
        });
    },
    deleteData(id) {
      swal.fire({
        title: 'آیا  مطمئن هستید؟',
        text: "ریکارد انتقال حذف خواهد شد",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'rgb(54 34 119)',
        cancelButtonColor: 'rgb(229 83 85)',
        confirmButtonText: '<span>بله، حذف شود!</span>',
        cancelButtonText: '<span>خیر، لغو عملیه!</span>'
      }).then((result) => {
        if (result.isConfirmed) {
          this.axios.delete('/api/transfer/' + id).then((id) => {
              // 
              swal.fire({
                title: 'عملیه موفقانه انجام شد.',
                text: "مورد انتقال از سیستم پاک شد!",
                icon: 'success',
              })
              this.getTransfers();
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
  },

  computed: {
    scrollbarTag() {
      return this.$store.getters.scrollbarTag
    },
    currentPage() {
      if (this.isMounted) {
        return this.$refs.table.currentx
      }
      return 0
    },
    queriedItems() {
      return this.$refs.table ? this.$refs.table.queriedResults.length : this.transfers.length
    }

  },
}
</script>
