<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <TransitionRoot as="template" :show="modelValue">
    <Dialog as="div" class="fixed z-30 inset-0 overflow-y-auto" @close="$emit('update:modelValue', false)">
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
              <div class="text-center">
                <DialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-900">
                  Add Grade
                </DialogTitle>
                <div class="mt-2">
                  <SearchSelectInput :is-show-line="false"
                                     label="ประเภท"
                                     v-model="form.type"
                                     :error="form.errors.type"
                                     :options="[
                        {
                          id:1,
                          name:'ศูนย์เด็ก'
                        },
                        {
                          id:2,
                          name:'อนุบาล'
                        },
                        {
                          id:3,
                          name:'ประถม'
                        },
                         {
                          id:4,
                          name:'มัธยม'
                        },
                  ]"
                  />
                  <TextInput
                    name="level"
                    v-model="form.level"
                    :error="form.errors.level"
                    :is-show-line="false"
                    label="ชั้น"
                    type="number"
                    placeholder="เช่น 1"
                  />
                  <TextInput
                    name="room_number"
                    v-model="form.room_number"
                    :error="form.errors.room_number"
                    :is-show-line="false"
                    label="ห้องที่"
                    type="number"
                    placeholder="ห้องที่"
                  />
                </div>
              </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
              <button type="button"
                      class="button button-secondary button-small"
                      @click="$emit('update:modelValue', false)" ref="cancelButtonRef">
                Cancel
              </button>
              <button type="button"
                      class="button button-primary button-small"
                      @click="submit()">
                Submit
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
import TextInput from "@/Components/TextInput";
import {useForm} from "@inertiajs/inertia-vue3";
import SearchSelectInput from "@/Components/SearchSelectInput";

export default {
  name: 'AddGradeModal',
  components: {
    TextInput,
    Dialog,
    DialogOverlay,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
    CheckIcon,
    SearchSelectInput,
  },
  props: {
    modelValue: {
      type: Boolean,
      default: false
    },
    schoolId: {
      type: Number,
      require: true
    }
  },
  data() {
    return {
      form: useForm({
        school_id: this.schoolId,
        type: null,
        level: null,
        room_number: null
      }),
    };
  },
  emits: ['update:modelValue'],
  methods: {
    submit() {
      this.form.post(route('dashboard.grades.store'), {
        onSuccess: () => this.$emit('update:modelValue', false),
      });
    },
  }
}
</script>
