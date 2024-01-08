<template>
  <div>
    <Breadcrumbs
      :back="route('dashboard.schools.index')"
      :breadcrumbs="breadcrumbs"
    />
    <PageHeading>
    <span class="hidden lg:inline">
      <span class="max-w-xs truncate">
        {{ grade.name }}
      </span>
    </span>
    </PageHeading>
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
            <Link
              :href="route('dashboard.reports.edit',item.id)"
              class="hover:underline"
            >
              {{ item.subject }}
            </Link>
          </TableTd>
          <TableTd>
            <Link
              :href="route('dashboard.reports.edit',item.id)"
              class="hover:underline"
            >
              {{ item.week_number }}
            </Link>
          </TableTd>
          <TableTd>
            <Link
              :href="route('dashboard.reports.edit',item.id)"
              class="hover:underline"
            >
              {{ item.lesson_number }}
            </Link>
          </TableTd>
          <TableTd>
            <Link
              :href="route('dashboard.reports.edit',item.id)"
              class="hover:underline"
            >
              {{ item.date }}
            </Link>
          </TableTd>
          <TableTd>
            <Link
              :href="route('dashboard.reports.edit',item.id)"
              class="hover:underline"
            >
              {{ item.plans.topic }}
            </Link>
          </TableTd>
          <TableTd>
            <Link :href="route('dashboard.reports.edit', item.id)" class="link">
              Edit
            </Link>
          </TableTd>
        </tr>
      </template>
      <template #pagination>
        <Pagination :data="reports.meta.pagination"></Pagination>
      </template>
    </TableDisplayContainer>
    <div>
      {{ students }}
    </div>

    <div class="mt-10">
      {{ subjects }}
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
import AddGradeModal from "@/Components/Forms/AddGradeModal";
import Pagination from "@/Components/Pagination";

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
  name: 'GradeShow',
  components: {
    Card, PageHeading, Breadcrumbs, DataDisplayContainer, AddGradeModal,
    DataDisplayRow, PencilIcon, TrashIcon, ExternalLinkIcon, EyeIcon,
    ConfirmDialog, TableDisplayContainer, TableTh, TableTd, Link, Pagination
  },
  layout: Layout,
  props: {
    grade: Object,
    students: {
      type: Object,
      required: true
    },
    subjects: {
      type: Object,
      required: true
    },
    reports: {
      type: Object,
      required: true
    },
    flash: Object,
    errorBags: Object,
    errors: Object,
  },
  data() {
    return {
      breadcrumbs: [
        {name: this.grade.school.name, href: route('dashboard.schools.show', this.grade.school.id)},
        {name: this.grade.name, href: '#'},
      ],
      columns: ['subject', 'week', 'lesson', 'teaching date', 'topic'],
      deleteForm: useForm({}),
      isShowDeleteDialog: false,
    };
  },
  computed: {},
  methods: {},
};
</script>

<style>
</style>
