<template>
  <div class="max-w-5xl mx-auto px-4 py-6">
    <div class="w-full flex justify-center items-center">
      <div class="w-32 h-32">
        <img alt="ABP Logo" src="/images/abpy/abpy-logo.png"/>
      </div>
    </div>
    <div>
      <h3 class="text-4xl font-semibold text-center text-gray-800">
        Substitutions {{ currentDate }}
      </h3>
    </div>
    <div class="overflow-x-auto bg-white shadow-sm rounded-lg mt-4">
      <table class="min-w-full text-lg">
        <thead class="bg-gray-50 text-gray-600 uppercase tracking-wider">
        <tr class="text-center">
          <th class="px-4 py-3">Time</th>
          <th class="px-4 py-3">Grade</th>
          <th class="px-4 py-3">Subject</th>
          <th class="px-4 py-3">Absent</th>
          <th class="px-4 py-3">Substitute</th>
        </tr>
        </thead>
        <tbody v-if="substituteData" class="divide-y divide-gray-100 text-lg">
        <tr
          v-for="(sub, index) in substituteData"
          :key="index"
          class="text-center"
        >
          <td class="px-4 py-2">
            <div class="flex gap-0.5 justify-center items-center">
              <p class="">{{ sub.start_time }}</p>
              <p>-</p>
              <p class="">{{ sub.end_time }}</p>
            </div>
          </td>
          <td class="px-4 py-2">
            {{ sub.grade }}
          </td>
          <td class="px-4 py-2">
            {{ sub.subject }}
          </td>
          <td class="px-4 py-2">
            <p class="capitalize">{{ sub.teacher }}</p>
          </td>
          <td class="px-4 py-2">
            <div class="flex flex-wrap justify-center gap-2 capitalize">
              {{ sub.volunteer }}
            </div>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <div class="w-full text-center mt-4 text-pink-400 font-semibold">
      <p class="text-xl">Thank you for stepping in — you help us keep learning and growing.</p>
    </div>
  </div>
</template>
<script>
import {Inertia} from "@inertiajs/inertia";
import {nextTick} from "vue";


export default {
  name: "VolunteerPrint",
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
      date: new URLSearchParams(window.location.search).get('date') ?? null,
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
    this.$nextTick(() => {
      const images = Array.from(document.images);
      const allImagesLoaded = images.map(img => {
        return img.complete ? Promise.resolve() : new Promise(resolve => {
          img.onload = img.onerror = resolve;
        });
      });
      Promise.all(allImagesLoaded).then(() => {
        window.addEventListener('afterprint', () => {
          Inertia.visit(this.route('dashboard.substitute.index', {school: this.school.id}));
        });
        window.print();
      });
    });
  },
  methods: {},
  watch: {},
  computed: {
    currentDate() {
      let now = new Date();
      if (this.date) {
        now = new Date(this.date);
      }
      const date = now.toLocaleDateString('en-GB', {
        weekday: 'short',
        day: '2-digit',
        month: 'long',
        year: 'numeric'
      });
      return date;
    },
    hasNewSubstitute() {
      return this.substituteData.some(sub => sub.id === null);
    },
  }
};
</script>
<style>
@media print {
  body {
    background: white !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }

  html, body {
    width: 210mm;
    height: 297mm;
    margin: 0;
    padding: 0;
    font-size: 12px;
  }

  .print\:hidden {
    display: none !important;
  }

  .min-h-screen {
    min-height: auto !important;
  }

  .bg-gray-100, .bg-white, .bg-yellow-100, .hover\:bg-yellow-200, .hover\:bg-gray-50, .shadow-sm {
    background: transparent !important;
    box-shadow: none !important;
  }

  .text-center, .text-gray-700, .text-gray-800 {
    color: black !important;
  }

  .rounded-lg {
    border-radius: 0 !important;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  th, td {
    border: 1px solid black !important;
    padding: 4px !important;
  }

  th {
    background: #f0f0f0 !important;
    font-weight: bold;
  }

  img {
    max-width: 100px;
  }
}
</style>
