<template>
  <div>
    <div class="content m-1">
      <Breadcrumbs :breadcrumbs="breadcrumbs" :back="route('dashboard.meetings.index')" />
      <PageHeading>
        Meeting Management
        <template #actions>
          <Link
            v-if="$page.props.authUserRole == 'SUPER_ADMIN' || $page.props.authUserRole == 'ADMIN'"
            class="button button-primary"
            :href="route('dashboard.meetings.create')"
          >
            <UserAddIcon class="h-5 w-5 mr-2" aria-hidden="true" />
            Create Meeting
          </Link>
        </template>
      </PageHeading>
      <div class="md:grid md:grid-cols-3 md:gap-2">
        <SearchSelectInput
          v-if="$page.props.authUserRole == 'SUPER_ADMIN' || $page.props.authUserRole == 'ADMIN'"
          v-model="form.school"
          :options="schools"
          :is-show-line="false"
        />
        <TextInput
          v-model="form.search"
          :isShowLine="false"
          placeholder="search.."
        ></TextInput>
        <div class="flex flex items-end">
          <button type="button" @click="clear()" class="button button-primary button-small mb-1 ml-1">Clear</button>
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
            v-for="(item, itemIndex) in meetings.data"
            :key="item"
            class="hover:bg-gray-100 cursor-pointer"
            @click="visit(item)"
            :class="computedRowColor(item,itemIndex)"
          >
            <TableTd>
              {{ item.school }}
            </TableTd>
            <TableTd>
              {{ item.title }}
            </TableTd>
            <TableTd>
              {{ item.date }} {{ item.time }}
            </TableTd>
          </tr>
        </template>
        <template #pagination>
          <Pagination :data="meetings.meta.pagination"></Pagination>
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
import SearchSelectInput from "@/Components/SearchSelectInput";

export default {
  name: "MeetingIndex",
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
    Link,
    SearchSelectInput
  },
  props: {
    meetings: Object,
    filters: Object,
    title: String,
    schools: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search ?? "",
        school: this.filters.school ?? null
      },
      breadcrumbs: [{name: 'Meetings', href: "#"}],
      columns: ['school', 'title', 'date'],
    };
  },
  methods: {
    computedRowColor(meeting, index) {
      if (meeting.status == 1) {
        return 'bg-green-100'
      }
      return index % 2 === 0 ? 'bg-white' : 'bg-gray-50'
    },
    visit(meeting) {
      this.$inertia.visit(route('dashboard.meetings.show', meeting.id));
    },
    clear() {
      this.$inertia.visit(route('dashboard.meetings.index'));
    },
    removeMeeting: function (meeting) {
      if (confirm("Are you sure to remove this meeting?")) {
        meeting._method = "DELETE";
        this.$inertia.post(route("dashboard.meetings.destroy", meeting), meeting);
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
      this.$inertia.replace(this.route("dashboard.meetings.index", this.form));
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

