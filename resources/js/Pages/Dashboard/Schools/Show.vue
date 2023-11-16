<template>
  <div>
    <ConfirmDialog
      v-model="isShowDeleteDialog"
      :body="`The School: ${ school.name} and its related data will be deleted. This action cannot be undone.`"
      :confirm-event="deleteSchool"
      confirm-text="Delete School"
      title="Delete Product?"
    />
    <ConfirmDialog
      v-model="showDeleteGradeDialog"
      :body="`Grade: ${ deletingGrade ? deletingGrade.name : null} will be deleted.`"
      :confirm-event="deleteGrade"
      confirm-text="Delete Grade"
      title="Delete Grade?"
    />
    <ConfirmDialog
      v-model="showDeleteSubjectDialog"
      :body="`Subject: ${ deletingSubject ? deletingSubject.name : null} will be deleted.`"
      :confirm-event="deleteSubject"
      confirm-text="Delete Subject"
      title="Delete subject?"
    />
    <ConfirmDialog
      v-model="showRemoveTeacherDialog"
      :body="`Teacher: ${ removingTeacher ? removingTeacher.name : null} will be removed from this school.`"
      :confirm-event="removeTeacher"
      confirm-text="Remove"
      title="Remove teacher from this school?"
    />
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
          :options="school.grades.data"
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
    <Card>
      <div class="md:flex md:items-center md:justify-between">
        <div class="w-full flex justify-between">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              School Information
            </h3>
          </div>
          <div>
            <button v-if="showSchoolInformation" class="text-gray-500 mr-4" type="button"
                    @click="showSchoolInformation = false">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                   xmlns="http://www.w3.org/2000/svg">
                <path d="M5 15l7-7 7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
              </svg>
            </button>
            <button v-if="!showSchoolInformation" class="text-gray-500 mr-4" type="button"
                    @click="showSchoolInformation = true">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                   xmlns="http://www.w3.org/2000/svg">
                <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
      <div :class="!showSchoolInformation ? 'h-0':'h-100'"
           class="mt-5 border-t border-gray-200 transition transform translate duration-500 ease-in-out"
      >
        <DataDisplayContainer>
          <DataDisplayRow>
            <template #label>
              Name
            </template>
            {{ school.name }}
          </DataDisplayRow>
          <DataDisplayRow>
            <template #label>
              Address
            </template>
            {{ school.address }}
          </DataDisplayRow>
          <DataDisplayRow>
            <template #label>
              Grades
            </template>
            <div class="flex flex-wrap">
              <div v-for="grade in school.grades.data" class="relative ml-1 my-1">
                <p class="bg-yellow-100 px-2 py-2 mx-2 mb-2 rounded-md">
                  <span>{{ grade.name }}</span>
                </p>
                <button v-if="$page.props.authUserRole === 'ADMIN' || $page.props.authUserRole === 'SUPER_ADMIN'"
                        class="absolute -top-1 right-0 text-red-500" @click="deleteGradePreConfirm(grade)">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                       xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                          stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"/>
                  </svg>
                </button>
              </div>
            </div>
            <button v-if="$page.props.authUserRole === 'ADMIN' || $page.props.authUserRole === 'SUPER_ADMIN'"
                    class="button button-primary button-small" @click="showAddGradeModal=true">Add Grade
            </button>
          </DataDisplayRow>
          <DataDisplayRow>
            <template #label>
              Subjects
            </template>
            <div class="flex flex-wrap">
              <div v-for="subject in school.subjects" class="relative bg-yellow-100 px-2 py-2 mx-2 mb-2 rounded-md">
                <span class="uppercase">{{ subject.name }}</span>
                <button v-if="$page.props.authUserRole === 'ADMIN' || $page.props.authUserRole === 'SUPER_ADMIN'"
                        class="absolute -top-2 -right-2 text-red-500" @click="deleteSubjectPreConfirm(subject)">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                       xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                          stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"/>
                  </svg>
                </button>
              </div>
            </div>
            <button v-if="$page.props.authUserRole === 'ADMIN' || $page.props.authUserRole === 'SUPER_ADMIN'"
                    class="button button-primary button-small" @click="showAddSubjectModal=true">Add Subject
            </button>
          </DataDisplayRow>
          <DataDisplayRow>
            <template #label>
              Teachers
            </template>
            <div class="flex flex-wrap">
              <div v-for="teacher in school.teachers" class="relative bg-yellow-100 px-2 py-2 mx-2 mb-2 rounded-md">
                <span>{{ teacher.name }}</span>
                <button v-if="$page.props.authUserRole === 'ADMIN' || $page.props.authUserRole === 'SUPER_ADMIN'"
                        class="absolute -top-2 -right-2 text-red-500" @click="removeTeacherPreConfirm(teacher)">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                       xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                          stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"/>
                  </svg>
                </button>
              </div>
            </div>
            <button v-if="$page.props.authUserRole === 'ADMIN' || $page.props.authUserRole === 'SUPER_ADMIN'"
                    class="button button-primary button-small" @click="showAddTeacherModal=true">Add Teacher
            </button>
          </DataDisplayRow>
        </DataDisplayContainer>
      </div>
    </Card>
    <AddGradeModal v-model="showAddGradeModal" :school-id="school.id"/>
    <AddSubjectModal v-model="showAddSubjectModal" :school-id="school.id"/>
    <AddTeacherModal v-model="showAddTeacherModal" :school="school"/>
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
import AddGradeModal from "@/Components/Forms/AddGradeModal";
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
import AddSubjectModal from "../../../Components/Forms/AddSubjectModal";
import TextInput from "../../../Components/TextInput";
import SearchSelectInput from "../../../Components/SearchSelectInput";
import AddTeacherModal from "../../../Components/Forms/AddTeacherModal";
import PrintModal from "../../../Components/Forms/PrintModal";
import SuperApproveModal from "@/Components/Forms/SuperApproveModal";

