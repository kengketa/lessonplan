<template>
  <div>
    <label class="text-sm font-medium text-gray-700">{{ label }}</label>
    <Editor
      class="mt-2"
      v-model="modelValue"
      api-key="8otka707t8tlcqakojbi97f3ptko4wrduwmf36zfyi3yh6wx"
      :init="customEditor"
      :id="id"
    />
  </div>
</template>


<script>
import {ExclamationCircleIcon} from "@heroicons/vue/solid";
import Editor from '@tinymce/tinymce-vue'
import {useForm} from "@inertiajs/inertia-vue3";

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
  components: {
    ExclamationCircleIcon, Editor
  },
  props: {
    id: {
      type: String,
      default() {
        return `text-input-${makeid(10)}`;
      },
    },
    modelValue: {
      type: String
    },
    label: String,
    error: String,
    height: {
      type: Number,
      default: 500
    }
  },
  data() {
    return {
      customEditor: {
        height: this.height,
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
  watch: {
    modelValue() {
      this.$emit('update:modelValue', this.modelValue)
    }
  },
  emits: ["update:modelValue"],
};
</script>
