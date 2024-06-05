<template>
  <div>
    <label v-if="label" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">{{ label }}</label>
    <div class="mt-1">
      <div class="flex flex-col justify-center items-center">
        <Switch
          :id="id"
          :class="[modelValue ? 'bg-blue-800' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer shadow transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-800']"
          :model-value="modelValue"
          @update:modelValue="updateValue"
        >
          <span class="sr-only">{{ label }}</span>
          <span
            :class="[modelValue ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']"
            aria-hidden="true"
          />
        </Switch>
        <label v-show="description" class="ml-2 text-sm cursor-pointer" @click="updateValue(!modelValue)">
          {{ description }}
        </label>
      </div>
    </div>
  </div>
</template>

<script>
import {ExclamationCircleIcon} from '@heroicons/vue/solid';
import {Switch} from '@headlessui/vue';

const makeid = (length) => {
  let result = '';
  const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
  const charactersLength = characters.length;
  for (let i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
};

export default {
  components: {
    ExclamationCircleIcon,
    Switch,
  },
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      default() {
        return `text-toggle-${makeid(10)}`;
        // return `text-input-123`
      },
    },
    placeholder: {
      type: String,
      default: '',
    },
    type: {
      type: String,
      default: 'text',
    },
    modelValue: {
      type: Boolean
    },
    label: String,
    description: String,
    error: String,
    isShowLine: {
      type: Boolean,
      default: true,
    },
  },
  emits: ['update:modelValue', 'change'],
  methods: {
    updateValue(event) {
      this.$emit('update:modelValue', event);
      this.$emit('change');
    }
  },
};
</script>
