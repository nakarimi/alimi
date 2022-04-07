<template>
<div>
  <despencer-sidebar :key="keyDespencer" :isSidebarActive="despencerSidebar" @closeSidebar="toggleDespencer" :data="dataDespencer" :storages="storages" />

  <div id="data-list-thumb-view" class="w-full data-list-container">
    <vs-table class="w-full" ref="table" pagination :max-items="itemsPerPage" :data="depencers">
      <template slot="thead">
        <vs-th sort-key="name">نام</vs-th>
        <vs-th sort-key="name">مدیر</vs-th>
        <vs-th sort-key="name">تانک تیل</vs-th>
        <vs-th sort-key="name">تلفن</vs-th>
        <vs-th v-if="user_permissions.includes('fuel_edit')|| user_permissions.includes('fuel_remove')" sort-key="name">عملیه</vs-th>
      </template>
      <template slot-scope="{data}">
        <tbody>
          <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
            <vs-td>
              {{ tr.name }}
            </vs-td>
            <vs-td>
              {{ tr.fuel_station.manager }}
            </vs-td>
            <vs-td>
              {{ tr.fuel_station.name }}
            </vs-td>
            <vs-td>
              {{ tr.fuel_station.phone }}
            </vs-td>
            <vs-td v-if="user_permissions.includes('fuel_edit')|| user_permissions.includes('fuel_remove')">
              <feather-icon v-if="user_permissions.includes('fuel_edit')" icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click.stop="deleteData(tr.id)" />
              <feather-icon v-if="user_permissions.includes('fuel_remove')" icon="EditIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click.stop="editDispenser(tr.id)" />
            </vs-td>
          </vs-tr>
        </tbody>
      </template>
    </vs-table>
  </div>
</div>
</template>

<script>
import DespencerAddSideBar from './DespencerAddSideBar.vue'

export default {
  // name: 'project-list',
  props: ['depencers'],
  data() {
    return {
      user_permissions: localStorage.getItem('user_permissions'),
      statusFa: {
        on_hold: 'مدیر',
        delivered: 'ادمین',
        canceled: 'سوپروایزر',
      },
      selected: [],
      // depencers: [],
      itemsPerPage: 4,
      isMounted: false,
      addNewDataSidebar: false,
      sidebarData: {},
      despencerSidebar: false,
      dataDespencer: {},
      storages: [],
      keyDespencer: 0,
    }
  },
  components: {
    'despencer-sidebar': DespencerAddSideBar,
  },
  created() {

  },
  computed: {
    currentPage() {
      if (this.isMounted) {
        return this.$refs.table.currentx
      }
      return 0
    },
    queriedItems() {
      return this.$refs.table ? this.$refs.table.queriedResults.length : this.depencers.length
    }
  },
  methods: {
    openDespencer() {
      this.toggleDespencer(true)
    },
    toggleDespencer(val = false) {
      this.$validator.reset();
      this.despencerSidebar = val
      this.$emit('updateTable')
    },
    editDispenser(id) {
      this.axios.get(`/api/despenser/${id}/edit`).then((resp) => {
        this.dataDespencer = resp.data;
        this.dataDespencer['station_id'] = this.dataDespencer.station_id
        this.axios.post('/api/storage-station', {id: this.dataDespencer.station_id}).then(({
          data
        }) => {
          this.storages = data
          this.keyDespencer += 1;
          this.openDespencer();
        });
      });
    },
    toggleDataSidebar(val = false) {
      this.addNewDataSidebar = val
    },
    deleteData(id) {
      swal.fire({
        title: 'آیا مطمئن هستید؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: 'rgb(54 34 119)',
        cancelButtonColor: 'rgb(229 83 85)',
        confirmButtonText: '<span>بله، حذف شود!</span>',
        cancelButtonText: '<span>خیر، لغو عملیه!</span>'
      }).then((result) => {
        if (result.isConfirmed) {
          this.axios.delete('/api/despenser/' + id, id).then((id) => {
              swal.fire({
                title: 'عملیه موفقانه انجام شد.',
                icon: 'success',
              });
              this.$emit('updateTable');
            })
            .catch(() => {
              swal.fire(
                'ناموفق !',
                'سیستم قادر به حذف نیست. وابستگی های آیتم را بررسی نمایید!',
                'error'
              )
              document.getElementById("loading-bg").style.display = "none";
            });
        }
      })
    },
  },
  mounted() {
    this.isMounted = true
  }
}
</script>

<style lang="scss">
#data-list-thumb-view {
  .vs-con-table {
    .product-name {
      max-width: 23rem;
    }

    .vs-table--header {
      display: flex;
      flex-wrap: wrap-reverse;
      margin-left: 1.5rem;
      margin-right: 1.5rem;

      >span {
        display: flex;
        flex-grow: 1;
      }

      .vs-table--search {
        padding-top: 0;

        .vs-table--search-input {
          padding: 0.9rem 2.5rem;
          font-size: 1rem;

          &+i {
            left: 1rem;
          }

          &:focus+i {
            left: 1rem;
          }
        }
      }
    }

    .vs-table {
      border-collapse: separate;
      border-spacing: 0 1.3rem;
      padding: 0 1rem;

      tr {
        box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);

        td {
          padding: 10px;

          &:first-child {
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
          }

          &:last-child {
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
          }

          &.img-container {
            // width: 1rem;
            // background: #fff;

            span {
              display: flex;
              justify-content: flex-start;
            }

            .product-img {
              height: 110px;
            }
          }
        }

        td.td-check {
          padding: 20px !important;
        }
      }
    }

    .vs-table--thead {
      th {
        padding-top: 0;
        padding-bottom: 0;

        .vs-table-text {
          text-transform: uppercase;
          font-weight: 600;
        }
      }

      th.td-check {
        padding: 0 15px !important;
      }

      tr {
        background: none;
        box-shadow: none;
      }
    }

    .vs-table--pagination {
      justify-content: center;
    }
  }
}
</style>
