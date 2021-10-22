<template>
  <div>
    <div class="content m-1">
      <Breadcrumbs :breadcrumbs="breadcrumbs" :back="route('dashboard.reports.index')" />
      <PageHeading>
        {{ title }}
        <template #actions>
          <!--                    <inertia-link-->
          <!--                        class="button button-primary"-->
          <!--                        :href="route('dashboard.reports.create')"-->
          <!--                    >-->
          <!--                        <UserAddIcon class="h-5 w-5 mr-2" aria-hidden="true" />-->
          <!--                        Create Report-->
          <!--                    </inertia-link>-->
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
            v-for="(item, itemIndex) in reports.data"
            :key="item"
            :class="itemIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
          >
            <TableTd>
              <inertia-link
                :href="route('dashboard.reports.show',item.id)"
                class="hover:underline"
              >
                {{ item.grade_id }}
              </inertia-link>
            </TableTd>
            <TableTd>
              <inertia-link
                :href="route('dashboard.reports.show',item.id)"
                class="hover:underline"
              >
                {{ item.week_number }}
              </inertia-link>
            </TableTd>
            <TableTd>
              <inertia-link
                :href="route('dashboard.reports.show',item.id)"
                class="hover:underline"
              >
                {{ item.lesson_number }}
              </inertia-link>
            </TableTd>
            <TableTd>
              <inertia-link
                :href="route('dashboard.reports.show',item.id)"
                class="hover:underline"
              >
                {{ item.date }}
              </inertia-link>
            </TableTd>
            <TableTd>
              <inertia-link
                :href="route('dashboard.reports.show',item.id)"
                class="hover:underline"
              >
                {{ item.topic }}
              </inertia-link>
            </TableTd>
            <TableTd>
              <inertia-link
                :href="route('dashboard.reports.show',item.id)"
                class="hover:underline"
              >
                {{ item.subject }}
              </inertia-link>
            </TableTd>
            <TableTd>
              <inertia-link
                :href="route('dashboard.reports.show',item.id)"
                class="hover:underline"
              >
                {{ item.outcome }}
              </inertia-link>
            </TableTd>
            <TableTd>
              <inertia-link
                :href="route('dashboard.reports.show',item.id)"
                class="hover:underline"
              >
                {{ item.outstanding_student }}
              </inertia-link>
            </TableTd>
            <TableTd>
              <inertia-link
                :href="route('dashboard.reports.show',item.id)"
                class="hover:underline"
              >
                {{ item.need_improvement_student }}
              </inertia-link>
            </TableTd>
            <TableTd>
              <inertia-link
                :href="route('dashboard.reports.show',item.id)"
                class="hover:underline"
              >
                {{ item.creator }}
              </inertia-link>
            </TableTd>
            <TableTd>
              <inertia-link
                :href="route('dashboard.reports.show',item.id)"
                class="hover:underline"
              >
                {{ item.approver }}
              </inertia-link>
            </TableTd>

            <TableTd>
              <inertia-link :href="route('dashboard.reports.edit', item.id)" class="link">
                Edit
              </inertia-link>
            </TableTd>
          </tr>
        </template>
        <template #pagination>
          <Pagination :data="reports.meta.pagination"></Pagination>
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

export default {
  name: "ReportIndex",
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
  },
  props: {
    reports: Object,
    filters: Object,
    title: String,
  },
  data() {
    return {
      form: {
        search: this.filters.search ?? "",
      },
      breadcrumbs: [{name: 'Reports', href: "#"}],
      columns: ['grade_id', 'week_number', 'lesson_number', 'date', 'topic', 'subject', 'outcome', 'outstanding_student', 'need_improvement_student', 'creator', 'approver',],
    };
  },
  methods: {
    removeReport: function (report) {
      if (confirm("Are you sure to remove this report?")) {
        report._method = "DELETE";
        this.$inertia.post(route("dashboard.reports.destroy", report), report);
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
      this.$inertia.replace(this.route("dashboard.reports.index", this.form));
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

