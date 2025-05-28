<template>
  <div>
    <div class="text-sm font-sans text-gray-900">
      <div v-for="(report,index) in flattedReports" :key="index"
           class="w-full max-w-[210mm] mx-auto p-2 shadow-lg page-break page relative">
        <table class="min-w-full">
          <tbody>
          <tr class="">
            <td class="font-bold text-lg">Teacher</td>
            <td class="font-bold text-lg">{{ report.teacher_name }}</td>
          </tr>
          <tr class="">
            <td class="font-bold">Week</td>
            <td class="font-bold">{{ report.week_number }}</td>
          </tr>
          <tr class="">
            <td class="font-bold ">Grade</td>
            <td class="">{{ report.grade_name }}</td>
          </tr>
          <tr class="">
            <td class="font-bold ">Subject</td>
            <td class="">{{ report.subject }}</td>
          </tr>
          <tr class="">
            <td class="font-bold  align-top">Topic</td>
            <td class="">{{ report.plans[0].topic }}</td>
          </tr>
          <tr class="">
            <td class="font-bold  align-top">Vocabularies</td>
            <td class="">
              {{ commaSeparated(report.plans[0].vocabs) }}
            </td>
          </tr>
          <tr>
            <td class="font-bold align-top">Materials</td>
            <td class="leading-4">{{ report.teaching_materials }}</td>
          </tr>
          </tbody>
        </table>
        <hr>
        <div class="mt-2 text-sm text-gray-900">
          <p v-html="report.plans[0].details"></p>
        </div>
        <div class="mt-4 w-full footer">
          <div :class="schoolViceDirector && schoolAdmin ?'grid-cols-3':'grid-cols-1'" class="grid gap-4">
            <div class="flex justify-center flex-col items-center">
              <div class="border-b border-gray-400 w-full h-6"></div>
              <div class="mt-1">({{ report.teacher_name }})</div>
              <p>Teacher</p>
            </div>
            <div v-if="schoolAdmin" class="flex justify-center flex-col items-center">
              <div class="border-b border-gray-400 w-full h-6"></div>
              <div class="mt-1">({{ schoolAdmin.name }})</div>
              <p>Head of MEP</p>
            </div>
            <div v-if="schoolViceDirector" class="flex justify-center flex-col items-center">
              <div class="border-b border-gray-400 w-full h-6"></div>
              <div class="mt-1">({{ schoolViceDirector.name }})</div>
              <p>Deputy director of academic affairs</p>
            </div>
          </div>
        </div>
        <div class="absolute top-4 right-10 flex flex-col items-center justify-center">
          <div class="w-20 h-20">
            <img alt="abpy logo" class="object-cover" src="/images/abpy/abpy-logo.png"/>
          </div>
          <!--          <p class="font-bold text-xl">Anuban Phayao Lesson Plan</p>-->
        </div>
      </div>
    </div>
    <div class="fixed top-4 right-4 z-100">
      <div class="flex flex-justify-end">
        <button
          class="no-print button button-small button-primary z-100 shadow-md transition transform ease-in-out hover:scale-105 hover:shadow-lg"
          type="button"
          @click="print()">
          Print
        </button>
      </div>
    </div>
  </div>
  <GeneratedLinkModal v-model="showGeneratedLink" :link="generatedLink"/>
</template>

<script>
import GeneratedLinkModal from "@/Components/Forms/GeneratedLinkModal";

export default {
  name: 'ReportPrint',
  components: {
    GeneratedLinkModal
  },
  props: {
    pages: {
      type: Object,
      required: true
    },
    reportIds: {
      type: Array,
      required: true
    },
    schoolAdmin: {
      type: Object,
      default: null
    },
    schoolViceDirector: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      fontSize: 'text-xs',
      showGeneratedLink: false,
      generatedLink: "",
      fontReduce: 1
    };
  },
  mounted() {
    console.log('-----------------');
    console.log(this.schoolAdmin);
    console.log('-----------------');
  },
  methods: {
    commaSeparated(vocabs) {
      return vocabs.join(', ');
    },
    print() {
      window.print();
    },
    generate() {
      let payload = {
        link: window.location.href
      };
      axios.post(route('dashboard.reports.generate_link'), payload).then((response) => {
        this.generatedLink = response.data;
        this.showGeneratedLink = true;
      })
    },
    increaseFont() {
      if (this.fontReduce > 1) {
        this.fontReduce = this.fontReduce - 1;
      }
    },
    decreaseFont() {
      if (this.fontReduce < 4) {
        this.fontReduce = this.fontReduce + 1;
      }
    },
  },
  watch: {
    fontReduce() {
      if (this.fontReduce == 1) {
        this.fontSize = 'text-xs'
      } else {
        this.fontSize = 'text-' + this.fontReduce + 'xs';
      }
    }
  },
  computed: {
    flattedReports() {
      return Object.values(this.pages).flat();
    }
  }

};
</script>
<style scoped>
@media print {
  @page {
    margin: 0.5cm 0.5cm 0.5cm 2cm;
    size: A4 portrait;
  }

  .page {
    position: relative;
    height: 320mm;
    width: 210mm;
    page-break-after: always;
    overflow: hidden;
  }

  * {
    box-shadow: none !important;
  }

  .page-break {
    page-break-after: always;
  }

  .no-print, .no-print * {
    display: none !important;
  }

  .footer {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
  }
}
</style>
