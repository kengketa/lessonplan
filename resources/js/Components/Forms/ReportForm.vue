<template>
  <Card>
    <Form
      form-class="space-y-8 divide-y divide-gray-200"
      :submit-event="submit"
    >
      <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
        <div class="space-y-6 sm:space-y-5">
          <div>
            <h3 v-if="report.id == null" class="text-lg leading-6 text-gray-900">
              Create Lesson plan
            </h3>
            <h3 v-else class="text-lg leading-6 text-gray-900">
              Update Lesson plan
            </h3>
          </div>
          <section>
            <SearchSelectInput
              :options="report.all_subjects"
              v-model="form.subject"
              :is-show-line="false"
              label="Subject"
            />
            <TextInput
              v-model="form.week_number"
              :is-show-line="false"
              :errors="form.errors.week_number"
              type="number"
              label="Week number"
              placeholder="Week number"
            />
            <div class="mt-2">
              <p class="text-sm font-medium text-gray-700">Classes</p>
              <div class="grid grid-cols-4 gap-2">
                <div v-for="(grade,gradeIndex) in form.report.for_grades" :key="gradeIndex"
                     class="border h-48 px-4 py-4 flex flex-col justify-center relative">
                  <SearchSelectInput
                    :options="report.all_grades"
                    v-model="form.report.for_grades[gradeIndex].id"
                    :is-show-line="false"
                    label="Class"
                  />
                  <DatePickerInput
                    :is-show-line="false"
                    v-model="form.report.for_grades[gradeIndex].date"
                    label="Teaching Date"
                  />
                  <button @click="removeClass(gradeIndex)" type="button" class="absolute top-2 right-2 text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </button>
                </div>
                <button @click="addClass()" type="button"
                        class="border border-dashed h-48 px-4 py-4 flex justify-center items-center">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                       xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  <span>Add Class</span>
                </button>
              </div>
            </div>
            <div class="mt-2">
              <p class="text-sm font-medium text-gray-700">Lesson Plans</p>
              <div class="grid grid-cols-2 gap-2">
                <div v-for="(plan,planIndex) in form.report.plans" :key="planIndex"
                     class="border px-4 py-4 relative">
                  <SearchSelectInput
                    :options="report.types"
                    v-model="form.report.plans[planIndex].type"
                    :is-show-line="false"
                    label="Type"
                  />
                  <TextInput
                    v-model="form.report.plans[planIndex].topic"
                    :is-show-line="false"
                    type="text"
                    label="Topic"
                    placeholder="Topic"
                  />
                  <div class="flex flex-wrap mt-2">
                    <div class="bg-yellow-100 px-2 py-1 rounded-md mx-2 my-2 relative"
                         v-for="(vocab,vocabIndex) in form.report.plans[planIndex].vocabs"
                         :key="vocabIndex">
                      <span>{{ vocab }}</span>
                      <button @click="removeVocab(vocabIndex,planIndex)" type="button"
                              class="absolute -top-2 -right-2 text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </button>
                    </div>
                  </div>
                  <div class="mt-2">
                    <form v-on:submit.prevent>
                      <p class="text-sm font-medium text-gray-700">Add vocabs</p>
                      <input type="text" @keyup.enter="addVocab(planIndex)"
                             v-model="form.report.plans[planIndex].typing_vocab"
                             placeholder="enter to add a vocabulary"
                             class="block w-full border-gray-300 focus:outline-none text-sm rounded-md focus:ring-primary-500">
                    </form>
                  </div>
                  <TextareaInput
                    label="Plan Details"
                    :is-show-line="false"
                    rows="6"
                  />
                  <button @click="removePlan(planIndex)" type="button" class="absolute top-2 right-2 text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </button>
                </div>
                <button @click="addPlan()" type="button"
                        class="border border-dashed h-80 px-4 py-4 flex justify-center items-center">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                       xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  <span>Add Plan</span>
                </button>
              </div>
            </div>
          </section>
        </div>
      </div>
    </Form>
  </Card>
</template>


<script>
import {useForm} from '@inertiajs/inertia-vue3';
import Card from '@/Components/Card';
import TextInput from '@/Components/TextInput';
import TextareaInput from '@/Components/TextareaInput';
import {PlusSmIcon, TrashIcon} from '@heroicons/vue/solid';
import Form from '@/Components/Form';
import SearchSelectInput from "../SearchSelectInput";
import DatePickerInput from "../DatePickerInput";

export default {
  name: 'ReportForm',
  components: {
    DatePickerInput,
    SearchSelectInput,
    Card,
    TextInput,
    TextareaInput,
    PlusSmIcon,
    TrashIcon,
    Form,
  },
  props: {
    type: {
      default: 'create',
      type: String,
    },
    report: Object,
    errors: Object,
  },
  data() {
    return {
      form: useForm({
        report: this.report
      }),
    };
  },
  mounted() {

  },
  methods: {
    removeVocab(vocabIndex, planIndex) {
      this.report.plans[planIndex].vocabs.splice(vocabIndex, 1);
    },
    addVocab(planIndex) {
      let newVocab = this.form.report.plans[planIndex].typing_vocab;
      this.form.report.plans[planIndex].typing_vocab = null;
      this.form.report.plans[planIndex].vocabs.push(newVocab)
    },
    addPlan() {
      let newPlan = {
        id: 0,
        type: 1,
        topic: null,
        vocabs: [],
        typing_vocab: null,
        details: null
      };
      this.form.report.plans.push(newPlan);
    },
    removePlan(planIndex) {
      this.form.report.plans.splice(planIndex, 1);
    },
    removeClass(gradeIndex) {
      this.form.report.for_grades.splice(gradeIndex, 1);
    },
    addClass() {
      let newClass = {
        id: 0,
        date: null
      };
      this.form.report.for_grades.push(newClass);
    },
    submit() {
      if (this.type === 'create') {
        this.form.post(route('dashboard.reports.store'));
      } else {
        this.form.put(route('dashboard.reports.update', this.report.id));
      }
    },
    makeid(length) {
      let result = '';
      const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
      const charactersLength = characters.length;
      for (let i = 0; i < length; i++) {
        result += characters.charAt(
          Math.floor(Math.random() * charactersLength)
        );
      }
      return result;
    },
  },
};
</script>

<style scoped>
</style>

