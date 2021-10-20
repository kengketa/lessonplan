<template>
  <div
    :class="
      isShowLine
        ? 'sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5'
        : ''
    "
  >
    <label
      v-if="label"
      :for="id"
      class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
      >{{ label }}</label
    >
    <div class="mt-1 sm:mt-0 sm:col-span-2">
      <div class="relative rounded-md shadow-sm max-w-lg">
        <!-- <input  :id="id" v-bind="$attrs" ref="input" :value="modelValue"
               @input="$emit('update:modelValue', $event.target.value)"
               class="block w-full border-gray-300 focus:outline-none sm:text-sm rounded-md"
               :class="{ 'pr-10 text-red-900 border-red-300 focus:ring-red-500 focus:border-red-500': error, 'focus:ring-primary-500': !error }"
               v-bind:aria-invalid="error ? 'true' : null"
               v-bind:aria-describedby="error ? `${id}-error` : null" /> -->
        <select
          :id="id"
          :value="modelValue"
          class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
          :class="{
            'pr-10 text-red-900 border-red-300 focus:ring-red-500 focus:border-red-500': error,
            'focus:ring-primary-500': !error,
          }"
          @input="$emit('update:modelValue', $event.target.value)"
          v-bind:aria-invalid="error ? 'true' : null"
          v-bind:aria-describedby="error ? `${id}-error` : null"
          v-bind="$attrs"
        >
          <slot name="option" />
        </select>
        <div
          v-if="error"
          class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
        >
          <ExclamationCircleIcon
            class="h-5 w-5 text-red-500"
            aria-hidden="true"
          />
        </div>
      </div>
      <p v-if="error" class="mt-2 text-sm text-red-600" :id="`${id}-error`">
        {{ error }}
      </p>
    </div>
  </div>
</template>

<script>
import { ExclamationCircleIcon } from "@heroicons/vue/solid";

const makeid = (length) => {
  let result = "";
  const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
  const charactersLength = characters.length;
  for (let i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
};

export default {
  inheritAttrs: false,
  props: {
    id: {
      default() {
        return `select-input-${makeid(10)}`;
        // return `select-input-123`
      },
    },
    modelValue: [String, Number],
    options: Array,
    label: String,
    error: String,
    isShowLine: {
      type: Boolean,
      default: true,
    },
  },
  emits: ["update:modelValue"],
  components: {
    ExclamationCircleIcon,
  },
};
</script>
