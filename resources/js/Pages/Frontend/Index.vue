<template>
  <div class="h-screen overflow-hidden relative">
    <img src="/images/t5/tessaban-5-bg.png" class="absolute top-0 -z-50 object-cover w-full h-full">
    <div class="absolute top-0 content h-screen relative">
      <div class="w-full flex justify-center mt-4">
        <div class="w-8/12">
          <img src="/images/t5/title.png" class="w-full">
        </div>
      </div>
      <div class="absolute bottom-10 right-10 flex flex-col gap-2">
        <button
          @click="fetchVocabs(grade)"
          v-for="(grade,index) in grades"
          class="w-40 bg-white rounded rounded-lg shadow shadow-lg px-4 py-2 transition transform ease-in-out duration-200 hover:scale-105 hover:shadow-xl">
          {{ grade.name }}
        </button>
      </div>
      <div>
      </div>
    </div>
  </div>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";

export default {
  name: "Index",
  components: {},
  props: {
    school: {
      type: Object,
      required: true
    },
    grades: {
      type: Object,
      required: true
    }
  },
  data() {
    return {};
  },
  methods: {
    fetchVocabs(grade) {
      // Inertia.visit(route('vocabs.show', grade.id));
    }
  },
  watch: {},
  computed: {
    computedBackground() {
      const url = route('baseurl') + '/images/t5/tessaban-5-bg.png';
      return 'background-image: url("' + url + '");background-size: 100% 100%;'
    }
  },
  mounted() {
  }
}
</script>

