<template>
<form-wizard color="rgba(var(--vs-primary), 1)" :title="null" :subtitle="null" ref="wizardtranf" @on-complete="formSubmitted">
  <tab-content title="تنظیم اطلاعات" class="mb-5" :before-change="changeStepStatus">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>تنظیم اطلاعات</vs-divider>
      </vs-row>
      <vs-row vs-w="12">
        <div>
          <p>عنوان: {{transfer.title }}</p>
        </div>
      </vs-row>
    </vs-row>
  </tab-content>
  <tab-content title="بارگیری اقلام" class="mb-5" :before-change="changeStepStatus1">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>بارگیری اقلام</vs-divider>
      </vs-row>
      <vs-row vs-w="12">
      </vs-row>
    </vs-row>
  </tab-content>
  <tab-content title="دریافت اقلام" class="mb-5" :before-change="changeStepStatus2">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>دریافت اقلام</vs-divider>
      </vs-row>
      <vs-row vs-w="12">
      </vs-row>
    </vs-row>
  </tab-content>
  <tab-content title="اطلاعات مالی" class="mb-5" :before-change="changeStepStatus3">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>اطلاعات مالی</vs-divider>
      </vs-row>
      <vs-row vs-w="12">
      </vs-row>
    </vs-row>
  </tab-content>
  <tab-content title="تاییدی" class="mb-5">
    <vs-row vs-w="12" class="mb-1">
      <vs-row vs-w="12">
        <vs-divider>تاییدی</vs-divider>
      </vs-row>
      <vs-row vs-w="12">
      </vs-row>
    </vs-row>
  </tab-content>

  <vs-button slot="prev">قبلی</vs-button>
  <vs-button slot="next">بعدی</vs-button>
  <vs-button slot="finish">بستن صفحه</vs-button>
</form-wizard>
</template>

<script>
import {
  FormWizard,
  TabContent
} from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
export default {
  props: ['transfer'],
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      step: 0
    }
  },
  components: {
    FormWizard,
    TabContent
  },
  methods: {
    formSubmitted() {
      this.$emit('closesteps');
    },
    setWizardStepTransf(index) {
      this.step = index;
      this.$refs.wizardtranf.activateAll();
      this.$refs.wizardtranf.navigateToTab(index - 1);
    },
    changeItTo(id, st) {
      this.$Progress.start()
      this.axios
        .get('/api/tranfchange/' + id + '/' + st)
        .then((response) => {
          this.$Progress.set(100);
          this.$vs.notify({
            title: 'موفقیت!',
            text: ' مرحله موفقانه به ' + st + ' تغیر یافت.',
            color: 'success',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
        }).catch(() => {document.getElementById("loading-bg").style.display = "none";});
    },
    changeStepStatus() {
      if (this.step == 1) {
        this.step = 2;
        this.changeItTo(this.transfer.id, this.step)
      }
      return true;
    },
    changeStepStatus1() {
      if (this.step == 2) {
        this.step = 3;
        this.changeItTo(this.transfer.id, this.step)
      }
      return true;
    },
    changeStepStatus2() {
      if (this.step == 3) {
        this.step = 4;
        this.changeItTo(this.transfer.id, this.step)
      }
      return true;
    },
    changeStepStatus3() {
      if (this.step == 4) {
        this.step = 5;
        this.changeItTo(this.transfer.id, this.step)
      }
      return true;
    },

  }
}
</script>
