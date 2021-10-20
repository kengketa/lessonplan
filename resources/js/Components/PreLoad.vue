<template>
  <TransitionRoot
    enter="ease-out duration-300"
    enter-from="opacity-0"
    enter-to="opacity-100"
    :show="modelValue"
    class="z-10"
  >
    <div
      class="fixed top-0 left-0 z-50 w-screen h-screen flex items-center justify-center"
      style="background: rgba(0, 0, 0, 0.5)"
    >
      <div
        class="bg-white border py-5 px-10 rounded-lg flex items-center flex-col shadow-md animate-bounce"
      >
        <div class="loader-dots block relative w-20 h-5 mt-2">
          <div
            class="absolute top-0 mt-1 w-3 h-3 rounded-full bg-blue-500"
          ></div>
          <div
            class="absolute top-0 mt-1 w-3 h-3 rounded-full bg-blue-500"
          ></div>
          <div
            class="absolute top-0 mt-1 w-3 h-3 rounded-full bg-blue-500"
          ></div>
          <div
            class="absolute top-0 mt-1 w-3 h-3 rounded-full bg-blue-500"
          ></div>
        </div>
        <div class="text-black text-base font-light mt-2 text-center">
          {{ loadingText }}
        </div>
      </div>
    </div>
  </TransitionRoot>
</template>
<script>
import { TransitionRoot } from "@headlessui/vue";

export default {
  components: { TransitionRoot },
  props: {
    loadingText: { type: String, default: "Please wait..." },
    modelValue: { type: Boolean, default: false },
  },
  computed: {
    hasFlash() {
      return (
        this.$page.props.flash.success != null ||
        this.$page.props.flash.error != null ||
        Object.keys(this.$page.props.errors).length > 0
      );
    },
    isShow() {
      if (this.modelValue && this.hasFlash) {
        this.$emit("update:modelValue", false);
        this.$page.props.errors = {};
        this.$page.props.flash.success = null;
        this.$page.props.flash.error = null;
      }
      return this.modelValue;
    },
  },
  emits: ["update:modelValue"],
};
</script>
<style scoped>
.loader-dots div {
  animation-timing-function: cubic-bezier(0, 1, 1, 0);
}
.loader-dots div:nth-child(1) {
  left: 8px;
  animation: loader-dots1 0.6s infinite;
}
.loader-dots div:nth-child(2) {
  left: 8px;
  animation: loader-dots2 0.6s infinite;
}
.loader-dots div:nth-child(3) {
  left: 32px;
  animation: loader-dots2 0.6s infinite;
}
.loader-dots div:nth-child(4) {
  left: 56px;
  animation: loader-dots3 0.6s infinite;
}
@keyframes loader-dots1 {
  0% {
    transform: scale(0);
  }
  100% {
    transform: scale(1);
  }
}
@keyframes loader-dots3 {
  0% {
    transform: scale(1);
  }
  100% {
    transform: scale(0);
  }
}
@keyframes loader-dots2 {
  0% {
    transform: translate(0, 0);
  }
  100% {
    transform: translate(24px, 0);
  }
}
</style>


