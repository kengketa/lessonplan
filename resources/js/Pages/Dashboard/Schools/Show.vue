<template>
  <div>
    <ConfirmDialog
      v-model="isShowDeleteDialog"
      title="Delete Product?"
      :body="`The School: ${ school.name} and its related data will be deleted. This action cannot be undone.`"
      confirm-text="Delete School"
      :confirm-event="deleteSchool"
    />
    <Breadcrumbs
      :breadcrumbs="breadcrumbs"
      :back="route('dashboard.schools.index')"
    />
    <PageHeading>
    <span class="hidden lg:inline">
      <span class="max-w-xs truncate">{{ school.name }}</span>
    </span>
      <template #actions>
        <Link
          :href="route('dashboard.schools.edit', school.id)"
          class="button button-primary mr-2"
        >
          <PencilIcon class="h-5 w-5 mr-2" aria-hidden="true" />
          Edit
        </Link>
        <form class="inline-flex" @submit.prevent="isShowDeleteDialog = true">
          <button type="submit" class="button button-danger">
            <TrashIcon class="h-5 w-5 mr-2" aria-hidden="true" />
            Delete
          </button>
        </form>
      </template>
    </PageHeading>
    <Card>
      <div class="md:flex md:items-center md:justify-between">
        <div class="flex">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              School Information
            </h3>
          </div>
        </div>
      </div>
      <div class="mt-5 border-t border-gray-200">
        <DataDisplayContainer>
          <DataDisplayRow>
            <template #label>
              name
            </template>
            {{ school.name }}
          </DataDisplayRow>
          <DataDisplayRow>
            <template #label>
              address
            </template>
            {{ school.address }}
          </DataDisplayRow>
        </DataDisplayContainer>
      </div>
    </Card>
    <div>
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
            v-for="(item, itemIndex) in school.grades.data"
            :key="item"
            :class="itemIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
          >
            <TableTd>
              <inertia-link
                :href="route('dashboard.grades.show',item.id)"
                class="hover:underline"
              >
                {{ item.name }}
              </inertia-link>
            </TableTd>
            <TableTd>
              <inertia-link :href="route('dashboard.grades.edit', item.id)" class="link">
                Edit
              </inertia-link>
            </TableTd>
          </tr>
        </template>
      </TableDisplayContainer>
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumbs from '@/Components/Breadcrumbs';
import Card from '@/Components/Card';
import PageHeading from '@/Components/PageHeading';
import DataDisplayContainer from '@/Components/DataDisplayContainer';
import DataDisplayRow from '@/Components/DataDisplayRow';
import ConfirmDialog from '@/Components/ConfirmDialog';
import TableDisplayContainer from "@/Components/TableDisplayContainer";
import TableTh from "@/Components/TableTh";
import TableTd from "@/Components/TableTd";
import {Link} from '@inertiajs/inertia-vue3';

import {
  ExternalLinkIcon,
  EyeIcon,
  PencilIcon,
  TrashIcon,
  CheckCircleIcon,
  ExclamationCircleIcon
} from '@heroicons/vue/solid';
import {useForm} from '@inertiajs/inertia-vue3';
import Button from '@/Jetstream/Button';

export default {
  name: 'SchoolShow',
  components: {
    Card, PageHeading, Breadcrumbs, DataDisplayContainer,
    DataDisplayRow, PencilIcon, TrashIcon, ExternalLinkIcon, EyeIcon,
    ConfirmDialog, TableDisplayContainer, TableTh, TableTd, Link
  },
  layout: Layout,
  props: {
    school: Object,
    flash: Object,
    errorBags: Object,
    errors: Object,
  },
  data() {
    return {
      breadcrumbs: [
        {name: 'Schools', href: route('dashboard.schools.index')},
        {name: this.school.name, href: '#'},
      ],
      columns: ['grade'],
      deleteForm: useForm({}),
      isShowDeleteDialog: false,
    };
  },
  computed: {},
  methods: {
    deleteSchool() {
      this.deleteForm.delete(route('dashboard.schools.destroy', {school: this.school.id}));
    },
  },
};
</script>

<style>
</style>
