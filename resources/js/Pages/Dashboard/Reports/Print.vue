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
      <div class="grid grid-cols-2 gap-0 border-t-2 border-blue-500 mt-2">
        <div v-for="(report,reportIndex) in page" :key="reportIndex"
             class="mt-1 px-2 report-space-height relative"
             :class="reportIndex%2 === 0 ? 'border-r-2 border-blue-500' : ''"
        >
          <div class="text-center">
            <p>Grade: {{ report.grade_name }}</p>
          </div>
          <div class="grid grid-cols-3 gap-2 text-sm">
            <p>Week: {{ report.week_number }}</p>
            <p>Lesson: {{ report.lesson_number }}</p>
            <p>Date: {{ report.date }}</p>
          </div>
          <div v-for="(plan,planIndex) in report.plans" :key="planIndex" class="grid grid-cols-2 gap-0 mt-1">
            <div class="border-t border-b border-l">
              <p class="text-sm py-0.5 px-2">{{ plan.type }}: {{ plan.topic }}</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                <p v-html="plan.details"></p>
              </div>
            </div>
            <div class="border">
              <p class="text-sm py-0.5 px-2">Vocabularies</p>
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
              <p class="text-sm py-0.5 px-2">Teaching Materials</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                {{ report.teaching_materials }}
              </div>
            </div>
            <div class="border">
              <p class="text-sm py-0.5 px-2">Activities</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                {{ report.activities }}
              </div>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-0 mt-1">
            <div class="col-span-2 border-t border-l border-r">
              <p class="text-sm py-0.5 px-2 text-center">Outcome</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                {{ report.outcome }}
              </div>
            </div>
            <div class="border-t border-b border-l">
              <p class="text-sm py-0.5 px-2">Hight distinction students</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                {{ report.outstanding_students }}
              </div>
            </div>
            <div class="border">
              <p class="text-sm py-0.5 px-2">Need improvment students</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                {{ report.need_improvement_students }}
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
  </div>
</template>

<script>


export default {
  name: 'ReportPrint',
  components: {},
  props: {
    fontSize: {
      type: String,
      default: 'text-2xs'
    },
    pages: {
      type: Object,
      required: true
    }
  },
  data() {
    return {};
  },
  methods: {},
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
}
</style>
