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
          class="w-40 bg-white rounded rounded-lg shadow shadow-lg px-4 py-2 transition transform ease-in-out duration-200 hover:scale-105 hover:shadow-xl"
          :class="selectedGradeId == grade.id ?'bg-mountain-green text-white' : 'text-gray-400'"
        >
          {{ grade.name }}
        </button>
      </div>
      <div class="w-10/12 absolute bottom-10 left-4 flex gap-2">
        <div v-for="(subject,key) in vocabGroupedBySubject"
             class=" w-1/3 opacity-bg shadow shadow-xl h-[500px] rounded rounded-xl p-2 overflow-y-scroll relative">
          <div class="sticky top-0 right-0 w-full flex justify-end">
            <div class="bg-yellow-200 px-2 py-0.5 rounded rounded-md">
              {{ key }}
            </div>
          </div>
          <div>
            <div v-for="(vocab,index) in subject" :key="index">
              {{ vocab.vocab_en }}
            </div>
          </div>
        </div>

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
    return {
      vocabGroupedBySubject: null,
      selectedGradeId: null
    };
  },
  methods: {
    fetchVocabs(grade) {
      this.selectedGradeId = grade.id;
      const url = route('vocabs.fetch', grade.id);
      axios.get(url).then(res => {
        this.vocabGroupedBySubject = res.data
      })
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
<style>
.opacity-bg {
  background-color: rgba(255, 255, 255, 0.5);
}

.bg-mountain-green {
  background-color: #84CB85;
}
</style>

