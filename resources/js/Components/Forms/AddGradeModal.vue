<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <TransitionRoot :show="modelValue" as="template">
    <Dialog as="div" class="fixed z-30 inset-0 overflow-y-auto" @close="$emit('update:modelValue', false)">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                         enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100"
                         leave-to="opacity-0">
          <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"/>
        </TransitionChild>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span aria-hidden="true" class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
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
                  <SearchSelectInput v-model="form.type"
                                     :error="form.errors.type"
                                     :is-show-line="false"
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
                                     label="ประเภท"
                  />
                  <TextInput
                    v-model="form.level"
                    :error="form.errors.level"
                    :is-show-line="false"
                    label="ชั้น"
                    name="level"
                    placeholder="เช่น 1"
                    type="number"
                  />
                  <TextInput
                    v-model="form.room_number"
                    :error="form.errors.room_number"
                    :is-show-line="false"
                    label="ห้องที่"
                    name="room_number"
                    placeholder="ห้องที่"
                    type="number"
                  />
                </div>
              </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
              <button ref="cancelButtonRef"
                      class="button button-secondary button-small"
                      type="button" @click="$emit('update:modelValue', false)">
                Cancel
              </button>
              <button class="button button-primary button-small"
                      type="button"
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
        onSuccess: () => {
          this.form.type = null;
          this.form.level = null;
          this.form.room_number = null;
          this.$emit('update:modelValue', false)
        },
      });
    },
  }
}
</script>
