<template>
  <div>
    <ConfirmDialog
      v-model="isShowDeleteDialog"
      title="Delete Product?"
      :body="`The School: ${ school.name} and its related data will be deleted. This action cannot be undone.`"
      confirm-text="Delete School"
      :confirm-event="deleteSchool"
    />
    <ConfirmDialog
      v-model="showDeleteGradeDialog"
      title="Delete Grade?"
      :body="`Grade: ${ deletingGrade ? deletingGrade.name : null} will be deleted.`"
      confirm-text="Delete Grade"
      :confirm-event="deleteGrade"
    />
    <ConfirmDialog
      v-model="showDeleteSubjectDialog"
      title="Delete subject?"
      :body="`Subject: ${ deletingSubject ? deletingSubject.name : null} will be deleted.`"
      confirm-text="Delete Subject"
      :confirm-event="deleteSubject"
    />
    <ConfirmDialog
      v-model="showRemoveTeacherDialog"
      title="Remove teacher from this school?"
      :body="`Teacher: ${ removingTeacher ? removingTeacher.name : null} will be removed from this school.`"
      confirm-text="Remove"
      :confirm-event="removeTeacher"
    />
    <Breadcrumbs
      :breadcrumbs="breadcrumbs"
      :back="route('dashboard.schools.index')"
    />
    <PageHeading>
    <span class="hidden lg:inline">
      <span class="max-w-xs truncate text-2xl">{{ school.name }}</span>
    </span>
      <template #actions>
        <Link
          :href="route('dashboard.reports.create', school.id)"
          class="button button-primary mr-2"
        >
          <DocumentReportIcon class="h-5 w-5 mr-2" aria-hidden="true" />
          Create Lesson plan
        </Link>
        <Link
          v-if="$page.props.authUserRole === 'ADMIN' || $page.props.authUserRole === 'SUPER_ADMIN'"
          :href="route('dashboard.schools.edit', school.id)"
          class="button button-primary mr-2"
        >
          <PencilIcon class="h-5 w-5 mr-2" aria-hidden="true" />
          Edit
        </Link>
        <form v-if="$page.props.authUserRole === 'ADMIN' || $page.props.authUserRole === 'SUPER_ADMIN'"
              class="inline-flex" @submit.prevent="isShowDeleteDialog = true">
          <button type="submit" class="button button-danger">
            <TrashIcon class="h-5 w-5 mr-2" aria-hidden="true" />
            Delete
          </button>
        </form>
      </template>
    </PageHeading>
    <section class="mt-4">
      <div class="md:grid md:grid-cols-6 md:gap-2">
        <SearchSelectInput
          :options="school.years"
          :is-show-line="false"
          v-model="filterForm.filters.academic_year"
          label="Academicyear"
        />
        <SearchSelectInput
          :options="school.semesters"
          v-model="filterForm.filters.semester"
          :is-show-line="false"
          label="Semester"
        />
        <SearchSelectInput
          :options="school.teachers"
          :is-show-line="false"
          v-model="filterForm.filters.teacher"
          label="Teacher"
        />
        <SearchSelectInput
          :options="school.grades.data"
          v-model="filterForm.filters.grade"
          :is-show-line="false"
          label="filter class"
        />
        <SearchSelectInput
          :options="school.subjects"
          v-model="filterForm.filters.subject"
          :is-show-line="false"
          label="filter subject"
        />
        <div class="flex items-end mt-2 md:mt-0">
          <button @click="print()" type="button" class="button button-primary button-small mb-1">Print</button>
          <button @click="clearFilter()" type="button" class="button button-primary button-small mb-1 ml-1">
            Clear
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
          <TableTh>Actions</TableTh>
        </template>
        <template #body>
          <tr
            class="cursor-pointer transition ease-in-out duration-200 hover:bg-gray-200"
            @click="openReportEdit(item)"
            v-for="(item, itemIndex) in reports.data"
            :key="item"
            :class="itemIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
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
                {{ item.week_number }}
              </div>
            </TableTd>
            <TableTd>
              <div>
                {{ item.lesson_number }}
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
            <TableTd>
              <p v-if="item.approver != null" class="text-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </p>
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
            <button v-if="showSchoolInformation" @click="showSchoolInformation = false" type="button"
                    class="text-gray-500 mr-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                   stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
              </svg>
            </button>
            <button v-if="!showSchoolInformation" @click="showSchoolInformation = true" type="button"
                    class="text-gray-500 mr-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                   stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
          </div>
        </div>
      </div>
      <div class="mt-5 border-t border-gray-200 transition transform translate duration-500 ease-in-out"
           :class="!showSchoolInformation ? 'h-0':'h-100'"
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
              <div class="relative ml-1 my-1" v-for="grade in school.grades.data">
                <p class="bg-yellow-100 px-2 py-2 mx-2 mb-2 rounded-md">
                  <span>{{ grade.name }}</span>
                </p>
                <button v-if="$page.props.authUserRole === 'ADMIN' || $page.props.authUserRole === 'SUPER_ADMIN'"
                        @click="deleteGradePreConfirm(grade)" class="absolute -top-1 right-0 text-red-500">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                       stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </button>
              </div>
            </div>
            <button v-if="$page.props.authUserRole === 'ADMIN' || $page.props.authUserRole === 'SUPER_ADMIN'"
                    @click="showAddGradeModal=true" class="button button-primary button-small">Add Grade
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
                        @click="deleteSubjectPreConfirm(subject)" class="absolute -top-2 -right-2 text-red-500">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                       stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </button>
              </div>
            </div>
            <button v-if="$page.props.authUserRole === 'ADMIN' || $page.props.authUserRole === 'SUPER_ADMIN'"
                    @click="showAddSubjectModal=true" class="button button-primary button-small">Add Subject
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
                        @click="removeTeacherPreConfirm(teacher)" class="absolute -top-2 -right-2 text-red-500">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                       stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </button>
              </div>
            </div>
            <button v-if="$page.props.authUserRole === 'ADMIN' || $page.props.authUserRole === 'SUPER_ADMIN'"
                    @click="showAddTeacherModal=true" class="button button-primary button-small">Add Teacher
            </button>
          </DataDisplayRow>
        </DataDisplayContainer>
      </div>
    </Card>
    <AddGradeModal v-model="showAddGradeModal" :school-id="school.id" />
    <AddSubjectModal v-model="showAddSubjectModal" :school-id="school.id" />
    <AddTeacherModal v-model="showAddTeacherModal" :school="school" />
    <PrintModal v-model="showPrintModal" :print-list="printList" />
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
  DocumentReportIcon
} from '@heroicons/vue/solid';
import {useForm} from '@inertiajs/inertia-vue3';
import Button from '@/Jetstream/Button';
import AddSubjectModal from "../../../Components/Forms/AddSubjectModal";
import TextInput from "../../../Components/TextInput";
import SearchSelectInput from "../../../Components/SearchSelectInput";
import AddTeacherModal from "../../../Components/Forms/AddTeacherModal";
import PrintModal from "../../../Components/Forms/PrintModal";

export default {
  name: 'SchoolShow',
  components: {
    PrintModal,
    AddTeacherModal,
    SearchSelectInput,
    TextInput,
    AddSubjectModal,
    Card, PageHeading, Breadcrumbs, DataDisplayContainer, AddGradeModal,
    DataDisplayRow, PencilIcon, TrashIcon, ExternalLinkIcon, EyeIcon,
    ConfirmDialog, TableDisplayContainer, TableTh, TableTd, Link, DocumentReportIcon,
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
      breadcrumbs: [
        {name: 'Schools', href: route('dashboard.schools.index')},
        {name: this.school.name, href: '#'},
      ],
      columns: ['teacher', 'grade', 'subject', 'week', 'lesson', 'teaching date', 'topic'],
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
          teacher: this.filters ? parseInt(this.filters.teacher) : 0
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
    };
  },
  mounted() {
    if (this.$page.props.authUserRole === 'TEACHER' && this.filterForm.filters.teacher === 0) {
      this.filterForm.filters.teacher = this.$page.props.authUser.id;
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
  },
  methods: {
    openReportEdit(report) {
      this.$inertia.visit(route('dashboard.reports.edit', report.id))
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
