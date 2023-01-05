<template>
  <div>
    <div class="content m-1">
      <Breadcrumbs :breadcrumbs="breadcrumbs" :back="route('dashboard.meetings.index')" />
      <PageHeading>
        Current Misbehavior students in 30 days
        <template #actions>

        </template>
      </PageHeading>
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
            v-for="(item, itemIndex) in students"
            :key="item"
            class="hover:bg-gray-100 cursor-pointer"
          >
            <TableTd>
              {{ item.grade }}
            </TableTd>
            <TableTd>
              {{ item.name }}
            </TableTd>
            <TableTd>
              {{ item.behavior }}
            </TableTd>
            <TableTd>
              {{ item.created_at }}
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
import {UserAddIcon} from "@heroicons/vue/solid";
import {Link} from "@inertiajs/inertia-vue3";
import SearchSelectInput from "@/Components/SearchSelectInput";

export default {
  name: "MisbehaviorIndex",
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
    students: Object,
    school: Object
  },
  data() {
    return {
      columns: ['Grade', 'Name', 'Behavior', 'Reported At'],
      breadcrumbs: [
        {name: 'School', href: route("dashboard.schools.show", this.school.id)},
        {name: 'Misbehavior', href: "#"}
      ],
      displayStudents: []
    };
  },
  mounted() {
  },
  methods: {},
  watch: {},
  computed: {}
};
</script>

