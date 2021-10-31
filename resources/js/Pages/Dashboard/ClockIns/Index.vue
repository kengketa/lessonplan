<template>
  <div>
    <div class="content m-1">
      <Breadcrumbs :breadcrumbs="breadcrumbs" :back="route('dashboard.schools.index')" />
      <PageHeading>
        {{ title }}
        <template #actions>

        </template>
      </PageHeading>
      <div class="md:grid md:grid-cols-3 md:gap-2">
        <SearchSelectInput
          v-model="form.filters.school_id"
          :options="allSchools"
          :is-show-line="false"
          label="school"
        />
        <SearchSelectInput
          v-model="form.filters.teacher_id"
          :options="teachersInSchool"
          :is-show-line="false"
          label="teacher"
        />
        <SearchSelectInput
          :options="['asd','asds']"
          :is-show-line="false"
          label="month"
        />
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
import {UserAddIcon, AcademicCapIcon} from "@heroicons/vue/solid";
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
    SearchSelectInput,
  },
  props: {
    clockIns: Object,
    filters: Object,
    title: String,
    allSchools: {
      type: Object,
      required: true
    },
  },
  data() {
    return {
      form: useForm({
        filters: {
          school_id: this.filters.school_id != null ? parseInt(this.filters.school_id) : null,
          teacher_id: this.filters.teacher_id != null ? parseInt(this.filters.teacher_id) : null,
        }
      }),
      breadcrumbs: [{name: 'Schools', href: "#"}],
      columns: ['teacher', 'date', 'clock in', 'clock out'],
    };
  },
  methods: {
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
  computed: {
    teachersInSchool() {
      if (this.form.filters.school_id == null) {
        let emptyTeachers = [
          {id: 0, name: "please select school"}
        ];
        return emptyTeachers;
      }
      let slectedSchool = this.allSchools.find(school => {
        return school.id == this.form.filters.school_id
      });
      return slectedSchool.teachers;
    }
  }
};
</script>

