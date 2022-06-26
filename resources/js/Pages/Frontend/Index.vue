<template>
  <div class="relative font-mali">
    <img src="/images/t5/tessaban-5-bg.png" class="absolute top-0 -z-50 object-cover w-full h-full">
    <div class="absolute top-0 content relative">
      <div class="w-full flex justify-center mt-4">
        <div class="w-8/12">
          <img src="/images/t5/title.png" class="w-full">
        </div>
      </div>
      <div class="w-full flex flex-wrap gap-2 px-2 justify-center">
        <button
          @click="fetchVocabs(grade)"
          v-for="(grade,index) in grades"
          class="bg-white rounded rounded-lg shadow shadow-lg px-4 py-2 transition transform ease-in-out duration-200 hover:scale-105 hover:shadow-xl"
          :class="selectedGrade &&  selectedGrade.id == grade.id ?'bg-mountain-green text-white' : 'text-gray-400'"
        >
          {{ grade.name }}
        </button>
      </div>
      <div class="w-full h-screen mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 px-2 py-2">
        <div v-for="(subject,key) in vocabGroupedBySubject"
             class="opacity-bg shadow shadow-xl h-full rounded rounded-xl p-2 overflow-y-scroll relative">
          <div class="sticky top-0 right-0 w-full flex justify-end">
            <div class="bg-yellow-200 px-2 py-0.5 rounded rounded-md">
              {{ key }}
            </div>
          </div>
          <div class="px-4">
            <div class="cursor-pointer flex justify-between text-xl text-gray-500"
                 v-for="(vocab,index) in subject"
                 :key="index"
                 @click="insertThaiVocab(vocab,key,index)"
            >
              <p>{{ vocab.vocab_en }}</p>
              <p>
                {{ vocab.vocab_th ?? 'ยังไม่มีคำแปล' }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";
import Swal from 'sweetalert2/dist/sweetalert2.js';

export default {
  name: "Index",
  components: {Swal},
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
      selectedGrade: null,
      hoverVocab: null,
    };
  },
  methods: {
    insertThaiVocab(vocab) {
      Swal.fire({
        title: 'Help us to translate "' + vocab.vocab_en + '" ?',
        input: 'text',
        showCancelButton: true,
        confirmButtonText: 'Submit',
        confirmButtonColor: '#8CD4F5',
        preConfirm: (thaiWord) => {
          return axios.put(route('vocabs.update', vocab.id), {
            thaiWord: thaiWord
          })
            .then(response => {
              return response.data;
            })
            .catch(error => {
              Swal.showValidationMessage(
                `Request failed: ${error}`
              )
            })
        },
      }).then((result) => {
        if (result.isConfirmed) {
          // Swal.fire({
          //   position: 'center',
          //   icon: 'success',
          //   title: 'ขอบคุณสำหรับคำแปล ' + result.value.vocab_en + ':' + result.value.vocab_th,
          //   showConfirmButton: false,
          //   timer: 1500
          // })
          this.fetchVocabs(this.selectedGrade)
        }
      })
    },
    showVoab(vocab) {
      this.hoverVocab = vocab;
    },
    clearVocab() {
      this.hoverVocab = null;
    },
    fetchVocabs(grade) {
      this.selectedGrade = grade;
      const url = route('vocabs.fetch', grade.id);
      axios.get(url).then(res => {
        this.vocabGroupedBySubject = res.data
      })
    }
  },
  watch: {},
  computed: {
    // computedGrid() {
    //   if (this.vocabGroupedBySubject == null) {
    //     return "";
    //   }
    //   return 'grid-cols-' + this.vocabGroupedBySubject.length;
    // },
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
  background-color: rgba(255, 255, 255, 0.8);
}

body {
  background-color: #E0F8FF;
}

.bg-mountain-green {
  background-color: #84CB85;
}
</style>

