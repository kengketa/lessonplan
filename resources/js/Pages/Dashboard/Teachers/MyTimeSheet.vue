<template>
  <div>
    <div class="content m-1">
      <Breadcrumbs :breadcrumbs="breadcrumbs" :back="route('dashboard')" />
      <PageHeading>
        My Timesheet
        <template #actions>
          <Link
            v-if="$page.props.authUserRole==='SUPER_ADMIN' || $page.props.authUserRole==='ADMIN'"
            class="button button-primary"
            :href="route('dashboard.schools.create')"
          >
            <AcademicCapIcon class="h-5 w-5 mr-2" aria-hidden="true" />
            Create School
          </Link>
        </template>
      </PageHeading>
      <div class="w-full flex">
        <button type="button" class="button button-small mr-2"
                @click="selectTimeSheet('lastMonth')"
                :class="selectedMonth=='lastMonth' ? 'button-primary' :'button-secondary'">
          Last month
        </button>
        <button type="button" class="button button-small"
                @click="selectTimeSheet('thisMonth')"
                :class="selectedMonth=='thisMonth' ? 'button-primary' :'button-secondary'">
          This month
        </button>
      </div>
      <TableDisplayContainer>
        <template #header>
          <TableTh v-for="(column,index) in columns" :key="index">
            <div class="flex">
              <span>{{ column }}</span>
            </div>
          </TableTh>
        </template>
        <template #body>
          <tr
            v-for="(item, itemIndex) in selectedMonth=='thisMonth' ? clockIn.thisMonth : clockIn.lastMonth"
            :key="item"
            :class="rowStyleComputed(itemIndex,item)"
          >

            <TableTd>
              {{ item.displayDate }}
            </TableTd>
            <TableTd>
              <span v-if="item.clockedIn != null">
                {{ item.clockedIn.clock_in }}
              </span>
              <span v-else>-</span>
            </TableTd>
            <TableTd>
              <span v-if="item.clockedIn != null">
                {{ item.clockedIn.clock_out }}
              </span>
              <span v-else>-</span>
            </TableTd>
          </tr>
        </template>
      </TableDisplayContainer>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layout";
import Breadcrumbs from "@/Components/Breadcrumbs";
import mapValues from "lodash/mapValues";
import Pagination from "@/Components/Pagination";
import PageHeading from "@/Components/PageHeading";
import TableDisplayContainer from "@/Components/TableDisplayContainer";
import TableTh from "@/Components/TableTh";
import TableTd from "@/Components/TableTd";
import TextInput from "@/Components/TextInput";
import SelectInput from "@/Components/SelectInput";
import {UserAddIcon, AcademicCapIcon} from "@heroicons/vue/solid";
import {Link} from "@inertiajs/inertia-vue3";

export default {
  name: "MyTimeSheet",
  layout: Layout,
  components: {
    Pagination,
    PageHeading,
    TableDisplayContainer,
    TableTh,
    TableTd,
    TextInput,
    SelectInput,
    Breadcrumbs,
    UserAddIcon,
    AcademicCapIcon,
    Link
  },
  props: {
    clockIn: {
      type: Object,
      required: true
    },
    title: String,
  },
  data() {
    return {
      form: {},
      breadcrumbs: [{name: 'My Timesheet', href: "#"}],
      columns: ['date', 'clock-In', 'clock-out'],
      selectedMonth: 'thisMonth'
    };
  },
  methods: {
    rowStyleComputed(itemIndex, item) {
      let rowColour = itemIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50';
      if ((item.day === 'Sat' || item.day === 'Sun') && item.clockedIn == null) {
        rowColour = 'bg-gray-300 hidden';
      }
      if ((item.day !== 'Sat' && item.day !== 'Sun') && item.clockedIn == null && item.past === true) {
        rowColour = itemIndex % 2 === 0 ? 'bg-red-50' : 'bg-red-100';
      }
      if (item.clockedIn != null) {
        rowColour = itemIndex % 2 === 0 ? 'bg-green-50' : 'bg-green-100';
      }
      return rowColour;
    },
    selectTimeSheet(month) {
      this.selectedMonth = month;
    },
    removeSchool: function (school) {
      if (confirm("Are you sure to remove this school?")) {
        school._method = "DELETE";
        this.$inertia.post(route("dashboard.schools.destroy", school), school);
      }
    },
    clearForm: function () {
      this.form = mapValues(this.form, () => "");
    },
    debounceSearch() {
      clearTimeout(this.debounce);
      this.debounce = setTimeout(() => {
        this.typing = null;
        this.sendRequest();
      }, 500);
    },
    sendRequest() {
      this.$inertia.replace(this.route("dashboard.schools.index", this.form));
    },

  },
  watch: {
    form: {
      handler: function () {
        this.debounceSearch();
      },
      deep: true,
    },
  },
  computed: {}
};
</script>

