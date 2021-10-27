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
            <div class="relative">
              <div>
                <DialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-900 text-center">
                  Generated Link
                </DialogTitle>
              </div>
              <div class="bg-gray-100 px-2 py-4 mt-2 text-center relative">
                <!--                <input class="w-full h-20" :value="link" ref="linkref" v-on:focus="$event.target.select()" readonly>-->
                <textarea class="w-full bg-gray-100 outline-none focus:outline-none border-0 text-center"
                          rows="2"
                          :value="link" ref="linkref"
                          v-on:focus="$event.target.select()"></textarea>
                <button
                  @click="copy()"
                  class="text-gray-700 absolute top-2 right-2 rounded-full p-1 transition ease-in-out duration-300 hover:bg-gray-200">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                       stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                  </svg>
                </button>
              </div>
              <p
                class="absolute -top-2 right-2 transition ease-out duration-100 text-sm bg-green-100 px-2 py-1 rounded-md"
                :class="coppied ? 'opacity-200' : 'opacity-0'"
              >
                coppied!
              </p>
            </div>
            <div class="flex justify-center mt-2">
              <button type="button"
                      class="button button-secondary button-small mr-2"
                      @click="close()" ref="cancelButtonRef">
                CLose
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


export default {
  name: 'GeneratedLinkModal',
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
    link: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      coppied: false
    };
  },
  emits: ['update:modelValue'],
  methods: {
    submit() {
      this.form.post(route('dashboard.reports.print'), {
        onSuccess: () => this.$emit('update:modelValue', false),
      });
    },
    copy() {
      this.$refs.linkref.focus();
      document.execCommand('copy');
      this.coppied = true;
    },
    close() {
      this.coppied = false;
      this.$emit('update:modelValue', false);
    }
  }
}
</script>
<style scoped>
textarea {
  border: none;
  overflow: auto;
  outline: none;

  -webkit-box-shadow: none;
  -moz-box-shadow: none;
  box-shadow: none;

  resize: none; /*remove the resize handle on the bottom right*/
}

::-moz-selection { /* Code for Firefox */
  background: transparent;
}

::selection {
  background: transparent;
}
</style>
