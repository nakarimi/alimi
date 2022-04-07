<template>
<div class="notifications">
  <vs-tabs>
    <vs-tab :label="$t('Important')">
      <div class="vx-row" :key="importantNotifKey">
        <!-- TITLE COLOR -->
        <div v-for="item in importnatNotif" :key="item.id" class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/3 xl:w-1/3 mb-base">
          <vx-card :title="item.title" :title-color="item.type" :subtitle="item.subtitle" :time="moment(item.created_at).fromNow()" :content-color="item.contentColor" :subtitle-color="item.subtitleColor" :card-background="item.cardBackground">
            <span class="item-time-ago cursor-pointer">
              <vs-button radius color="warning" size="small" title="پاک کردن از مهمترین ها" svgClasses="w-2 h-3 hover:text-danger" type="border" icon="close" style="margin-right:7px;" @click="removeFromImportant(item.id)"></vs-button>&nbsp;&nbsp;
              <vs-button v-if="item.user_notification.pin == 0" title="سنجاق به بالا " radius color="#7367F0" size="small" svgClasses="w-2 h-3 hover:text-danger" type="border" icon="bookmark" @click="addToPins(item.id, false)"></vs-button>
              <vs-button v-if="item.user_notification.pin == 1" title="برداشتن از بالا" radius color="success" size="small" svgClasses="w-2 h-3 hover:text-danger" type="border" icon="bookmark" @click="addToPins(item.id, true)"></vs-button>
            </span>
            <p class="mb-3 notification-desc" style=" -webkit-box-orient: vertical; ">{{item.text}}</p>
            <div slot="footer">
              <vs-row>
                <vs-button @click="notifActionCenter(item)" color="primary" size="small" class="pull-right" :title="'notif_' + item.notif_number">{{ $t('notif_' + item.notif_number) }}</vs-button>
              </vs-row>
            </div>
          </vx-card>
        </div>
      </div>
    </vs-tab>
    <vs-tab :label="$t('All')">
      <div class="vx-row" :key="standardNotifKey">
        <div v-for="item in allNotif.slice((currentPage - 1) * item_per_page , currentPage * item_per_page)" :key="item.id" class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/3 xl:w-1/3 mb-base cursor-pointer">
          <vx-card :title="item.title" :title-color="item.type" :subtitle="item.subtitle" :time="moment(item.created_at).fromNow()" :content-color="item.contentColor" :subtitle-color="item.subtitleColor" :card-background="item.cardBackground">
            <span class="item-time-ago" :style="'color:' + item.titleColor">
              <vs-button radius color="primary" size="small" title="حذف کامل" svgClasses="w-2 h-3 hover:text-danger" type="border" icon="close" style="margin-right:7px;" @click="removeFromUser(item.id)"></vs-button>&nbsp;&nbsp;
              <vs-button v-if="item.user_notification.pin == 0" title="سنجاق به بالا " radius color="#7367F0" size="small" svgClasses="w-2 h-3 hover:text-danger" type="border" icon="bookmark" @click="addToPins(item.id, false)"></vs-button>
              <vs-button v-if="item.user_notification.pin == 1" title="برداشتن از بالا" radius color="success" size="small" svgClasses="w-2 h-3 hover:text-danger" type="border" icon="bookmark" @click="addToPins(item.id, true)"></vs-button>
            </span>
            <p class="mb-3 notification-desc" style=" -webkit-box-orient: vertical; ">{{item.text}}</p>
            <div slot="footer">
              <vs-row>
                <vs-button @click="notifActionCenter(item)" color="primary" size="small" class="pull-right" :title="'notif_' + item.notif_number">{{ $t('notif_' + item.notif_number) }}</vs-button>
              </vs-row>
            </div>
          </vx-card>
        </div>
      </div>
      <vs-pagination :total="Math.ceil(allNotif.length/item_per_page)" max="7" v-model="currentPage" @input="changePageNotif" class="mt-base"></vs-pagination>
    </vs-tab>
  </vs-tabs>
  <vs-popup class="holamundo" :title="vsPopupTitle" :active.sync="popupStepActiveProposal">
    <ProposalSteps @closesteps="closeModel" ref="wizardModal1" :participators="participators" :proposal="proposal"></ProposalSteps>
  </vs-popup>
  <vs-popup class="holamundo" :title="vsPopupTitle" :active.sync="popupStepActiveProject">
    <ProjectSteps @closesteps="closeModel" ref="wizardModal" :project="project"></ProjectSteps>
  </vs-popup>

