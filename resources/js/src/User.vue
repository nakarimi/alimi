<template>

</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      currentuserdata: [],
    }
  },

  created() {
    this.loadcurrentuser();
  },
  methods: {
    loadcurrentuser() {
      this.axios.get('/api/user')
        .then((response) => {
          this.currentuserdata = response.data
          localStorage.setItem('name', this.currentuserdata.firstName)
          localStorage.setItem('lastname', this.currentuserdata.lastName)
          localStorage.setItem('position', this.currentuserdata.position)
          localStorage.setItem('image', this.currentuserdata.image)
          localStorage.setItem('id', this.currentuserdata.id)
        }).catch(() => {
          localStorage.removeItem('token');
          localStorage.removeItem('name');
          localStorage.removeItem('lastname');
          localStorage.removeItem('position');
          localStorage.removeItem('image');
          localStorage.removeItem('id');
          localStorage.removeItem('user_permissions');

          window.location.replace("/login");
          this.$vs.notify({
            title: ' شما به سیستم دسترسی ندارید!',
            text: 'عملیه ناکام شد لطفا دوباره تلاش نماید',
            color: 'danger',
            iconPack: 'feather',
            icon: 'icon-check',
            position: 'top-right'
          })
        })
    },
  },

}
</script>
