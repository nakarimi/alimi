<template>
<vs-table>
  <template slot="thead">
    <vs-th>نمبر</vs-th>
    <vs-th>نهاد</vs-th>
    <vs-th>پروژه</vs-th>
    <vs-th>تاریخ ختم اعلان</vs-th>
    <vs-th>وضعیت</vs-th>
    <vs-th>قیمت</vs-th>
    <vs-th>آدرس</vs-th>
    <vs-th>تاریخ پیشنهاد</vs-th>
  </template>
  <template>
    <tbody>
      <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in proposals">
        <vs-td class="pl-2 text-center">
          {{ indextr + 1 }}
        </vs-td>
        <vs-td>
          <router-link v-if="tr.pro_data" class="font-medium truncate" :to="{
                  url: `/projects/proposal/${tr.id}/edit`,
                  params: { id: tr.id, dyTitle: tr.title },
                }">
            <!-- <img :src="tr.img" class="product-img" /> -->
            <p>{{ findClient(tr.pro_data.client_id) }}</p>

          </router-link>
        </vs-td>
        <vs-td>
          <div v-if="tr.pro_data">
            <router-link class="font-medium truncate" :to="{
                  path: `/projects/proposal/${tr.id}`,
                  params: { id: tr.id, dyTitle: tr.title },
                }">
              {{ tr.pro_data.title }}</router-link>
          </div>
        </vs-td>
        <vs-td>
          <p>{{ tr.offer_guarantee }} افغانی</p>
        </vs-td>
        <vs-td>
          <vs-chip :color="getOrderStatusColor(tr.status)">{{ statusFa[tr.status] }}</vs-chip>
        </vs-td>
        <vs-td>
          <p>{{ tr.submission_date }}</p>
        </vs-td>
        <vs-td>
          <p>{{ tr.bidding_address }}</p>
        </vs-td>
        <vs-td>
          <p>{{ tr.bidding_date }}</p>
        </vs-td>
      </vs-tr>
    </tbody>
  </template>
</vs-table>
</template>

<script>
export default {
  name: 'vx-proposal-steps-table',
  props: ['proposals'],
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      clients: [],
      statusFa: {
        normal: 'درجریان',
        delivered: 'تکمیل',
        canceled: 'نا موفق',
        income: 'تاخیر'
      },
    }
  },
  created() {
    this.getAllClients();
  },
  methods: {
    getAllClients() {
      this.axios.get('/api/clients')
        .then((response) => {
          this.clients = response.data;
        })
    },
    findClient(id) {
      let name = '';
      Object.keys(this.clients).some(key => (this.clients[key].id == id) ? name = this.clients[key].name : null);
      return name;
    },
    getOrderStatusColor(status) {
      if (status === 'normal') return 'success'
      // if (status === 'delivered') return 'success'
      // if (status === 'canceled') return 'danger'
      return 'primary'
    },
  }
}
</script>
