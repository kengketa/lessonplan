<template>
  <div>
    <Breadcrumbs
      :back="route('dashboard')"
      :breadcrumbs="breadcrumbs"
    />
    <PageHeading>
    <span class="hidden lg:inline">
      <span class="max-w-xs truncate text-2xl">{{ school.name }}</span>
    </span>
      <template #actions>
        <Link
          :href="route('dashboard.schools.calendar', school.id)"
          class="button button-primary mr-2"
        >
          <CalendarIcon aria-hidden="true" class="h-5 w-5 mr-2"/>
          Calendar
        </Link>
        <Link
          :href="route('dashboard.misbehaviors.index', school.id)"
          class="button button-primary mr-2"
        >
          <DocumentReportIcon aria-hidden="true" class="h-5 w-5 mr-2"/>
          MB Report
        </Link>
        <Link
          :href="route('dashboard.reports.create', school.id)"
          class="button button-primary mr-2"
        >
          <DocumentReportIcon aria-hidden="true" class="h-5 w-5 mr-2"/>
          Create Lesson plan
        </Link>
        <Link
          v-if="$page.props.authUserRole === 'ADMIN' || $page.props.authUserRole === 'SUPER_ADMIN'"
          :href="route('dashboard.schools.edit', school.id)"
          class="button button-primary mr-2"
        >
          <PencilIcon aria-hidden="true" class="h-5 w-5 mr-2"/>
          Edit
        </Link>
        <form v-if="$page.props.authUserRole === 'ADMIN' || $page.props.authUserRole === 'SUPER_ADMIN'"
              class="inline-flex" @submit.prevent="isShowDeleteDialog = true">
          <button class="button button-danger" type="submit">
            <TrashIcon aria-hidden="true" class="h-5 w-5 mr-2"/>
            Delete
          </button>
        </form>
      </template>
    </PageHeading>
    <section class="mt-4">
      <div class="md:grid md:grid-cols-6 md:gap-2">
        <SearchSelectInput
          v-model="filterForm.filters.academic_year"
          :is-show-line="false"
          :options="school.years"
          label="Academicyear"
        />
        <SearchSelectInput
          v-model="filterForm.filters.semester"
          :is-show-line="false"
          :options="school.semesters"
          label="Semester"
        />
        <SearchSelectInput
          v-model="filterForm.filters.teacher"
          :is-show-line="false"
          :options="school.teachers"
          label="Teacher"
        />
        <SearchSelectInput
          v-model="filterForm.filters.grade"
          :is-show-line="false"
          :options="school.grades"
          label="filter class"
        />
        <SearchSelectInput
          v-model="filterForm.filters.subject"
          :is-show-line="false"
          :options="school.subjects"
          label="filter subject"
        />
        <SearchSelectInput
          v-model="filterForm.filters.week"
          :is-show-line="false"
          :options="school.weeks"
          label="week"
        />
        <div class="flex items-end mt-2 md:mt-0">
          <button class="button button-primary button-small mb-1" type="button" @click="print()">Print</button>
          <button class="button button-primary button-small mb-1 ml-1" type="button" @click="clearFilter()">
            Clear
          </button>
          <button v-if="$page.props.authUserRole === 'ADMIN' || $page.props.authUserRole === 'SUPER_ADMIN'"
                  class="button button-primary button-small mb-1 ml-1 whitespace-nowrap" type="button"
                  @click="showSuperApproveModal = true">
            Super Approve
          </button>
        </div>
      </div>
      <TableDisplayContainer class="mt-4">
        <template #header>
          <TableTh v-for="(column,index) in columns" :key="index">
            <div class="flex">
              <span>{{ column }}</span>
            </div>
          </TableTh>
        </template>
        <template #body>
          <tr
            v-for="(item, itemIndex) in reports.data"
            :key="itemIndex"
            :class="computedRowColour(itemIndex,item)"
            class="cursor-pointer transition ease-in-out duration-200"
            @click="openReportEdit(item)"
          >
            <TableTd>
              <div>
                {{ item.teacher_name }}
              </div>
            </TableTd>
            <TableTd>
              <div>
                {{ item.grade_name }}
              </div>
            </TableTd>
            <TableTd>
              <div>
                <span class="capitalize">{{ item.subject }}</span>
              </div>
            </TableTd>
            <TableTd>
              <div>
                {{ item.week_number }}/{{ item.lesson_number }}
              </div>
            </TableTd>
            <TableTd>
              <div>
                {{ item.date }}
              </div>
            </TableTd>
            <TableTd>
              <div>
                <ul>
                  <li v-for="(topic,index) in item.topics" :key="index">
                    <div class="w-80 truncate">
                      {{ topic }}
                    </div>
                  </li>
                </ul>
              </div>
            </TableTd>
          </tr>
        </template>
        <template #pagination>
          <Pagination :data="reports.meta.pagination"></Pagination>
        </template>
      </TableDisplayContainer>
    </section>
    <PrintModal v-model="showPrintModal" :print-list="printList" :school-id="school.id"/>
    <SuperApproveModal v-model="showSuperApproveModal" :school="school" :week="filterForm.filters.week"/>
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
import Pagination from "@/Components/Pagination";

