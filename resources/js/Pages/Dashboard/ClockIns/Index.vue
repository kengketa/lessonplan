<template>
  <div>
    <div class="content m-1">
      <Breadcrumbs :back="route('dashboard.schools.index')" :breadcrumbs="breadcrumbs"/>
      <PageHeading>
        Clock In Management
        <template #actions>
          <button class="button button-secondary mr-2 flex items-center" @click="openLeaveModal">
            <CalendarIcon aria-hidden="true" class="h-5 w-5 mr-2"/>
            Leave
          </button>

          <button class="button button-primary opacity-50 cursor-not-allowed" disabled>
            <ClockIcon aria-hidden="true" class="h-5 w-5 mr-2"/>
            Clock in
          </button>
        </template>
      </PageHeading>

      <div class="md:grid md:grid-cols-3 md:gap-2">
        <SearchSelectInput
          v-model="form.filters.teacher_id"
          :is-show-line="false"
          :options="allTeachers"
        />
        <SearchSelectInput
          v-model="form.filters.month"
          :is-show-line="false"
          :options="monthOptions"
        />
        <div class="flex items-end">
          <button class="button button-primary button-small mb-1 ml-1" type="button" @click="clear()">Clear</button>
          <button class="button button-primary button-small mb-1 ml-1" type="button" @click="generate()">Generate
          </button>
        </div>
      </div>

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
            v-for="(item, itemIndex) in clockIns.data"
            :key="item"
            :class="itemIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
          >
            <TableTd>
              <span>{{ item.teacher_name }}</span>
            </TableTd>
            <TableTd>
              <span>{{ item.date }}</span>
            </TableTd>
            <TableTd>
              <span v-if="item.clock_in">{{ item.clock_in }}</span>
              <span v-if="!item.clock_in" class="text-red-500">{{ item.comment }}</span>
            </TableTd>
            <TableTd>
              <span v-if="item.clock_out">{{ item.clock_out }}</span>
            </TableTd>
          </tr>
        </template>
        <template #pagination>
          <Pagination :data="clockIns.meta.pagination"></Pagination>
        </template>
      </TableDisplayContainer>
    </div>

    <div v-if="showLeaveModal" aria-labelledby="modal-title" aria-modal="true"
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showLeaveModal = false"></div>

        <div
          class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
          <form @submit.prevent="submitLeave">
            <div>
              <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100">
                <CalendarIcon aria-hidden="true" class="h-6 w-6 text-indigo-600"/>
              </div>
              <div class="mt-3 text-center sm:mt-5">
                <h3 id="modal-title" class="text-lg leading-6 font-medium text-gray-900">Leave</h3>
                <div class="mt-4 text-left space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 w-full">Select Teacher</label>
                    <SearchSelectInput
                      v-model="leaveForm.teacher_id"
                      :options="allTeachers"
                      class="mt-1"
                      placeholder="Select a teacher..."
                    />
                    <div v-if="leaveForm.errors.teacher_id" class="text-red-500 text-xs mt-1">
                      {{ leaveForm.errors.teacher_id }}
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Leave Date</label>
                    <input
                      v-model="leaveForm.date"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      required
                      type="date"
                    />
                    <div v-if="leaveForm.errors.date" class="text-red-500 text-xs mt-1">{{
                        leaveForm.errors.date
                      }}
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Reason for Leave</label>
                    <textarea
                      v-model="leaveForm.reason"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      placeholder="Please provide a reason..."
                      required
                      rows="3"
                    ></textarea>
                    <div v-if="leaveForm.errors.reason" class="text-red-500 text-xs mt-1">{{
                        leaveForm.errors.reason
                      }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
              <button
                :disabled="leaveForm.processing"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:col-start-2 sm:text-sm disabled:opacity-50"
                type="submit"
              >
                {{ leaveForm.processing ? 'Submitting...' : 'Submit' }}
              </button>
              <button
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:col-start-1 sm:text-sm"
                type="button"
                @click="showLeaveModal = false"
              >
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
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
import {UserAddIcon, AcademicCapIcon, ClockIcon, CalendarIcon} from "@heroicons/vue/solid";
import {Link} from "@inertiajs/inertia-vue3";
import SearchSelectInput from "@/Components/SearchSelectInput";
import {useForm} from '@inertiajs/inertia-vue3';

export default {
  name: "ClockInIndex",
  layout: Layout,
  components: {
    Pagination,
    PageHeading,
    TableDisplayContainer,
    TableTh,
    TableTd,
    Breadcrumbs,
    UserAddIcon,
    AcademicCapIcon,
    Link,
    ClockIcon,
    CalendarIcon,
    SearchSelectInput,
  },
  props: {
    clockIns: Object,
    filters: Object,
    title: String,
    allTeachers: {
      type: Object,
      required: true
    },
    monthOptions: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      form: useForm({
        filters: {
          teacher_id: this.filters.teacher_id != null ? parseInt(this.filters.teacher_id) : null,
          month: this.filters.month
        }
      }),
      showLeaveModal: false,
      leaveForm: useForm({
        teacher_id: null,
        date: '',
        reason: '',
      }),
      breadcrumbs: [{name: 'Schools', href: "#"}],
      columns: ['teacher', 'date', 'clock in', 'clock out'],
    };
  },
  methods: {
    openLeaveModal() {
      this.leaveForm.reset();
      this.leaveForm.clearErrors();
      this.showLeaveModal = true;
    },
    submitLeave() {
      this.leaveForm.post(route('dashboard.clock_ins.leave_request'), {
        preserveScroll: true,
        onSuccess: () => {
          this.showLeaveModal = false;
          this.leaveForm.reset();
        },
      });
    },
    generate() {
      let payload = {
        month: this.form.filters.month,
        teacher: this.form.filters.teacher_id ?? null
      };
      this.$inertia.post(route('dashboard.clock_ins.generate_report', payload))
    },
    clear() {
      this.$inertia.visit(route('dashboard.clock_ins.index'))
    },
    debounceSearch() {
      clearTimeout(this.debounce);
      this.debounce = setTimeout(() => {
        this.typing = null;
        this.sendRequest();
      }, 500);
    },
    sendRequest() {
      this.$inertia.get(this.route("dashboard.clock_ins.index", this.form));
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
};
</script>
