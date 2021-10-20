<template>
  <TransitionRoot as="template" :show="modelValue">
    <Dialog
      as="div"
      static
      class="fixed z-10 inset-0 overflow-y-auto"
      @close="$emit('update:modelValue', false)"
      :open="modelValue"
    >
      <div
        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
      >
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="ease-in duration-200"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <DialogOverlay
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
          />
        </TransitionChild>

        <!-- This element is to trick the browser into centering the modal contents. -->

        <span
          class="hidden sm:inline-block sm:align-middle sm:h-screen"
          aria-hidden="true"
          >&#8203;</span
        >

        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          enter-to="opacity-100 translate-y-0 sm:scale-100"
          leave="ease-in duration-200"
          leave-from="opacity-100 translate-y-0 sm:scale-100"
          leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
          <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
          >
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div
                  :class="`mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-${themeColor}-100 sm:mx-0 sm:h-10 sm:w-10`"
                >
                  <ExclamationIcon
                    :class="`h-6 w-6 text-${themeColor}-600`"
                    aria-hidden="true"
                  />
                </div>

                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                  <DialogTitle
                    as="h3"
                    class="text-lg leading-6 font-medium text-gray-900"
                  >
                    {{ title }}
                  </DialogTitle>

                  <div class="mt-2">
                    <p class="text-sm text-gray-500">
                      {{ body }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <div
              class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
            >
              <button
                type="button"
                :class="`w-full button bg-${themeColor}-600 text-white hover:bg-${themeColor}-700 focus:ring-${themeColor}-500 sm:ml-3 sm:w-auto`"
                @click="onConfirm"
              >
                {{ confirmText }}
              </button>

              <button
                type="button"
                class="mt-3 button button-secondary sm:mt-0 sm:ml-3 sm:w-auto"
                @click="$emit('update:modelValue', false)"
                ref="cancelButtonRef"
              >
                {{ cancelText }}
              </button>
            </div>
          </div>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script>
import {
  Dialog,
  DialogOverlay,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";

import { ExclamationIcon } from "@heroicons/vue/outline";
export default {
  components: {
    Dialog,
    DialogOverlay,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
    ExclamationIcon,
  },
  props: {
    modelValue: {
      type: Boolean,
    },
    title: { type: String, default: "Confirm Action" },
    body: { type: String, default: "Are you sure to do this action?" },
    confirmText: { type: String, default: "Confirm" },
    cancelText: { type: String, default: "Cancel" },
    confirmEvent: Function,
    themeColor: {
      type: String,
      default: "red",
    },
  },
  emits: ["update:modelValue"],
  methods: {
    onConfirm() {
      this.confirmEvent();
      this.$emit("update:modelValue", false);
    },
  },
};
</script>
