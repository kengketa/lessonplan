<template>
  <div>
    <Breadcrumbs
      :back="route('dashboard.schools.index')"
      :breadcrumbs="breadcrumbs"
    />
    <div class="w-full flex justify-end -mt-10">
      <ToggleInput id="adminMode" v-model="adminMode" label="Admin Mode"/>
    </div>

    <div class="w-full text-4xl flex justify-center uppercase font-bold text-blue-800">
      <p>{{ grade.name }}</p>
    </div>
    <div class="w-full">
      <DayAndSubjectCard v-model="activeDayAndSubject" :admin-mode="adminMode" :grade="grade"/>
    </div>
    <div class="grid grid-cols-2 gap-2 mt-8">
      <div class="h-screen overflow-y-scroll rounded-lg overflow-x-hidden pb-8">
        <Card>
          {{ activeDayAndSubject }}
        </Card>
      </div>
      <div class="-my-4">
        <TableDisplayContainer>
          <template #header-title>
            <th class="py-2 font-bold text-blue-800 uppercase" colspan="5">Attendance</th>
          </template>
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
                <div class="w-full flex justify-center">
                  <button class="rounded-full h-6 w-6 cursor-pointer border border-gray-300 overflow-hidden"
                          @click="toggleNameCheck(student)">
                    <div :class="student.present ? 'text-lime-500':'text-transparent'"
                         class="flex justify-center items-center">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3.0"
                           viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="m4.5 12.75 6 6 9-13.5" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </div>
                  </button>
                </div>
              </TableTd>
            </tr>
          </template>
        </TableDisplayContainer>
        <input v-if="adminMode" accept=".xlsx, .xls" name="excelFile" type="file" @input="uploadExcel">
      </div>
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
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Form from "@/Components/Form.vue";
import {Inertia} from '@inertiajs/inertia';
import DayAndSubjectCard from "@/Components/DayAndSubjectCard.vue";
import ToggleInput from "@/Components/ToggleInput.vue";

export default {
  name: 'GradeShow',
  components: {
    ToggleInput,
    DayAndSubjectCard,
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
    attendances: {
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
      adminMode: false,
      breadcrumbs: [
        {name: this.grade.school.name, href: route('dashboard.schools.show', this.grade.school.id)},
        {name: this.grade.name, href: '#'},
      ],
      columns: ['#', 'code', 'Full Name', 'nickname'],
      isShowDeleteDialog: false,
      students: this.enrollments.map(en => ({
        ...en.student,
        number_in_grade: en.number_in_grade,
        present: this.attendances.some(attendance => attendance.student_id === en.student.id)
      })),
      activeDayAndSubject: null
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
      const res = await axios.post(this.route('dashboard.attendances.store', this.grade.id), {
        student_id: foundStudent.id,
        present: foundStudent.present
      });
    },
    async uploadExcel(event) {
      const file = event.target.files[0];
      const formData = new FormData();
      formData.append('file', file);
      const response = await Inertia.post(this.route('dashboard.enrollments.store', this.grade.id), formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
          'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
      });
    },
  },
  watch: {
    activeDayAndSubject() {
      
    }
  }
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
