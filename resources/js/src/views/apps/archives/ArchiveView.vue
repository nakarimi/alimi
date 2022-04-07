<template>
<div v-if="archive">
  <!-- <vs-button size="small" type="gradient" icon="print" class="mb-1" id="printBTN">چاپ</vs-button> -->
  <vs-row vs-w="12" class="project-view-header">
    <vs-col vs-type="flex" vs-justify="right" vs-align="right" vs-lg="12" vs-sm="12" vs-xs="12">
      <h4>&nbsp;لست فایل های آرشیف&nbsp;</h4>
    </vs-col>
  </vs-row>
  <!-- <vs-row vs-w="12">
    <vs-col :key="i" v-for="(field, i) in fields_toShow" vs-type="flex" vs-justify="right" vs-align="right" vs-lg="4" vs-sm="6" vs-xs="12">
      <h6 class="mb-5 mt-3 ml-2">
        <strong class="mr-4">
          {{ (typeof field === 'object') ? $t(field[0]) : $t(field) }}:
        </strong>
        <small class="mb-5" v-if="(typeof field !== 'object')">{{ archive['files'][field] }}</small>
        <small class="mb-5" v-if="(typeof field === 'object')">
          <span :key="i" v-for="(sub, i) in field[1]"> {{ archive['files'][field[0]][sub] }} </span>
        </small>
      </h6>
    </vs-col>
  </vs-row> -->
  <vx-card v-if="archive['files']">
    <vs-table max-items="7" pagination :data="archive['files']" stripe class="archive_view_files">
      <template slot="thead">
        <vs-th>نام اصلی</vs-th>
        <vs-th>نام سیستم</vs-th>
        <vs-th>نوعیت</vs-th>
        <!-- <vs-th>کپشن</vs-th> -->
        <vs-th>لینک دانلود</vs-th>
      </template>
      <template slot-scope="{ data }">
        <vs-tr :key="indextr" v-for="(tr, indextr) in data">
          <vs-td>{{ tr.origname }}</vs-td>
          <vs-td><p class="number-rtl-view">{{ tr.newname }}</p></vs-td>
          <vs-td><p class="number-rtl-view">{{ tr.mime }}</p></vs-td>
          <!-- <vs-td>{{ tr.caption }}</vs-td> -->
          <vs-td>
            <p class="cursor-pointer" @click="doawnloadThisFile(tr.id)">
              <vs-button radius color="primary" type="flat" icon-pack="feather" icon="icon icon-download"></vs-button>
            </p>

          </vs-td>
        </vs-tr>
      </template>
    </vs-table>
  </vx-card>
</div>
</template>

<script>
export default {
  props: {
    archive: {
      required: false
    },
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      fields_toShow: [
        'origname',
        'path',
        'newname',
        'mime',
        'caption',
      ],
    };
  },
  created() {
    this.getTransaction();
  },
  methods: {
    doawnloadThisFile(file_id) {
      this.axios.get(`/api/archive/download-file/${file_id}`)
        .then((response) => {
          window.open(`/api/archive/download-file/${file_id}/get`, '_blank');
          // this.axios.get(`/api/archive/download-file/${file_id}/get`);
        }).catch((errors) => {
          swal.fire({
            icon: "error",
            title: "خطا در دانلود",
            text: "فایل مورد نظر در سیستم موجود نمی باشد.",
          });

        });
    },
    getTransaction() {
      if (this.$route.params.id) {
        this.$Progress.start()
        // this.$vs.loading()
        this.axios.get('/api/archive/' + this.$route.params.id)
          .then((response) => {
            this.archive = response.data;
            // document.getElementById("loading-bg").style.display = "none";;
const appLoading = document.getElementById("loading-bg");
if (appLoading) {
  appLoading.style.display = "none";
}
            this.$Progress.set(100)
          })
      }
    },
  },
};
</script>
