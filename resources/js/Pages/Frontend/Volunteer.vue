<template>
  <div class="max-w-5xl mx-auto px-4 py-6 bg-gray-100 min-h-screen">
    <div class="w-full flex justify-center items-center">
      <div class="w-32 h-32">
        <img alt="ABP Logo" src="/images/abpy/abpy-logo.png"/>
      </div>
    </div>
    <h3 class="text-xl font-semibold text-center text-gray-800 mb-6">
      Today’s Substitutions ({{ currentDateTime }})
    </h3>
    <div class="overflow-x-auto bg-white shadow-sm rounded-lg">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
        <tr class="text-center">
          <th class="px-4 py-3">Time</th>
          <th class="px-4 py-3">G</th>
          <th class="px-4 py-3">Subject</th>
          <th class="px-4 py-3">Absent</th>
          <th class="px-4 py-3">Substitute</th>
        </tr>
        </thead>
        <tbody v-if="substituteData" class="divide-y divide-gray-100">
        <tr
          v-for="(sub, index) in substituteData"
          :key="index"
          class="text-center text-gray-700 hover:bg-gray-50 transition"
        >
          <td class="px-4 py-2">
            <p class="whitespace-nowrap text-sm"> {{ sub.start_time }} - {{ sub.end_time }}</p>
          </td>
          <td class="px-4 py-2">
            {{ sub.grade }}
          </td>
          <td class="px-4 py-2">
            {{ sub.subject }}
          </td>
          <td class="px-4 py-2">
            {{ sub.teacher }}
          </td>
          <td class="px-4 py-2">
            <div v-if="sub.id !== null" class="flex flex-wrap justify-center gap-2">
              <button
                v-if="sub.volunteer"
                class="px-3 py-1 bg-green-500 text-white text-xs rounded-lg hover:bg-green-600 transition"
                @click="assignNewSubstitute(sub)"
              >
                {{ sub.volunteer }}
              </button>
              <button
                v-else
                class="px-3 py-1 bg-yellow-400 text-white text-xs rounded-lg hover:bg-yellow-500 transition"
                @click="assignNewSubstitute(sub)"
              >
                Need
              </button>
            </div>
            <div v-else class="flex flex-col items-center gap-1">
              <input
                v-model="form.substitute"
                class="w-28 px-2 py-1 border border-gray-300 rounded-md text-sm text-center focus:ring-2 focus:ring-blue-400"
                placeholder="Volunteer"
                type="text"
              />
              <p class="text-red-500 text-xs mt-1">
                {{ $page.props.errors.substitute }}
              </p>
            </div>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <div class="w-full text-center mt-4 text-pink-400 font-semibold">
      <p> Thank you for stepping in — you help us keep learning and growing.</p>
    </div>
  </div>

</template>
<script>
import {Inertia} from "@inertiajs/inertia";


export default {
  name: "SubstituteIndex",

  components: {},
  props: {
    substitutes: {
      type: Array,
      required: true
    },
    school: {
      type: Object,
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
    this.timer = setInterval(this.updateTime, 1000);
  },
  beforeUnmount() {
    clearInterval(this.timer)
  },
  methods: {
    async assignNewSubstitute(sub) {
      const {value: userInput} = await this.$swal.fire({
        title: 'Let me take this class.',
        input: 'text',
        inputValue: sub.volunteer,
        inputPlaceholder: 'Fill up your name to take this hour.',
        showCancelButton: true,
        confirmButtonText: 'Count Me In',
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
      Inertia.patch(this.route("substitute.update", sub.id), {
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
      const date = now.toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'});
      const time = now.toLocaleTimeString(undefined, {hour12: false})
      this.currentDateTime = `${date} ${time}`
    },
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
