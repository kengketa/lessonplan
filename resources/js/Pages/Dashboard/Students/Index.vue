<template>
  <div>
    <div class="content m-1">
      <Breadcrumbs :back="route('dashboard.schools.index')" :breadcrumbs="breadcrumbs"/>
      <PageHeading>
        Student Management
        <template #actions>
          <Link
            v-if="$page.props.authUserRole==='SUPER_ADMIN' || $page.props.authUserRole==='ADMIN'"
            :href="route('dashboard.students.create')"
            class="button button-primary"
          >
            <UserAddIcon aria-hidden="true" class="h-5 w-5 mr-2"/>
            Create Student
          </Link>
        </template>
      </PageHeading>
      <div class="grid grid-cols-12 gap-2 content-center">
        <div class="col-span-12 md:col-span-5">
          <TextInput
            v-model="form.search"
            :isShowLine="false"
            placeholder="search.."
          ></TextInput>
        </div>
        <div class="col-span-12 md:col-span-2 flex items-center">
          <div>
            <button class="button button-secondary button-small" @click="clearForm">
              Clear
            </button>
          </div>
        </div>
      </div>
      <TableDisplayContainer>
        <template #header>
          <TableTh v-for="(column,index) in columns" :key="index">
            <div class="flex">
              <span>{{ column }}</span>
            </div>
          </TableTh>
          <TableTh>Actions</TableTh>
        </template>
        <template #body>
          <tr
            v-for="(student, studentIndex) in students.data"
            :key="student"
            :class="studentIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
          >
            <TableTd>
              <Link
                :href="route('dashboard.students.show',student.id)"
                class="hover:underline"
              >
                {{ student.code }}
              </Link>
            </TableTd>
            <TableTd>
              <Link
                :href="route('dashboard.students.show',student.id)"
                class="hover:underline"
              >
                {{ student.prefix }} {{ student.first_name }} {{ student.last_name }}
              </Link>
            </TableTd>
            <TableTd>
              <Link v-if="$page.props.authUserRole==='SUPER_ADMIN' || $page.props.authUserRole==='ADMIN'"
                    :href="route('dashboard.students.edit', student.id)" class="link">
                Edit
              </Link>
            </TableTd>
          </tr>
        </template>
        <template #pagination>
          <Pagination :data="students.meta.pagination"></Pagination>
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
  name: "StudentIndex",
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
    students: Object,
    filters: Object,
    title: String,
  },
  data() {
    return {
      form: {
        search: this.filters.search ?? "",
      },
      breadcrumbs: [{name: 'Students', href: "#"}],
      columns: ['name', 'address',],
    };
  },
  mounted() {
    console.log('-----------------');
    console.log(this.students.meta.pagination);
    console.log('-----------------');
  },
  methods: {
    removeSchool: function (student) {
      if (confirm("Are you sure to remove this student?")) {
        student._method = "DELETE";
        this.$inertia.post(route("dashboard.students.destroy", student), student);
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
      this.$inertia.replace(this.route("dashboard.students.index", this.form));
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

