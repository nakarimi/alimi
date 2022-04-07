<template>
  <vs-sidebar
    class="items-no-padding"
    parent="#inventory-app"
    id="inventory-list-sidebar"
    :click-not-close="clickNotClose"
    :hidden-background="clickNotClose"
    v-model="isSidebarActive"
  >
  </vs-sidebar>
</template>
<script>
export default {
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      active: true,
      isHidden: false,
      searchContact: '',
      activeProfileSidebar: false,
      activeChatUser: null,
      userProfileId: -1,
      typedMessage: '',
      isChatPinned: false,
      settings: {
        maxScrollbarLength: 60,
        wheelSpeed: 0.70
      },
      clickNotClose: false,
      isSidebarActive: false,
      isLoggedInUserProfileView: false
    }
  },
  methods:{
    toggleSidebar(){
      this.isSidebarActive = !this.isSidebarActive;
    }
  }
}
</script>