export default {
  name: 'SchoolShow',
  components: {
    SuperApproveModal,
    PrintModal,
    AddTeacherModal,
    SearchSelectInput,
    TextInput,
    AddSubjectModal,
    Card, PageHeading, Breadcrumbs, DataDisplayContainer, AddGradeModal,
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
      deleteForm: useForm({}),
      subjectForm: useForm({
        id: null
      }),
      teacherForm: useForm({
        id: null
      }),
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
      showDeleteGradeDialog: false,
      showDeleteSubjectDialog: false,
      showRemoveTeacherDialog: false,
      showAddGradeModal: false,
      showAddSubjectModal: false,
      showAddTeacherModal: false,
      showPrintModal: false,
      deletingGrade: null,
      deletingSubject: null,
      removingTeacher: null,
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
    'filterForm.filters.academic_year': function () {
      this.submitSearch()
    },
    'filterForm.filters.semester': function () {
      this.submitSearch()
    },
    'filterForm.filters.grade': function () {
      this.submitSearch()
    },
    'filterForm.filters.subject': function () {
      this.submitSearch()
    },
    'filterForm.filters.teacher': function () {
      this.submitSearch()
    },
    'filterForm.filters.week': function () {
      this.submitSearch()
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
      let url = route('dashboard.schools.show', this.school.id);
      this.$inertia.visit(url,
        {
          preserveScroll: true
        });
    },
    submitSearch() {
      let url = route('dashboard.schools.show', this.school.id);
      this.filterForm.get(url);
    },
    removeTeacherPreConfirm(teacher) {
      this.removingTeacher = teacher;
      this.showRemoveTeacherDialog = true;
    },
    deleteGradePreConfirm(grade) {
      this.deletingGrade = grade;
      this.showDeleteGradeDialog = true;
    },
    deleteSubjectPreConfirm(subject) {
      this.deletingSubject = subject;
      this.showDeleteSubjectDialog = true;
    },
    removeTeacher() {
      this.teacherForm.id = this.removingTeacher.id;
      this.teacherForm.post(route('dashboard.schools.remove_teacher', this.school.id));
    },
    deleteGrade() {
      this.deleteForm.delete(route('dashboard.grades.destroy', this.deletingGrade.id));
    },
    deleteSubject() {
      this.subjectForm.id = this.deletingSubject.id
      this.subjectForm.post(route('dashboard.schools.delete_subject', this.school.id));
    },
    deleteSchool() {
      this.deleteForm.delete(route('dashboard.schools.destroy', {school: this.school.id}));
    },
  },
};
</script>

<style>
</style>
