<template>
  <div>
    <div v-for="(page,pageIndex) in pages" :key="pageIndex">
      <div>
        <div class="flex justify-center">
          <div class="w-1/4">
            <p>Lesson Plan</p>
          </div>
          <div class="w-1/4">
            <p>school___________________</p>
          </div>
          <div class="w-1/4">
            <p>Teacher___________________</p>
          </div>
          <div class="w-1/4">
            <p>Subject___________________</p>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-0 border-t-2 border-blue-500 mt-2">
        <div v-for="(report,reportIndex) in page" :key="reportIndex"
             class="border-r-2 border-blue-500 mt-1 px-2 report-space-height relative">
          <div class="text-center">
            <p>Grade xxxx</p>
          </div>
          <div class="grid grid-cols-3 gap-2 text-sm">
            <p>Week</p>
            <p>Lesson</p>
            <p>Date</p>
          </div>
          <div class="grid grid-cols-2 gap-0 mt-1">
            <div class="border-t border-b border-l">
              <p class="text-sm py-0.5 px-2">topic-1</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi error quo
                tempora
                voluptate. Architecto aut dolorem dolores error inventore necessitatibus officiis porro quaerat qui
                quo quos, repellendus, unde vitae!
              </div>
            </div>
            <div class="border">
              <p class="text-sm py-0.5 px-2">Vocabularies</p>
              <div class="border-t flex flex-wrap px-2 py-1" :class="fontSize">
                <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
              </div>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-0 mt-1">
            <div class="border-t border-b border-l">
              <p class="text-sm py-0.5 px-2">topic-2</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi error quo
                tempora
                voluptate. Architecto aut dolorem dolores error inventore necessitatibus officiis porro quaerat qui
                quo quos, repellendus, unde vitae!
              </div>
            </div>
            <div class="border">
              <p class="text-sm py-0.5 px-2">Vocabularies</p>
              <div class="border-t flex flex-wrap px-2 py-1" :class="fontSize">
                <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
              </div>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-0 mt-1">
            <div class="border-t border-b border-l">
              <p class="text-sm py-0.5 px-2">Teaching Materials</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi error quo
                tempora
                voluptate. Architecto aut dolorem dolores error inventore necessitatibus officiis porro quaerat qui
                quo quos, repellendus, unde vitae!
              </div>
            </div>
            <div class="border">
              <p class="text-sm py-0.5 px-2">Activities</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi error quo
                tempora
                voluptate. Architecto aut dolorem dolores error inventore necessitatibus officiis porro quaerat qui
                quo quos, repellendus, unde vitae!
              </div>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-0 mt-1">
            <div class="col-span-2 border-t border-l border-r">
              <p class="text-sm py-0.5 px-2 text-center">Outcome</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi error quo
                tempora
                voluptate. Architecto aut dolorem dolores error inventore necessitatibus officiis porro quaerat qui
                quo quos, repellendus, unde vitae!
              </div>
            </div>
            <div class="border-t border-b border-l">
              <p class="text-sm py-0.5 px-2">Hight distinction students</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi error quo
                tempora
                voluptate. Architecto aut dolorem dolores error inventore necessitatibus officiis porro quaerat qui
                quo quos, repellendus, unde vitae!
              </div>
            </div>
            <div class="border">
              <p class="text-sm py-0.5 px-2">Need improvment students</p>
              <div class="border-t px-2 py-1" :class="fontSize">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi error quo
                tempora
                voluptate. Architecto aut dolorem dolores error inventore necessitatibus officiis porro quaerat qui
                quo quos, repellendus, unde vitae!
              </div>
            </div>
          </div>
          <div class="absolute right-8 bottom-0">
            <div class="text-center text-sm">
              <p>Signature___________________</p>
              <p>(Teacher name)</p>
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
      type: Array,
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
  height: 185mm;
}

@media print {
  @page {
    margin: 0.5cm;
    size: A4 landscape;
  }
}
</style>
