<template>
<vs-sidebar position-right parent="#app" default-index="1" color="primary" class="add-new-data-sidebar transfer-sidebar items-no-padding" spacer v-model="isSidebarActiveLocal">
  <div class="mt-6 flex items-center justify-between px-6 float-right">
    <feather-icon icon="XIcon" @click.stop="$emit('closeSidebar')" class="cursor-pointer"></feather-icon>
  </div>

  <vs-tabs :key="transferKey" :value="tabNumber">
    <vs-tab v-if="user_permissions.includes('transfer_view')" label="انتقالات">
      <all-transfer @loadEditAction="loadEditData"></all-transfer>
    </vs-tab>
    <vs-tab v-if="user_permissions.includes('transfer_add')" label="ثبت انتقال">
      <AddNewTransfer></AddNewTransfer>
    </vs-tab>
    <vs-tab v-if="tabNumber == 2" label="ویرایش انتقال">
      <AddNewTransfer @editCompleted="editCompleted" :entityData="transferEditData"></AddNewTransfer>
    </vs-tab>
  </vs-tabs>
</vs-sidebar>
</template>

<script>
import vSelect from 'vue-select'
import AllTransfer from './add/AllTransfer';
import AddNewTransfer from './add/AddNewTransfer';

export default {
  props: {
    isSidebarActive: {
      type: Boolean,
      required: true
    },
    data: {
      type: Object,
      default: () => {}
    },
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      transferEditData: null,
      transferKey: 0,
      tabNumber: 0,
    }
  },
  watch: {
    'transferKey' () {
      console.log(this.transferKey);
    }
  },
  components: {
    'v-select': vSelect,
    AllTransfer,
    AddNewTransfer,
  },
  methods: {
    editCompleted() {
      this.transferKey +=1;
      this.tabNumber = 0;
    },
    loadEditData(data){
      this.tabNumber = 2;
      this.transferEditData = data;
      this.transferKey +=1;
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
        }
      }
    },
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
  height: calc(var(--vh, 1vh) * 100 - 16px - 45px - 82px);

  &:not(.ps) {
    overflow-y: auto;
  }
}
</style>
