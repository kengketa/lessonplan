<template>
  <div>
    <div class="content m-1">
      <Breadcrumbs :breadcrumbs="breadcrumbs" :back="route('dashboard.schools.index')" />
      <PageHeading>
        {{ title }}
        <template #actions>
          <Link
            class="button button-primary"
            :href="route('dashboard.schools.create')"
          >
            <UserAddIcon class="h-5 w-5 mr-2" aria-hidden="true" />
            Create School
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
        <div class="col-span-12 md:col-span-2 flex items-end justify-end">
          <div>
            <button
              @click="clearForm"
              class="button button-secondary button-small"
            >
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
            v-for="(item, itemIndex) in schools.data"
            :key="item"
            :class="itemIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
          >
            <TableTd>
              <Link
                :href="route('dashboard.schools.show',item.id)"
                class="hover:underline"
              >
                {{ item.name }}
              </Link>
            </TableTd>
            <TableTd>
              <Link
                :href="route('dashboard.schools.show',item.id)"
                class="hover:underline"
              >
                {{ item.address }}
              </Link>
            </TableTd>

            <TableTd>
              <Link :href="route('dashboard.schools.edit', item.id)" class="link">
                Edit
              </Link>
            </TableTd>
          </tr>
        </template>
        <template #pagination>
          <Pagination :data="schools.meta.pagination"></Pagination>
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
import {UserAddIcon} from "@heroicons/vue/solid";
import {Link} from "@inertiajs/inertia-vue3";

export default {
  name: "SchoolIndex",
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
    Link
  },
  props: {
    schools: Object,
    filters: Object,
    title: String,
  },
  data() {
    return {
      form: {
        search: this.filters.search ?? "",
      },
      breadcrumbs: [{name: 'Schools', href: "#"}],
      columns: ['name', 'address',],
    };
  },
  methods: {
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

