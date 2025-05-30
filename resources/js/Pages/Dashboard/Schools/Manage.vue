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
      </template>
    </PageHeading>
    <Card>
      <div class="md:flex md:items-center md:justify-between">
        <div class="w-full flex justify-between">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Manage School
            </h3>
          </div>
        </div>
      </div>
      <div class="mt-5 border-t border-gray-200 transition transform translate duration-500 ease-in-out"
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
    <LoadingModal v-model="isSubmitting"/>
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
import LoadingModal from "@/Components/LoadingModal.vue";

export default {
  name: 'SchoolShow',
  components: {
    LoadingModal,
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
      isSubmitting: false
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
    async handleExcelUpload(event) {
      const result = await this.$swal.fire({
        title: "Do you want to import the lesson plans from excel?",
        showCancelButton: false,
        showDenyButton: true,
        confirmButtonText: "Import",
        denyButtonText: `Cancel`,
        denyButtonColor: 'gray'
      });
      if (result.isDenied) {
        window.location.reload();
        return;
      }
      this.isSubmitting = true;
      const file = event.target.files[0];
      if (!file) {
        return;
      }
      try {
        const formData = new FormData();
        formData.append('file', file);
        const response = await axios.post(this.route('dashboard.schools.import_my_lesson_plan', this.school.id), formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        if (response.status === 200) {
          this.$swal.fire({
            title: "Completed",
            text: "Lesson plans uploaded!",
            icon: "success"
          }).then(() => {
            window.location.reload();
          });
        }
      } catch (error) {
        this.$swal.fire({
          title: "Error",
          text: error.response.data.message,
          icon: "error"
        }).then(() => {
          window.location.reload();
        });
      }
      this.isSubmitting = false;
    },
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