import {
  ExternalLinkIcon,
  EyeIcon,
  PencilIcon,
  TrashIcon,
  CheckCircleIcon,
  ExclamationCircleIcon,
  DocumentReportIcon,
  CalendarIcon
} from '@heroicons/vue/solid';
import {useForm} from '@inertiajs/inertia-vue3';
import Button from '@/Jetstream/Button';
import SearchSelectInput from "@/Components/SearchSelectInput";
import PrintModal from "@/Components/Forms/PrintModal";
import SuperApproveModal from "@/Components/Forms/SuperApproveModal";

export default {
  name: 'SchoolShow',
  components: {
    SuperApproveModal,
    PrintModal,
    SearchSelectInput,
    Card, PageHeading, Breadcrumbs, DataDisplayContainer,
    DataDisplayRow, PencilIcon, TrashIcon, ExternalLinkIcon, EyeIcon,
    ConfirmDialog, TableDisplayContainer, TableTh, TableTd, Link, DocumentReportIcon,
    CalendarIcon,
    Pagination
  },
  layout: Layout,
  props: {
    school: Object,
    reports: {
      type: Object,
      required: true
    },
    filters: {
      type: Object,
      default: null
    },
    flash: Object,
    errorBags: Object,
    errors: Object,
  },
  data() {
    return {
      breadcrumbs: [],
      columns: ['teacher', 'grade', 'subject', 'W/L', 'teaching date', 'topic'],
      filterForm: useForm({
        filters: {
          academic_year: this.filters ? parseInt(this.filters.academic_year) : 0,
          semester: this.filters ? parseInt(this.filters.semester) : 0,
          grade: this.filters ? parseInt(this.filters.grade) : 0,
          subject: this.filters ? parseInt(this.filters.subject) : 0,
          teacher: this.filters ? parseInt(this.filters.teacher) : 0,
          week: this.filters ? parseInt(this.filters.week) : 0
        }
      }),
      isShowDeleteDialog: false,
      showPrintModal: false,
      printList: [],
      showSchoolInformation: false,
      showSuperApproveModal: false,
    };
  },
  mounted() {
    if (this.$page.props.authUserRole === 'TEACHER' && this.filterForm.filters.teacher === 0) {
      this.filterForm.filters.teacher = this.$page.props.authUser.id;
    }
    if (this.$page.props.authUserRole === 'TEACHER') {
      let breadcrumb = {name: this.school.name, href: '#'};
      this.breadcrumbs.push(breadcrumb);
    }
    if (this.$page.props.authUserRole === 'ADMIN' || this.$page.props.authUserRole === 'SUPER_ADMIN') {
      let breadcrumb = {name: 'Schools', href: route('dashboard.schools.index')};
      this.breadcrumbs.push(breadcrumb);
      breadcrumb = {name: this.school.name, href: '#'};
      this.breadcrumbs.push(breadcrumb);
    }
  },
  computed: {},
  watch: {
    showPrintModal() {
      if (this.showPrintModal === false) {
        setTimeout(() => {
          this.printList = [];
        }, 200);
      }
    },
    'filterForm.filters': {
      handler() {
        this.submitSearch()
      },
      deep: true
    },
  },
  methods: {
    computedRowColour(index, item) {
      if (item.approver) {
        return index % 2 === 0 ? 'bg-green-50 hover:bg-green-200' : 'bg-green-100 hover:bg-green-200'
      }
      return index % 2 === 0 ? 'bg-white hover:bg-gray-200' : 'bg-gray-50 hover:bg-gray-200'
    },
    openReportEdit(report) {
      //this.$inertia.visit(route('dashboard.reports.edit', report.id))
      let url = route('dashboard.reports.edit', report.id);
      window.open(url, '_blank');
    },
    print() {
      this.reports.data.forEach(report => {
        let newPrintList = {
          id: report.id,
          print: false,
          teacher_name: report.teacher_name,
          grade: report.grade_name,
          subject: report.subject,
          week_number: report.week_number,
          lesson_number: report.lesson_number,
          topics: report.topics
        }
        this.printList.push(newPrintList);
      });
      this.showPrintModal = true;
    },
    clearFilter() {
      let url = route('dashboard.reports.index', this.school.id);
      this.$inertia.visit(url,
        {
          preserveScroll: true
        });
    },
    submitSearch() {
      let url = route('dashboard.reports.index', this.school.id);
      this.filterForm.get(url);
    },
  },
};
</script>

<style>
</style>
