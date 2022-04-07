<template>
<div>
  <Clients :isSidebarActive="addNewDataSidebar" @closeSidebar="toggleDataSidebar" :data="sidebarData" />
  <div class="vx-row">
    <vx-card>
      <div class="vx-row">
        <div class="vx-col w-1/2">
          <h3>فورم ویرایش پروژه قرار دادی</h3>
        </div>
      </div>
      <project-form></project-form>
    </vx-card>
  </div>
</div>
</template>

<script>
import vSelect from "vue-select";
import Clients from "./proposals/Clients.vue";
import ProjectList from "./ProjectList.vue";
import ProjectForm from "./ProjectForm.vue";

export default {
  components: {
    Clients,
    ProjectList,
    ProjectForm,
    "v-select": vSelect,
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      // init values
      announces: [],
      org: [],
      selectedAnnounce: null,
      selectedOrg: null,

      // Data Sidebar
      addNewDataSidebar: false,
      sidebarData: {},
    };
  },
  created() {
  },
  methods: {
    addNewData() {
      this.sidebarData = {};
      this.toggleDataSidebar(true);
    },
    toggleDataSidebar(val = false) {
      this.addNewDataSidebar = val;
    },
    // End Custom
    addNewData() {
      this.sidebarData = {};
      this.toggleDataSidebar(true);
    },
    toggleDataSidebar(val = false) {
      this.addNewDataSidebar = val;
    },
  },
};
</script>

<style lang="scss">

</style>
