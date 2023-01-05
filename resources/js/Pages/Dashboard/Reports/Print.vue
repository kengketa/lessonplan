<template>
  <div>
    <div v-for="(page,pageIndex) in pages" :key="pageIndex" class="page-break">
      <div>
        <div class="flex justify-center">
          <div class="w-2/12">
            <p>Lesson Plan</p>
          </div>
          <div class="w-6/12">
            <p>School: {{ page[0].school_name }}</p>
          </div>
          <div class="w-2/12">
            <p>Teacher: {{ page[0].teacher_name }}</p>
          </div>
          <div class="w-2/12">
            <p>Subject: {{ page[0].subject }}</p>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-0 border-t border-gray-200 mt-2">
        <div v-for="(report,reportIndex) in page" :key="reportIndex"
             class="mt-1 px-2 report-space-height relative"
             :class="reportIndex%2 === 0 ? 'border-r border-gray-200' : ''"
        >
          <div class="text-center">
            <p>Grade: {{ report.grade_name }}</p>
          </div>
          <div class="grid grid-cols-3 gap-2 text-sm">
            <p>Week: {{ report.week_number }}</p>
            <p>Lesson: {{ report.lesson_number }}</p>
            <p>Date: {{ report.date }}</p>
          </div>
          <div v-for="(plan,planIndex) in report.plans" :key="planIndex" class="grid grid-cols-3 gap-0 mt-1">
            <div class="col-span-2 border-t border-b border-l">
              <p class="text-sm py-0.5 px-2 bg-indigo-100">{{ plan.type }}: {{ plan.topic }}</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                <p v-html="plan.details"></p>
              </div>
            </div>
            <div class="border">
              <p class="text-sm py-0.5 px-2 bg-indigo-100">Vocabularies</p>
              <div class="border-t flex flex-wrap px-2 py-1" :class="fontSize">
                <p v-for="(vocab,vocabIndex) in plan.vocabs" :key="vocabIndex"
                   class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">
                  {{ vocab }}
                </p>
              </div>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-0 mt-1">
            <div class="border-t border-b border-l">
              <p class="text-sm py-0.5 px-2 bg-yellow-100">Teaching Materials</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                {{ report.teaching_materials }}
              </div>
            </div>
            <div class="border">
              <p class="text-sm py-0.5 px-2 bg-yellow-100">Activities</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                {{ report.activities }}
              </div>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-0 mt-1">
            <div class="col-span-2 border-t border-l border-r">
              <p class="text-sm py-0.5 px-2 text-center bg-indigo-100">Outcome</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                {{ report.outcome }}
              </div>
            </div>
            <div class="border-t border-b border-l">
              <p class="text-sm py-0.5 px-2 bg-green-100">Hight distinction students</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                {{ report.outstanding_students }}
              </div>
            </div>
            <div class="border">
              <p class="text-sm py-0.5 px-2 bg-red-100">Need improvment students</p>
              <div class="border-t px-2 py-1" :class="fontSize" v-html="report.need_improvement_students">
              </div>
            </div>
          </div>
          <div class="absolute right-8 bottom-0">
            <div class="text-center text-sm">
              <p>Signature___________________</p>
              <p>( {{ report.teacher_name }} )</p>
            </div>
          </div>
        </div>

      </div>
    </div>
    <div class="fixed top-4 right-4 z-100">
      <div class="flex flex-justify-end">
        <button type="button"
                @click="generate()"
                class="mr-2 no-print button button-small button-primary z-100 shadow-md transition transform ease-in-out hover:scale-105 hover:shadow-lg">
          Generate
        </button>

        <button type="button"
                @click="print()"
                class="no-print button button-small button-primary z-100 shadow-md transition transform ease-in-out hover:scale-105 hover:shadow-lg">
          Print
        </button>
      </div>
      <div class="flex justify-end no-print mt-1">
        <button type="button" class="bg-blue-600 p-1 rounded-full text-white mr-1" @click="decreaseFont()">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" />
          </svg>
        </button>
        <button type="button" class="bg-blue-600 p-1 rounded-full text-white" @click="increaseFont()">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
          </svg>
        </button>
      </div>
    </div>
  </div>
  <GeneratedLinkModal v-model="showGeneratedLink" :link="generatedLink" />
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
  methods: {
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
  mounted() {
  }
};
</script>
<style scoped>
.report-space-height {
  height: 180mm;
}

@media print {
  @page {
    margin: 0.5cm;
    size: A4 landscape;
  }

  .page-break {
    page-break-after: always;
  }

  .no-print, .no-print * {
    display: none !important;
  }
}
</style>
