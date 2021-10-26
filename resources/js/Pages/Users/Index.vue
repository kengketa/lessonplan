<template>
  <div>
    <div class="content m-1">
      <Breadcrumbs :breadcrumbs="breadcrumbs" :back="route('dashboard.users.index')" />

      <PageHeading>
        Users Management
        <template #actions>
          <Link
            class="button button-primary"
            :href="route('dashboard.users.create')"
          >
            <UserAddIcon class="h-5 w-5 mr-2" aria-hidden="true" />
            Create User
          </Link>
        </template>
      </PageHeading>
      <div class="grid grid-cols-12 gap-2 content-center">
        <div class="col-span-12 md:col-span-5">
          <TextInput
            v-model="form.search"
            :isShowLine="false"
            placeholder="Name, Email, Contact"
          ></TextInput>
        </div>
        <div class="col-span-12 md:col-span-5">
          <SelectInput class="w-full" :isShowLine="false" v-model="form.role">
            <template #option>
              <option value="">Please select a role</option>
              <option
                v-for="(option, key) in roles"
                :key="option"
                :value="option"
              >
                {{ key }}
              </option>
            </template>
          </SelectInput
          >
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
          <TableTh v-for="column in sort.columns" :key="column"
                   :class="[sort.key === column ? 'font-bold' : '']"
          >
            <div class="flex">
              <a class="hover:underline cursor-pointer flex" @click="sortBy(column)">
                <div v-if="sort.key == column">
                    <span v-if="sort.reverse" class="flex ml-1">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                           stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                      </svg>
                    </span>
                  <span v-else class="flex ml-1">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                           stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                      </svg>
                    </span>
                </div>
                <span>
                    {{ column }}
                  </span>
              </a>
            </div>
          </TableTh>
          <TableTh></TableTh>
        </template>
        <template #body>
          <tr
            v-for="(item, itemIndex) in sortedUser"
            :key="item"
            :class="itemIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
          >
            <TableTd>
              <div class="flex items-center">
                <div class="flex-grow-0 flex-shrink-0 mr-4">
                  <Link
                    :href="route('dashboard.users.show', item.id)"
                    class="hover:underline"
                  >
                    <img
                      :src="item.profile_photo_url"
                      class="rounded-full w-12 h-12"
                    />
                  </Link>
                </div>
                <div class="flex-grow-1">
                  <Link
                    :href="route('dashboard.users.show', item.id)"
                    class="hover:underline"
                  >
                    <span class="font-medium">{{ item.name }}</span>
                    <br />
                    <span class="text-primary">{{ item.email }}</span>
                  </Link>
                </div>
              </div>
            </TableTd>

            <TableTd>
              <Link
                :href="route('dashboard.users.show', item.id)"
                class="hover:underline"
              >
                {{ item.role }}
              </Link>
            </TableTd>
            <TableTd>
              <Link :href="route('dashboard.users.edit', item.id)" class="link">
                Edit
              </Link>
            </TableTd>
          </tr>
        </template>
        <template #pagination>
          <Pagination :data="users.meta.pagination"></Pagination>
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
  name: "Index",
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
    roles: Object,
    users: Object,
    filters: Object,
    title: String,
  },
  data() {
    return {
      form: {
        search: this.filters.search ?? "",
        role: this.filters.role ?? "",
      },
      breadcrumbs: [{name: "Users", href: "#"}],
      sort: {
        columns: ['name', 'role'],
        reverse: false,
        key: 'name'
      }
    };
  },
  methods: {
    removeUser: function (user) {
      if (confirm("Are you sure to remove this user?")) {
        user._method = "DELETE";
        this.$inertia.post(route("dashboard.users.destroy", user), user);
      }
    },
    clearForm: function () {
      this.form = mapValues(this.form, () => "");
    },
    getKeyByValue(object, value) {
      return Object.keys(object).find((key) => object[key] === value);
    },
    debounceSearch() {
      clearTimeout(this.debounce);
      this.debounce = setTimeout(() => {
        this.typing = null;
        this.sendRequest();
      }, 500);
    },
    sendRequest() {
      this.$inertia.replace(this.route("dashboard.users.index", this.form));
    },
    sortBy(sortKey) {
      this.sort.reverse = (this.sort.key == sortKey) ? !this.sort.reverse : false;
      this.sort.key = sortKey;
      console.log(this.sort.key);
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
    sortedUser: function () {
      return this.users.data.sort((p1, p2) => {
        let modifier = 1;
        if (this.sort.reverse) modifier = -1;
        if (p1[this.sort.key] < p2[this.sort.key]) return -1 * modifier;
        if (p1[this.sort.key] > p2[this.sort.key]) return 1 * modifier;
        return 0;
      });
    }
  }

};
</script>

