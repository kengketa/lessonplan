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
    <div>
      <input accept=".xlsx, .xls" name="excelFile" type="file" @input="uploadExcel">
    </div>
    <div class="w-1/2 h-screen overflow-y-scroll rounded-lg overflow-x-hidden hide-scroll">
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
            v-for="(student, studentIndex) in this.students"
            :key="student"
            :class="studentIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
          >
            <TableTd>
              <p>{{ student.number_in_grade }}</p>
            </TableTd>
            <TableTd>
              {{ student.number }}
            </TableTd>
            <TableTd>
              {{ student.prefix }} {{ student.first_name }} {{ student.last_name }}
            </TableTd>
            <TableTd>
              {{ student.nick_name }}
            </TableTd>
            <TableTd>
              <div>
                <button :class="student.present ?'bg-lime-300 border-lime-300':'border-gray-300'"
                        class="rounded-full h-6 w-6 cursor-pointer border"
                        @click="toggleNameCheck(student)"></button>
              </div>
            </TableTd>
          </tr>
        </template>
      </TableDisplayContainer>
    </div>


    <!--    <div class="mt-10">-->
    <!--      subjects <br>-->
    <!--      {{ subjects }}-->
    <!--    </div>-->
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
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Form from "@/Components/Form.vue";
import {Inertia} from '@inertiajs/inertia';

export default {
  name: 'GradeShow',
  components: {
    Form, SelectInput, TextInput,
    Card, PageHeading, Breadcrumbs, DataDisplayContainer, AddGradeModal,
    DataDisplayRow, PencilIcon, TrashIcon, ExternalLinkIcon, EyeIcon,
    ConfirmDialog, TableDisplayContainer, TableTh, TableTd, Link, Pagination
  },
  layout: Layout,
  props: {
    grade: Object,
    enrollments: {
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
  mounted() {

  },
  data() {
    return {
      breadcrumbs: [
        {name: this.grade.school.name, href: route('dashboard.schools.show', this.grade.school.id)},
        {name: this.grade.name, href: '#'},
      ],
      columns: ['#', 'code', 'Full Name', 'nickname'],
      isShowDeleteDialog: false,
      students: this.enrollments.map(en => ({...en.student, number_in_grade: en.number_in_grade, present: false}))
    };
  },
  computed: {},
  methods: {
    async toggleNameCheck(student) {
      const foundStudent = this.students.find(s => s.number === student.number);
      if (!foundStudent) {
        return;
      }
      foundStudent.present = !foundStudent.present;
      // const res = await axios.post(this.route('dashboard.attendances.check'), {
      //   studentId: foundStudent.id,
      //   present: foundStudent.present
      // });
    },
    async uploadExcel(event) {
      const file = event.target.files[0];
      const formData = new FormData();
      formData.append('file', file);
      const response = await Inertia.post(this.route('dashboard.grades.enrollment', this.grade.id), formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
          'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
      });
    }
  },
};
</script>

<style>
.hide-scroll {
  -ms-overflow-style: none; /* for Internet Explorer, Edge */
  scrollbar-width: none; /* for Firefox */
  overflow-y: scroll;
}

.hide-scroll::-webkit-scrollbar {
  display: none; /* for Chrome, Safari, and Opera */
}
</style>
