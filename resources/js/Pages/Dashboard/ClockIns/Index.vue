<template>
  <div>
    <div class="content m-1">
      <Breadcrumbs :breadcrumbs="breadcrumbs" :back="route('dashboard.schools.index')" />
      <PageHeading>
        Clock In Management
        <template #actions>
          <button class="button button-primary opacity-50 cursor-not-allowed" disabled>
            <ClockIcon class="h-5 w-5 mr-2" aria-hidden="true" />
            Clock in
          </button>
        </template>
      </PageHeading>
      <div class="md:grid md:grid-cols-3 md:gap-2">
        <SearchSelectInput
          v-model="form.filters.teacher_id"
          :options="allTeachers"
          :is-show-line="false"
        />
        <SearchSelectInput
          :options="monthOptions"
          v-model="form.filters.month"
          :is-show-line="false"
        />
        <div class="flex flex items-end">
          <button type="button" @click="clear()" class="button button-primary button-small mb-1 ml-1">Clear</button>
          <button type="button" @click="generate()" class="button button-primary button-small mb-1 ml-1">Generate
          </button>
        </div>
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
            v-for="(item, itemIndex) in clockIns.data"
            :key="item"
            :class="itemIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
          >
            <TableTd>
              <span>{{ item.teacher_name }}</span>
            </TableTd>
            <TableTd>
              <span>{{ item.date }}</span>
            </TableTd>
            <TableTd>
              <span>{{ item.clock_in }}</span>
            </TableTd>
            <TableTd>
              <span>{{ item.clock_out }}</span>
            </TableTd>
          </tr>
        </template>
        <template #pagination>
          <Pagination :data="clockIns.meta.pagination"></Pagination>
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
import {UserAddIcon, AcademicCapIcon, ClockIcon} from "@heroicons/vue/solid";
import {Link} from "@inertiajs/inertia-vue3";
import SearchSelectInput from "@/Components/SearchSelectInput";
import {useForm} from '@inertiajs/inertia-vue3';

export default {
  name: "ClockInIndex",
  layout: Layout,
  components: {
    Pagination,
    PageHeading,
    TableDisplayContainer,
    TableTh,
    TableTd,
    Breadcrumbs,
    UserAddIcon,
    AcademicCapIcon,
    Link,
    ClockIcon,
    SearchSelectInput,
  },
  props: {
    clockIns: Object,
    filters: Object,
    title: String,
    allTeachers: {
      type: Object,
      required: true
    },
    monthOptions: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      form: useForm({
        filters: {
          teacher_id: this.filters.teacher_id != null ? parseInt(this.filters.teacher_id) : null,
          month: this.filters.month
        }
      }),
      breadcrumbs: [{name: 'Schools', href: "#"}],
      columns: ['teacher', 'date', 'clock in', 'clock out'],
    };
  },
  methods: {
    generate() {
      let payload = {
        month: this.form.filters.month,
        teacher: this.form.filters.teacher_id ?? null
      };
      this.$inertia.visit(route('dashboard.clock_ins.generate_report', payload))
    },
    clear() {
      this.$inertia.visit(route('dashboard.clock_ins.index'))
    },
    debounceSearch() {
      clearTimeout(this.debounce);
      this.debounce = setTimeout(() => {
        this.typing = null;
        this.sendRequest();
      }, 500);
    },
    sendRequest() {
      this.$inertia.get(this.route("dashboard.clock_ins.index", this.form));
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

