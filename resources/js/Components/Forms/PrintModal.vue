<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <TransitionRoot as="template" :show="modelValue">
    <Dialog as="div" class="fixed z-30 inset-0 overflow-y-auto" @close="cancel()">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                         enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100"
                         leave-to="opacity-0">
          <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
        </TransitionChild>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <TransitionChild as="template" enter="ease-out duration-300"
                         enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                         leave-from="opacity-100 translate-y-0 sm:scale-100"
                         leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
          <div
            class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
              <div>
                <DialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-900 text-center">
                  Print Selection
                </DialogTitle>
                <div class="mt-2">
                  <div>
                    <fieldset class="space-y-2">
                      <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                          <input id="select-all" aria-describedby="comments-description"
                                 v-model="selectAll"
                                 name="selectAll"
                                 type="checkbox"
                                 class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                          <label for="select-all" class="font-medium text-gray-700">
                            Select All
                          </label>
                        </div>
                      </div>
                      <div v-for="(report,reportIndex) in printList" :key="report.id" class="relative flex items-start">
                        <div class="flex items-center h-5">
                          <input :id="'report-'+report.id" aria-describedby="comments-description"
                                 v-model="form.print_list[reportIndex].print"
                                 :name="'report-'+report.id"
                                 type="checkbox"
                                 class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                          <label :for="'report-'+report.id" class="font-medium text-gray-700">
                            {{ report.teacher_name }} {{ report.subject }} {{ report.grade }}
                            week:{{ report.week_number }}/{{ report.lesson_number }}
                          </label>
                          <ul class="text-gray-500">
                            <li v-for="(topic,index) in report.topics" :key="index">{{ topic }}</li>
                          </ul>
                        </div>
                      </div>
                      <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                          <input id="select-all" aria-describedby="comments-description"
                                 v-model="selectAll"
                                 name="selectAll"
                                 type="checkbox"
                                 class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                          <label for="select-all" class="font-medium text-gray-700">
                            Select All
                          </label>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-4 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
              <button type="button"
                      class="button button-secondary button-small mr-2"
                      @click="cancel()" ref="cancelButtonRef">
                Cancel
              </button>
              <button type="button"
                      class="button button-primary button-small"
                      @click="submit()">
                Preview
              </button>
            </div>
          </div>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script>
import {ref} from 'vue'
import {Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {CheckIcon} from '@heroicons/vue/outline'
import {useForm} from "@inertiajs/inertia-vue3";

export default {
  name: 'PrintModal',
  components: {
    Dialog,
    DialogOverlay,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
    CheckIcon,
  },
  props: {
    modelValue: {
      type: Boolean,
      default: false
    },
    printList: {
      type: Array,
      required: true
    },
    schoolId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      form: useForm({
        school_id: this.schoolId,
        print_list: this.printList,
      }),
      selectAll: false
    };
  },
  emits: ['update:modelValue'],
  methods: {
    submit() {
      this.form.post(route('dashboard.reports.print'), {
        onSuccess: () => this.$emit('update:modelValue', false),
      });
    },
    cancel() {
      setTimeout(() => {
        this.form.print_list.forEach((list, index) => {
          this.form.print_list[index].print = false;
        });
      }, 1000);
      this.$emit('update:modelValue', false)
    }
  },
  watch: {
    selectAll() {
      if (this.selectAll == true) {
        this.form.print_list.forEach((list, index) => {
          this.form.print_list[index].print = true;
        });
      }
    },
    'form.print_list': {
      deep: true,
      handler(printLists) {
        printLists.forEach(list => {
          if (list.print == false) {
            this.selectAll = false;
          }
        })
      }
    }
  }
}
</script>
