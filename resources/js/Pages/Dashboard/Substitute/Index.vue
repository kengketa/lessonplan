<template>
  <div>
    <div class="content m-1">
      <Breadcrumbs :back="route('dashboard.schools.index')" :breadcrumbs="breadcrumbs"/>
      <PageHeading>
        Substitute Management
      </PageHeading>
      <div>
        <div class="mx-auto mt-10 p-6 bg-white shadow rounded-2xl">
          <div class="">
            <h3 class="text-xl font-semibold mb-4">Today’s Substitutions ({{ currentDateTime }})</h3>
            <table class="w-full border">
              <thead class="bg-gray-100 text-left">
              <tr class="text-center">
                <th class="p-2 border">Time</th>
                <th class="p-2 border">Grade</th>
                <th class="p-2 border">Subject</th>
                <th class="p-2 border">Absent</th>
                <th class="p-2 border">Substitute</th>
              </tr>
              </thead>
              <tbody v-if="substituteData">
              <tr v-for="(sub,index) in substituteData" :key="index" class="text-center">
                <td class="p-2 border">
                  <div v-if="sub.id !== null">
                    {{ sub.start_time }} - {{ sub.end_time }}
                  </div>
                  <div v-if="sub.id === null" class="flex justify-center gap-2 items-center">
                    <input
                      :ref="'startTimeInputRef' + index"
                      v-model="form.start_time"
                      class="border-gray-300 focus:outline-none text-2xl font-bold rounded-md text-center"
                      placeholder="Enter something"
                      type="time"
                      @input="onTimeInput($event)"
                      @keyup.enter.prevent="addNewSubstitute"
                    />
                    <input
                      v-model="form.end_time"
                      class="border-gray-300 focus:outline-none text-2xl font-bold rounded-md text-center"
                      placeholder="Enter something"
                      type="time"
                      @input="onTimeInput($event)"
                      @keyup.enter.prevent="addNewSubstitute"
                    />
                  </div>
                  <div v-if="sub.id === null">
                    <p class="text-red-500 text-sm">{{ $page.props.errors.end_time }}</p>
                  </div>
                </td>
                <td class="p-2 border">
                  <div v-if="sub.id !== null">
                    {{ sub.grade }}
                  </div>
                  <div v-if="sub.id === null" class="flex justify-center gap-2 items-center">
                    <input
                      v-model="form.grade"
                      class="border-gray-300 focus:outline-none rounded-md text-center"
                      placeholder="ห้องเรียน eg. 2/1"
                      type="text"
                      @keyup.enter.prevent="addNewSubstitute"
                    />
                  </div>
                  <div v-if="sub.id === null">
                    <p class="text-red-500 text-sm">{{ $page.props.errors.grade }}</p>
                  </div>
                </td>
                <td class="p-2 border">
                  <div v-if="sub.id !== null">
                    {{ sub.subject }}
                  </div>
                  <div v-if="sub.id === null" class="flex justify-center gap-2 items-center">
                    <input
                      v-model="form.subject"
                      class="border-gray-300 focus:outline-none rounded-md text-center"
                      placeholder="วิชา"
                      type="text"
                      @keyup.enter.prevent="addNewSubstitute"
                    />
                  </div>
                  <div v-if="sub.id === null">
                    <p class="text-red-500 text-sm">{{ $page.props.errors.subject }}</p>
                  </div>
                </td>
                <td class="p-2 border">
                  <div v-if="sub.id !== null">
                    {{ sub.teacher }}
                  </div>
                  <div v-if="sub.id === null" class="flex justify-center gap-2 items-center">
                    <input
                      v-model="form.absent"
                      class="border-gray-300 focus:outline-none rounded-md text-center"
                      placeholder="ชื่อครูที่ขาด"
                      type="text"
                      @keyup.enter.prevent="addNewSubstitute"
                    />
                  </div>
                  <div v-if="sub.id === null">
                    <p class="text-red-500 text-sm">{{ $page.props.errors.absent }}</p>
                  </div>
                </td>
                <td class="p-2 border">
                  <div v-if="sub.id !== null">
                    <button v-if="sub.volunteer"
                            class="px-2 py-1 bg-green-500 rounded-md text-white cursor-pointer font-bold"
                            type="button"
                            @click="assignNewSubstitute(sub)">
                      {{ sub.volunteer }}
                    </button>
                    <button v-if="!sub.volunteer"
                            class="px-2 py-1 bg-yellow-400 rounded-md text-white cursor-pointer font-bold"
                            type="button"
                            @click="assignNewSubstitute(sub)">
                      Need
                    </button>

                  </div>
                  <div v-if="sub.id === null" class="flex justify-center gap-2 items-center">
                    <input
                      v-model="form.substitute"
                      class="border-gray-300 focus:outline-none rounded-md text-center"
                      placeholder="volunteer"
                      type="text"
                      @keyup.enter.prevent="addNewSubstitute"
                    />
                  </div>
                  <div v-if="sub.id === null">
                    <p class="text-red-500 text-sm">{{ $page.props.errors.substitute }}</p>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="p-2" colspan="5">
                  <div class="flex justify-end items-center gap-2">
                    <button v-if="hasNewSubstitute"
                            class="px-2 py-1 bg-gray-500 rounded-md text-white cursor-pointer font-bold uppercase"
                            type="button"
                            @click="clearNewSubstitute">
                      Cancle
                    </button>
                    <button v-if="hasNewSubstitute"
                            class="px-2 py-1 bg-green-500 rounded-md text-white cursor-pointer font-bold uppercase"
                            type="button"
                            @click.prevent="addNewSubstitute">
                      Save
                    </button>
                    <button v-if="!hasNewSubstitute"
                            class="px-2 py-1 bg-blue-800 rounded-md text-white cursor-pointer font-bold uppercase"
                            type="button"
                            @click="newSubstitute">
                      Add
                    </button>
                  </div>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
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
import TextInput from "@/Components/TextInput";
import SelectInput from "@/Components/SelectInput";
import {UserAddIcon, AcademicCapIcon} from "@heroicons/vue/solid";
import {Link} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import {nextTick} from "vue";

