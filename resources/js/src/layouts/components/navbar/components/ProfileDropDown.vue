<template>
<div class="the-navbar__user-meta flex items-center" v-if="activeUserInfo.displayName">

  <div class="text-right leading-tight hidden sm:block">
    <p class="font-semibold">{{firsname}} {{lastname}}</p>
    <!-- <p class="font-semibold">{{ activeUserInfo.displayName }}</p> -->
    <!--<small>{{$t('Available')}}</small> -->
    <small>{{position}}</small>
  </div>

  <vs-dropdown vs-custom-content vs-trigger-click class="cursor-pointer">

    <div class="con-img ml-3">
      <img id="con-img-profile" v-if="activeUserInfophotoURL" key="onlineImg" :src="'/img/user/'+image" width="40" height="40" class="rounded-full shadow-md cursor-pointer block" />
      <img v-if="!activeUserInfophotoURL" key="onlineImg" src="/img/default/navelogo.png" width="40" height="40" class="rounded-full shadow-md cursor-pointer block" />
    </div>

    <vs-dropdown-menu class="vx-navbar-dropdown">
      <ul style="min-width: 9rem">

        <!-- <li
            class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white"
            @click="$router.push('/pages/profile').catch(() => {})">
            <feather-icon icon="UserIcon" svgClasses="w-4 h-4" />
            <span class="ml-2">Profile</span>
          </li> -->


        <li class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white" @click="mailTo">
          <feather-icon icon="MailIcon" svgClasses="w-4 h-4" />
          <span class="ml-2">ایمیل</span>
        </li>
        <li class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white" @click="$router.push('/notifications').catch(() => {})">
          <feather-icon icon="BellIcon" svgClasses="w-4 h-4" />
          <span class="ml-2">{{$t('Notifications')}}</span>
        </li>

        <li class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white" @click="$router.push('/settings').catch(() => {})">
          <feather-icon icon="SettingsIcon" svgClasses="w-4 h-4" />
          <span class="ml-2">{{$t('Settings')}}</span>
        </li>

        <li class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white" @click="$router.push('/user/management').catch(() => {})">
          <feather-icon icon="UserIcon" svgClasses="w-4 h-4" />
          <span class="ml-2">تنظیمات کابر</span>
        </li>

        <vs-divider class="m-1" />

        <li class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white" @click="logout">
          <feather-icon icon="LogOutIcon" svgClasses="w-4 h-4" />
          <span class="ml-2">{{$t('Logout')}}</span>
        </li>
      </ul>
    </vs-dropdown-menu>
  </vs-dropdown>
</div>
</template>

<script>
import 'firebase/auth'

export default {
  data() {
    return {
      firsname: localStorage.getItem('name'),
      lastname: localStorage.getItem('lastname'),
      image: localStorage.getItem('image'),
      position: localStorage.getItem('position'),
      activeUserInfophotoURL: true
    }
  },
  created() {
    if(document.getElementById('con-img-profile') && document.getElementById('con-img-profile').naturalHeight == 0){
      this.activeUserInfophotoURL = false
    }
  },
  computed: {
    activeUserInfo() {
      return this.$store.state.AppActiveUser
    }
  },
  methods: {
    mailTo() {
      window.location.href = 'mailto:a@gmail.com';
    },
    logout() {
          localStorage.removeItem('token');
          localStorage.removeItem('name');
          localStorage.removeItem('lastname');
          localStorage.removeItem('position');
          localStorage.removeItem('image');
          localStorage.removeItem('id');
          localStorage.removeItem('user_permissions');
          this.$vs.notify({
            title: 'عملیه خروج موفق بود!',
            text: 'عملیه موفقانه انجام شد',
            color: 'success',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
        })
        window.location.href = '/login';
    }
  }
}
</script>
