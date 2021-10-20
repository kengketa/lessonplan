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
      <DatePicker class="max-w-lg rounded-md shadow-sm"
                  @update:modelValue="updateModelValue($event)"
                  :modelValue="modelValue"
                  :masks="masks"
                  :update-on-input="false"
      >
        <template v-slot="{ inputValue, inputEvents, togglePopover }">
          <div class="flex items-center rounded">
            <button
              type="button"
              class="p-2 button-primary rounded-l focus:outline-none focus:ring-2 focus:ring-primary-500"
              @click="togglePopover()"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                class="w-6 h-6 fill-current"
              >
                <path
                  d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z"
                ></path>
              </svg>
            </button>
            <input
              :value="inputValue"
              v-on="inputEvents"
              class="block w-full p-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>
        </template>
      </DatePicker>
      <!--            <DatePicker v-model="modelValue" />-->
      <!--            <p v-if="error" class="mt-2 text-sm text-red-600" :id="`${id}-error`">-->
      <!--                {{ error }}-->
      <!--            </p>-->
    </div>
  </div>
</template>

<script>
import {ExclamationCircleIcon} from "@heroicons/vue/solid";
import {DatePicker} from 'v-calendar';

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
  name: "DatePickerInput",
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      default() {
        return `text-input-${makeid(10)}`;
        // return `text-input-123`
      },
    },
    modelValue: {
      type: [Date, String]
    },
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
    DatePicker
  },
  data() {
    return {
      masks: {
        input: 'DD/MM/YYYY',
      },
    }
  },
  methods: {
    updateModelValue(newValue) {
      this.$emit('update:modelValue', newValue);
    }
  }
}
</script>

<style scoped>

</style>
