<template>
<vs-dropdown class="w-full pro-serial-number company-select-wrapper" id="company-select-wrapper">
  <label for="serial_no"><small>سریال نمبر *</small></label>
  <vx-input-group class="">
    <template v-if="form.company_id" slot="append">
      <div class="append-text bg-primary">
        <span>{{ form.company_id.sign }}</span>
      </div>
    </template>
    <vs-input autocomplete="off" id="company-select" v-validate="'required'" size="medium" v-model="form.serial_no" name="serial_no" class="w-full" placeholder="انتخاب شرکت" disabled />
  </vx-input-group>
  <vs-dropdown-menu class="company-select" id="company-select" :style="getBannerStyle">
    <vs-dropdown-item v-for="(com, i) in companies" :key="'fs' + i" @click="$emit('companySelected', com)"> {{ com.label }} - {{ com.sign }} </vs-dropdown-item>
  </vs-dropdown-menu>
</vs-dropdown>
</template>

<script>
export default {
  props: ['form', 'companies'],
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      offsetWidth: 200,
      sources: [],
      selection: ""
    }
  },
  created() {},
  mounted() {},
  computed: {
    getBannerStyle() {
      return `min-width: ${this.offsetWidth}px;`
    }
  }

}
</script>

<style>

</style>
