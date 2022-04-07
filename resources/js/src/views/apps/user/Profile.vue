<template>
  <vs-tabs>
      <!-- <vs-tab label="پروفایل">
        <profile-details />
      </vs-tab> -->
      <vs-tab v-if="user_permissions.includes('user_view')" label="لیست کاربران">
        <user-list />
      </vs-tab>
      <vs-tab v-if="user_permissions.includes('user_add')" label="ثبت کاربر">
        <register-user />
      </vs-tab>
    </vs-tabs>
</template>
<script>

import ProfileDetails from './ProfileDetails'
import RegisterUser from './sub/RegisterUser'
import UserList from './sub/UserList'
export default {
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
    }
  },
  components:{
    ProfileDetails,
    RegisterUser,
    UserList
  }
}
</script>