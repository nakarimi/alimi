<template>
<div>
  <div slot="header"><span>صلاحیت های کاربر را انتخاب کنید</span>
    <span class="float-right">
      <vs-button @click="setAllPrivileges(checkAll)" color="danger">
        {{ (checkAll)? 'دادن همه صلاحیت ها':'گرفتن همه صلاحیت ها' }}
      </vs-button>
    </span>
  </div>
  <vs-row class="pt-5 pb-5">
    <ul class="demo-alignment flex space-between" :key="listKey">
      <li v-for="(n, index) in privileges" :key="index" @change="checkedMain(index)" class="sm:w-1 md:w-1/4 lg:w-1/4 xl:w-1/4 pt-3 pb-3">
        <div class="vs-component con-vs-checkbox vs-checkbox-success vs-checkbox-default">
          <input type="checkbox" class="vs-checkbox--input" v-model="privileges[index].assign" @click="privileges[index].assign = !privileges[index].assign">
          <span class="checkbox_x vs-checkbox" style="border: 2px solid rgb(180, 180, 180);">
            <span class="vs-checkbox--check">
              <i class="vs-icon notranslate icon-scale vs-checkbox--icon  material-icons null">check</i>
            </span>
          </span>
          <span class="con-slot-label">{{privileges[index].display_name}}</span>
        </div>
        <ul class="childofprivilae" v-bind:class="{'show': privileges[index].childActive !== true }">
          <li v-for="(privilage, i) in privileges[index].can_do" :key="i" @change="checked(index, i)">
            <div class="vs-component con-vs-checkbox vs-checkbox-success vs-checkbox-default">
              <input type="checkbox" class="vs-checkbox--input" v-model="privileges[index].can_do[i].assign" @click="privileges[index].can_do[i].assign = !privileges[index].can_do[i].assign">
              <span class="checkbox_x vs-checkbox" style="border: 2px solid rgb(180, 180, 180);">
                <span class="vs-checkbox--check">
                  <i class="vs-icon notranslate icon-scale vs-checkbox--icon  material-icons null">check</i>
                </span>
              </span>
              <span class="con-slot-label">{{ privilage.display_name }}</span>
            </div>
          </li>
        </ul>
      </li>
    </ul>

  </vs-row>
    <vs-col class="sm:w-1 md:w-1/2 lg:w-1/4 xl:w-1/4 pt-3 pb-3">
      <div class="w-full">
        <vs-button class="w-full mt-6 input-height" @click.stop="storePrivileges(privileges)">ثبت صلاحیت های کابر</vs-button>
      </div>
    </vs-col>
</div>
</template>

<script>
export default {
  props: {
    source: {
      required: false
    },
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      privileges: [],
      listKey: 0,
      user: null,
      checkAll: true,
    }
  },
  methods: {
    checked(index, i) {
      this.privileges[index].assign = false;
      for (let [key, value] of Object.entries(this.privileges[index]['can_do'])) {
        if(value.assign == true) {
          this.privileges[index].assign = true;
          break;
        }
      }
    },
    checkedMain(index, i) {
      if(!this.privileges[index].assign){
        for (const key2 of Object.keys(this.privileges[index]['can_do'])) {
          this.privileges[index]['can_do'][key2]['assign'] = false
        }
      }
    },
    getPermissions(user) {
      this.user = user;
      this.checkAll = true;
      this.axios.get('/api/permissions', {
          params: {
            id: (user) ? user.id : null,
          }
        })
        .then((response) => {
          this.privileges = response.data;
          setTimeout(() => {
            this.$emit('closeModal');
          }, 700);
        })
    },
    storePrivileges(p) {
      var username = (this.user) ? this.user.firstName + `&nbsp;` + this.user.lastName : 'کاربر'
      swal.fire({
        title: `آیا از صلاحیت های تعیین شده برای ${username} مطمئن هستید؟`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'rgb(54 34 119)',
        cancelButtonColor: 'rgb(229 83 85)',
        confirmButtonText: '<span>بله، ثبت شود!</span>',
        cancelButtonText: '<span>خیر، لغو عملیه!</span>'
      }).then((result) => {
        if (result.isConfirmed) {
          if (this.user) {
            this.$Progress.start()
            this.axios.post('/api/permissions', [this.user.id, this.privileges.filter((e) => e.assign === true)])
              .then((response) => {
                this.privileges = [];
                this.$emit('closeModal');
                this.$Progress.set(100)
                this.$vs.notify({
                  title: 'موفقیت!',
                  text: 'صلاحیت های کاربر موفقانه ثبت شد.',
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
          } else {
            this.$emit('closeModal', this.privileges);
          }
        }
      })
    },
    setAllPrivileges(value) {
      for (const key of Object.keys(this.privileges)) {
        this.privileges[key]['assign'] = value
        for (const key2 of Object.keys(this.privileges[key]['can_do'])) {
          this.privileges[key]['can_do'][key2]['assign'] = value
        }
      }
      this.checkAll = !value
    },
  }

}
</script>

<style>

</style>