</div>
</template>

<script>
import {
  AirplayIcon
} from 'vue-feather-icons'
import ProposalSteps from "./apps/projects/proposals/ProposalSteps"
import ProjectSteps from "./apps/projects/ProjectSteps"

export default {
  data() {
    return {
      range: [],
      item_per_page: 15,
      popupStepActiveProposal: false,
      popupStepActiveProject: false,
      vsPopupTitle: '',
      importnatNotif: [],
      allNotif: [],
      removeFromI: false,
      importantNotifKey: 0,
      standardNotifKey: 0,
      proposal: null,
      project: null,
      participators: [],
      proposalstep: [],
      currentPage: 1,
      proposalsteps: new Form({
        step: 0,
        proposal_id: 0,
        res_person: '',
        is_recieved_cont: 0,
        is_participated: 0,
        prop_recieved_or_allow: 0,
        winner: ''
      }),

    }
  },
  components: {
    AirplayIcon,
    ProposalSteps,
    ProjectSteps
  },
  created() {
    // this.$vs.loading();
    this.getAllNotification();
  },
  methods: {
    changePageNotif(val) {
      this.range = [(val - 1) * item_per_page, val * item_per_page];
    },
    closeModel() {
      this.getAllNotification();
      this.popupStepActiveProposal = false;
      this.popupStepActiveProject = false;
    },
    addToPins(id, unpin = false) {
      this.axios.post('/api/notif/pin', {
          notif_id: id,
          unpin: unpin
        })
        .then((response) => {
          this.getAllNotification();
        })
    },
    getAllNotification() {
      this.axios.get('/api/notification')
        .then((response) => {
          this.allNotif[0] = Object.values(response.data).filter(i => (i.user_notification) ? i.user_notification.pin != 0 : false)
          this.allNotif[1] = Object.values(response.data).filter(i => (i.user_notification) ? i.user_notification.pin != 1 : false)
          this.importnatNotif[0] = Object.values(this.allNotif[0]).filter(i => (i.user_notification) ? i.user_notification.status != 'not_im' : false)
          this.importnatNotif[1] = Object.values(this.allNotif[1]).filter(i => (i.user_notification) ? i.user_notification.status != 'not_im' : false)
          this.importnatNotif = this.importnatNotif[0].concat(this.importnatNotif[1]);
          this.allNotif = this.allNotif[0].concat(this.allNotif[1]);
          this.importantNotifKey += 1;
          this.standardNotifKey += 1;
          // document.getElementById("loading-bg").style.display = "none";;
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
        })
        .catch((errors) => {
          // document.getElementById("loading-bg").style.display = "none";;
          const appLoading = document.getElementById("loading-bg");
          if (appLoading) {
            appLoading.style.display = "none";
          }
        })

    },
    removeFromImportant(id) {
      swal.fire({
        title: 'مطمئن هستید ؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'rgb(54 34 119)',
        cancelButtonColor: 'rgb(229 83 85)',
        confirmButtonText: '<span>بله !</span>',
        cancelButtonText: '<span>خیر !</span>'
      }).then((result) => {
        if (result.isConfirmed) {
          this.$Progress.start()
          this.axios.post('/api/notif/no-important', {
              notif_id: id
            })
            .then(({
              data
            }) => {
              this.getAllNotification();
              this.$Progress.set(100)
              this.$vs.notify({
                title: 'موفقیت!',
                text: 'اعلامیه مذکور به لست عمومی انتقال نمود!.',
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
        }
      })
    },
    removeFromUser(id) {
      swal.fire({
        title: 'مطمئن هستید ؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'rgb(54 34 119)',
        cancelButtonColor: 'rgb(229 83 85)',
        confirmButtonText: '<span>بله !</span>',
        cancelButtonText: '<span>خیر !</span>'
      }).then((result) => {
        if (result.isConfirmed) {
          this.$Progress.start()
          this.axios.post('/api/notif/remove-notif-user', {
              notif_id: id
            })
            .then(({
              data
            }) => {
              this.getAllNotification();
              this.$Progress.set(100)
              this.$vs.notify({
                title: 'موفقیت!',
                text: 'اعلامیه مذکور از لیست شما حذف شد!.',
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
        }
      })
    },
    notifActionCenter(item) {
      if (item.action == 'btn-link') {
        this.$router.push(item.url).catch(() => {})
      } else if (item.action == 'btn-modal') {
        this.vsPopupTitle = item.title;
        if (item.notif_source == 'offer') {
          this.showCheckModalProposal(item.notif_source_id);
        } else if (item.notif_source == 'project') {
          this.showStepsModalProject(item.notif_source_id);
        }
      }
    },
    showCheckModalProposal(id) {
      this.proposalstep = [];
      this.getProposal(id);
      this.getParticipators(id);
      this.getProposlStep(id);
      this.popupStepActiveProposal = true;
    },
    getProposlStep(id) {
      this.$Progress.start()
      this.axios.get('/api/proposalstep/' + id)
        .then((response) => {
          this.proposalstep = response.data;
          if (response.data.status == 'no') {
            this.$refs.wizardModal1.setWizardStep1(1, this.proposalsteps);
          } else {
            this.proposalstep.forEach(item => {
              this.$refs.wizardModal1.setWizardStep1(item.step, item);
            })
          }
          this.$Progress.set(100);
        }).catch(() => {
          document.getElementById("loading-bg").style.display = "none";
        });
    },
    getProposal(id) {
      this.axios
        .get("/api/proposal/" + id)
        .then((response) => {
          this.proposal = [];
          this.proposal = response.data;
        })
        .catch(() => {
          document.getElementById("loading-bg").style.display = "none";
        });
    },
    getParticipators(proposalid) {
      this.axios
        .get("/api/participators/" + proposalid)
        .then((response) => {
          this.participators = [];
          this.participators = response.data;
        })
        .catch(() => {
          document.getElementById("loading-bg").style.display = "none";
        });
    },
    showStepsModalProject(id) {
      this.project = [];
      this.getThisProject(id);
      this.getProjectStep(id);
      this.popupStepActiveProject = true;
    },
    getProjectStep(id) {
      this.$Progress.start()
      this.axios.get('/api/projectstep/' + id)
        .then((response) => {
          this.projectstep = response.data;
          if (response.data.status == 'no') {
            this.$refs.wizardModal.setWizardStep(1, this.projectsteps);
          } else {
            this.projectstep.forEach(item => {
              this.$refs.wizardModal.setWizardStep(item.step, item);
            })
          }
          this.$Progress.set(100);
        }).catch(() => {
          document.getElementById("loading-bg").style.display = "none";
        });
    },
    getThisProject(id) {
      this.$Progress.start()
      this.axios.get('/api/project/' + id)
        .then((response) => {
          this.project = [];
          this.project = response.data;
          this.$Progress.set(100);
        }).catch(() => {
          document.getElementById("loading-bg").style.display = "none";
        });
    },

  }
}
</script>

<style>
.item-time-ago {
  position: absolute;
  top: -45px;
  color: rgba(var(--vs-primary), 1);
}

[dir] .vs-button.includeIcon {
  float: left;
}
</style>
