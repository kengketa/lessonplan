<template>
  <div>
    <div>
      <div class="lg:hidden grid grid-cols-2 gap-2">
        <div class="col-span-2 text-center py-2 font-semibold text-lg text-gray-700">{{ pages[1][0].school_name }}</div>
        <div class="text-center">{{ pages[1][0].teacher_name }}</div>
        <div class="text-center">{{ pages[1][0].subject }}</div>
      </div>
      <div class="hidden lg:flex justify-center px-2 py-2 font-semibold text-gray-700">
        <div class="w-2/12">
          <p>Lesson Plan</p>
        </div>
        <div class="w-6/12">
          <p>School: {{ pages[1][0].school_name }}</p>
        </div>
        <div class="w-2/12">
          <p>Teacher: {{ pages[1][0].teacher_name }}</p>
        </div>
        <div class="w-2/12">
          <p>Subject: {{ pages[1][0].subject }}</p>
        </div>
      </div>
    </div>
    <div v-for="(page,pageIndex) in pages" :key="pageIndex" class="page-break">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 lg:border-t border-gray-200 mt-2">
        <div v-for="(report,reportIndex) in page" :key="reportIndex"
             class="mt-1 mb-10 px-2 relative"
             :class="reportIndex%2 === 0 ? 'border-r border-gray-200' : ''"
        >
          <div class="text-center border-t lg:border-t-0">
            <p class="font-semibold text-gray-700">Grade: {{ report.grade_name }}</p>
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
              <div class="border-t px-2 py-1" :class="fontSize">
                {{ report.need_improvement_students }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="fixed bottom-0 h-20 bg-blue-900 w-full text-center text-gray-100 flex justify-center items-center">
      <div>
        <div>
          <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
               width="60px" height="60px" viewBox="0 0 200.000000 200.000000"
               preserveAspectRatio="xMidYMid meet">
            <g transform="translate(0.000000,200.000000) scale(0.012210,-0.012210)"
               fill="#F3F4F6" stroke="none">
              <path d="M2870 8345 l0 -4735 5285 0 5285 0 0 4735 0 4735 -5285 0 -5285 0 0
              -4735z m4740 1989 l0 -1484 -1700 0 -1700 0 0 1485 0 1485 1688 2 c928 2 1693
              1 1700 -1 9 -2 12 -308 12 -1487z m4600 1 l0 -1485 -1635 0 -1635 0 0 1483 c0
              816 2 1486 4 1488 2 2 738 3 1635 1 l1631 -2 0 -1485z m-4600 -4295 l0 -1360
              -1700 0 -1700 0 0 1360 0 1360 1700 0 1700 0 0 -1360z m4600 0 l0 -1360 -1635
              0 -1635 0 0 1360 0 1360 1635 0 1635 0 0 -1360z" />
            </g>
          </svg>
        </div>
      </div>
      <div class="text-left">
        <p>Brighton Education co.,ltd.,</p>
        <p class="text-sm">©{{ new Date().getFullYear() }} All rights reserved.</p>
      </div>
    </div>
  </div>
</template>

<script>


export default {
  name: 'GlobalReports',
  components: {},
  props: {
    pages: {
      type: Object,
      required: true
    },
  },
  data() {
    return {
      fontSize: 'text-xs'
    };
  },
  methods: {

    setSize(size) {
      this.fontSize = size;
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
