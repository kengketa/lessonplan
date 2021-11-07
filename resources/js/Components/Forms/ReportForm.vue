<template>
  <Card :approve="computedApprove">
    <Form
      :disabled="report.creator_id != $page.props.authUser.id && $page.props.authUserRole === 'TEACHER'"
      :submit-event="submit"
      :need-confirm-text="needConfirmTextComputed"
    >
      <div>
        <div class="flex justify-between items-center">
          <h3 v-if="report.id == null" class="text-lg leading-6 text-gray-900">Create Lesson plan</h3>
          <h3 v-if="report.id != null" class="text-lg leading-6 text-gray-900">Update Lesson plan</h3>
          <p class="text-base lg:text-xl text-gray-700 px-1 py-1 lg:px-2 pg:py-2 bg-blue-100 rounded-md">
            {{ report.teacher_name }}
          </p>
        </div>
      </div>
      <div>
        <div class="grid lg:grid-cols-3 gap-2">
          <SearchSelectInput
            :options="report.all_subjects"
            v-model="form.subject"
            :is-show-line="false"
            :error="form.errors.subject"
            label="Subject"
          />
          <TextInput
            v-model="form.week_number"
            :is-show-line="false"
            :error="form.errors.week_number"
            type="number"
            label="Week number"
            placeholder="Week number"
          />
          <TextInput
            v-model="form.lesson_number"
            :is-show-line="false"
            :error="form.errors.lesson_number"
            type="number"
            label="Lesson number"
            placeholder="Lesson number"
          />
        </div>
        <div class="my-4">
          <p class="text-sm font-medium text-gray-700">Classes</p>
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
            <div v-for="(grade,gradeIndex) in form.report.for_grades" :key="gradeIndex"
                 class="border px-4 py-4 relative">
              <SearchSelectInput
                :options="report.all_grades"
                v-model="form.report.for_grades[gradeIndex].id"
                :error="computedGradeError(form.errors[this.getErrorKey('report.for_grades.',gradeIndex,'.id')])"
                :is-show-line="false"
                label="Class"
              />
              <DatePickerInput
                :is-show-line="false"
                v-model="form.report.for_grades[gradeIndex].date"
                :error="form.errors[this.getErrorKey('report.for_grades.',gradeIndex,'.date')]"
                label="Teaching Date"
              />
              <button v-if="type==='create'" @click="removeClass(gradeIndex)" type="button"
                      class="absolute top-2 right-2 text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </button>
            </div>
            <button v-if="type === 'create'" @click="addClass()" type="button"
                    class="border border-dashed h-48 px-4 py-4 flex justify-center items-center">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                   xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              <span>Add Class</span>
            </button>
          </div>
          <p v-show="form.errors['report.for_grades']" class="text-red-500 mt-1">
            There must be at least 1 class for this lesson plan
          </p>
        </div>
        <div class="my-4">
          <p class="text-sm font-medium text-gray-700">Lesson Plans</p>
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
            <div v-for="(plan,planIndex) in form.report.plans" :key="planIndex"
                 class="border px-4 py-4 relative"
            >
              <form v-on:submit.prevent>
                <SearchSelectInput
                  :options="report.types"
                  v-model="form.report.plans[planIndex].type"
                  :error="form.errors[this.getErrorKey('report.plans.',planIndex,'.type')]"
                  :is-show-line="false"
                  label="Type"
                />
                <TextInput
                  v-model="form.report.plans[planIndex].topic"
                  :is-show-line="false"
                  :error="form.errors[this.getErrorKey('report.plans.',planIndex,'.topic')]"
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
                  <p class="text-sm font-medium text-gray-700">Add vocabs</p>
                  <input type="text" @keyup.enter="addVocab(planIndex)"
                         v-model="form.report.plans[planIndex].typing_vocab"
                         placeholder="enter to add a vocabulary"
                         class="block w-full border-gray-300 focus:outline-none text-sm rounded-md focus:ring-primary-500">
                </div>
                <div class="mt-2">
                  <p>Lesson plan details</p>
                  <Editor
                    v-model="form.report.plans[planIndex].details"
                    api-key="8otka707t8tlcqakojbi97f3ptko4wrduwmf36zfyi3yh6wx"
                    :init="customEditor"
                  />
                </div>
                <button
                  v-if="type === 'create' ||
                  $page.props.authUserRole == 'SUPER_ADMIN' ||
                  $page.props.authUserRole == 'ADMIN'"
                  @click="removePlan(planIndex)" type="button"
                  class="absolute top-2 right-2 text-red-500">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                       stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </button>
              </form>
            </div>
            <button v-if="type === 'create'" @click="addPlan()" type="button"
                    class="border border-dashed h-80 px-4 py-4 flex justify-center items-center">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                   xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              <span>Add Plan</span>
            </button>
          </div>
          <p v-show="form.errors['report.plans']" class="text-red-500 mt-1">
            There must be at least 1 lesson plan
          </p>
        </div>
        <div class="my-4">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
            <TextareaInput
              :is-show-line="false"
              label="Teaching materials"
              v-model="form.teaching_materials"
              :error="form.errors.teaching_materials"
              rows="5"
            />
            <TextareaInput
              :is-show-line="false"
              label="Activities"
              v-model="form.activities"
              :error="form.errors.activities"
              rows="5"
            />
          </div>
        </div>
        <div class="my-4" v-if="type === 'edit'" id="outcome group">
          <div class="grid grid-cols-1">
            <TextareaInput
              :is-show-line="false"
              label="Outcome"
              v-model="form.outcome"
              :error="form.errors.outcome"
              rows="5"
            />
          </div>
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
            <TextareaInput
              :is-show-line="false"
              label="High distinction students"
              v-model="form.outstanding_students"
              :error="form.errors.outstanding_students"
              rows="5"
            />
            <TextareaInput
              :is-show-line="false"
              label="Need improvment students"
              v-model="form.need_improvement_students"
              :error="form.errors.need_improvement_students"
              rows="5"
            />
          </div>
        </div>
      </div>
      <div v-if="type === 'edit'" class="flex flex-col items-end text-sm text-gray-400">
        <p>Created: {{ report.created_at }} </p>
        <p>Last updated: {{ report.updated_at }}</p>
        <p v-if="report.approver != null">Approved by: {{ report.approver.name }}</p>
      </div>
      <div
        v-if="type === 'edit' && report.approver === null && ($page.props.authUserRole==='ADMIN' || $page.props.authUserRole==='SUPER_ADMIN')"
        class="flex justify-end">
        <button @click="approve()" type="button" class="button button-primary button-small">Approve</button>
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
import Editor from '@tinymce/tinymce-vue'

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
    Editor
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
        school_id: this.report.school.id,
        week_number: this.report.week_number ?? null,
        lesson_number: this.report.lesson_number ?? null,
        subject: this.report.subject ?? 1,
        teaching_materials: this.report.teaching_materials ?? null,
        activities: this.report.activities ?? null,
        outcome: this.report.outcome ?? null,
        outstanding_students: this.report.outstanding_students ?? null,
        need_improvement_students: this.report.need_improvement_students ?? null,
        report: this.report
      }),
      customEditor: {
        height: 500,
        menubar: false,
        plugins: [
          'advlist autolink lists link image charmap',
          'searchreplace visualblocks code fullscreen',
          'print preview anchor insertdatetime media',
          'paste code help wordcount table'
        ],
        toolbar:
          'undo redo | bold italic | \
          forecolor backcolor | \
          bullist numlist | \
          alignleft aligncenter alignright outdent indent | help'
      }
    };
  },
  mounted() {

  },
  computed: {
    needConfirmTextComputed() {
      if (this.computedApprove === 'approved') {
        return 'Are you sure to resubmit this approved lesson plan?'
      }
      return null;
    },
    computedApprove() {
      if (this.type === 'create') {
        return 'unapproved';
      }
      if (this.type === 'edit' && this.report.approver == null) {
        return 'waiting'
      }
      if (this.type === 'edit' && this.report.approver != null) {
        return 'approved'
      }
      return null;
    }
  },
  updated() {
    if (this.type === 'edit') {
      this.form.school_id = this.report.school.id;
      this.form.week_number = this.report.week_number ?? null;
      this.form.lesson_number = this.report.lesson_number ?? null;
      this.form.subject = this.report.subject ?? 1;
      this.form.teaching_materials = this.report.teaching_materials;
      this.form.activities = this.report.activities;
      this.form.outcome = this.report.outcome;
      this.form.outstanding_students = this.report.outstanding_students;
      this.form.need_improvement_students = this.report.need_improvement_students;
      this.form.report = this.report;
    }
  },
  methods: {
    computedGradeError(key) {
      if (key != undefined) {
        return 'Class is required.'
      }
      return null;
    },
    approve() {
      let url = route('dashboard.reports.approve', this.report.id);
      this.$inertia.post(url);
    },
    getErrorKey(front, index, prop) {
      let key = front + index + prop;
      return key;
    },
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