export default {
  name: "SubstituteIndex",
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
    AcademicCapIcon,
    Link
  },
  props: {
    substitutes: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      breadcrumbs: [{name: 'Substitute', href: "#"}],
      columns: ['name', 'address',],
      substituteData: [],
      form: {
        start_time: "",
        end_time: "",
        grade: "",
        subject: "",
        absent: "",
        substitute: ""
      },
      currentDateTime: ''
    };
  },
  mounted() {
    this.substituteData = this.substitutes;
    this.updateTime()
    this.timer = setInterval(this.updateTime, 1000)
  },
  beforeUnmount() {
    clearInterval(this.timer)
  },
  methods: {
    async assignNewSubstitute(sub) {
      const {value: userInput} = await this.$swal.fire({
        title: 'Assign New Substitute',
        input: 'text',
        inputValue: sub.volunteer,
        inputPlaceholder: 'Fill up your name to take this hour.',
        showCancelButton: true,
        confirmButtonText: 'Assign',
        cancelButtonText: 'Cancel',
        inputValidator: (value) => {
          if (!value) {
            return 'Write teacher name';
          }
        }
      });
      if (!userInput) {
        return;
      }
      Inertia.patch(this.route("dashboard.substitute.update", sub.id), {
          volunteer: userInput
        }, {
          onSuccess: async () => {
            await this.$swal.fire({
              title: "Assigned",
              text: `Awesome! You assigned ${userInput} to teach`,
              icon: "success"
            });
            window.location.reload();
          }
        }
      )
    },
    updateTime() {
      const now = new Date()
      const date = now.toLocaleDateString()
      const time = now.toLocaleTimeString(undefined, {hour12: false})
      this.currentDateTime = `${date} ${time}`
    },
    simulateTab(currentElement) {
      const focusable = 'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])';
      const focusables = Array.from(document.querySelectorAll(focusable))
        .filter(el => !el.disabled && el.offsetParent !== null);
      const index = focusables.indexOf(currentElement);
      if (index > -1 && index + 1 < focusables.length) {
        focusables[index + 1].focus();
      }
    },
    onTimeInput(event) {
      const val = event.target.value;
      if (val.length === 5) {
        //this.simulateTab(event.target);
      }
    },
    clearNewSubstitute() {
      window.location.reload();
    },
    addNewSubstitute() {
      Inertia.post(this.route('dashboard.substitute.store', {school: 1}), this.form, {
        onSuccess: () => {
          window.location.reload();
          console.log('-----------------');
          console.log('sdfhdsg');
          console.log('-----------------');
        }
      });
    },
    newSubstitute() {
      const newSub = {
        id: null,
        time: "",
        grade: "",
        subject: "",
        absent: "",
        substitute: ""
      }
      this.substituteData.push(newSub);
      this.$nextTick(() => {
        const index = this.substituteData.length - 1;
        const ref = this.$refs['startTimeInputRef' + index];
        const el = Array.isArray(ref) ? ref[0] : ref;
        if (el && el.focus) {
          el.focus();
        } else {
          console.warn('Input not found or not focusable');
        }
      });
    }
  },
  watch: {},
  computed: {
    hasNewSubstitute() {
      return this.substituteData.some(sub => sub.id === null);
    },
  }
};
</script>
<style>
input[type="time"]::-webkit-calendar-picker-indicator {
  display: none;
  -webkit-appearance: none;
}
</style>

