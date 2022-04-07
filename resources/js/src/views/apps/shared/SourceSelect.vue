<template>
<div class="source-select-wrapper" id="source-select-wrapper">
  <vs-dropdown v-if="sources">
    <vs-input :value="parentForm.source_id ? selection : ''" disabled autocomplete="off" class="w-full" />
    <vs-dropdown-menu class="source-select" id="source-select" :style="getBannerStyle">
      <vs-dropdown-group vs-label="ذخایر عمومی">
        <vs-dropdown-item v-for="(item, i) in sources.str" :key="'str' + i" @click="ddSelected(item, 'STRG')"> {{ item.name }} </vs-dropdown-item>
      </vs-dropdown-group>

      <vs-dropdown-group vs-label="تانک تیل">
        <vs-dropdown-group vs-label="" class="fuel-station">
          <vs-dropdown-group vs-collapse vs-icon="add" v-for="(fuel, i) in sources.fuel" :key="'f' + i" @click="ddSelected(fuel, 'FUEL', )" :vs-label="fuel.name">
            <vs-dropdown-item v-for="(item, i) in fuel.fuel_station_storages" :key="'fs' + i" @click="ddSelected(item, 'FSTR', fuel)"> {{ item.name }} </vs-dropdown-item>
            <vs-dropdown-item v-for="(item, i) in fuel.fuel_despencers" :key="'fd' + i" @click="ddSelected(item, 'FDSP', fuel)"> {{ item.name }} </vs-dropdown-item>
          </vs-dropdown-group>
        </vs-dropdown-group>
      </vs-dropdown-group>

      <vs-dropdown-group vs-label="گدام ها">
        <vs-dropdown-item v-for="(item, i) in sources.inv" :key="'s' + i" @click="ddSelected(item, 'STOK')"> {{ item.name }} </vs-dropdown-item>
      </vs-dropdown-group>
    </vs-dropdown-menu>
  </vs-dropdown>
</div>
</template>

<script>
import vSelect from "vue-select";

export default {
  props: ['parentForm'],
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      offsetWidth: 100,
      sources: [],
      selection: "",
      matched_items: [],
      items: [],
    }
  },
  created() {
    this.getStorages();
  },
  methods: {
    async findAndSet(data, type) {

      const result = await this.getStorages();

      if (type == 'FSTR') {
        let mainStation = data.station_id;
        this.sources.fuel.forEach(e => {
          if (e.id === mainStation) {
            let f_station = e;
            f_station['fuel_station_storages'].forEach(ee => {
              if (ee.id === data.id) {
                this.ddSelected(ee, type, f_station)
              }
            })
          }
        })
      } else if (type == 'FDSP') {
        let mainStation = data.station_id;
        this.sources.fuel.forEach(e => {
          if (e.id === mainStation) {
            let f_station = e;
            f_station['fuel_despencers'].forEach(ee => {
              if (ee.id === data.id) {
                this.ddSelected(ee, type, f_station)
              }
            })
          }
        });
      } else if (type == 'STOK') {
        this.sources.inv.forEach(ee => {
          if (ee.id === data.id) {
            this.ddSelected(ee, type)
          }
        })
      } else {
        this.sources.str.forEach(ee => {
          if (ee.id === data.id) {
            this.ddSelected(ee, type)
          }
        })
      }
    },
    ddSelected(data, type, parent = null) {
      this.getStorageItems(type, data.id);
      this.parentForm.source_id = data;
      this.parentForm.source_type = type;
      this.parentForm.source = type
      if (parent) {
        this.selection = parent.name + ' - ' + this.parentForm.source_id.name;
      } else {
        this.selection = this.parentForm.source_id.name;
      }

    },
    getStorages() {
      return new Promise(resolve => {
        this.$Progress.start();
        this.axios
          .get("/api/sources")
          .then((data) => {
            this.sources = data.data;
            this.$Progress.set(100);
            resolve('resolved');
          })
          .catch(() => {
            document.getElementById("loading-bg").style.display = "none";
            resolve('resolved');
          });
      });
    },
    getStorageItems(type, id) {
      this.axios
        .get(`/api/sources/find-items`, {
          params: {
            id: id,
            type: type
          }
        })
        .then((data) => {
          this.matched_items = data.data;
          this.$emit('updateItems', this.matched_items)
        })
        .catch(() => {
          document.getElementById("loading-bg").style.display = "none";
        });
    },
  },
  mounted() {
    this.offsetWidth = document.getElementById('source-select-wrapper').offsetWidth
  },
  computed: {
    getBannerStyle() {
      return `width: ${this.offsetWidth}px;`
    }
  }
}
</script>

<style lang="">

</style>
