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
    >{{ label }}</label>
    <div class="mt-1 sm:mt-0 sm:col-span-2">
      <div class="relative rounded-md shadow-sm max-w-lg">
        <span class="inline-block w-full rounded-md shadow-sm">
          <button
            type="button"
            class="relative z-0 w-full py-2.5 pl-3 pr-10 text-left transition cursor-pointer duration-150 ease-in-out bg-white border border-gray-300 rounded-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5"
            @click="open"
          >
            <span class="block truncate">{{
                selectedOption == null ? selectText : selectedOption.name
              }}</span>
            <span
              class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
            >
              <svg
                class="w-5 h-5 text-gray-400"
                viewBox="0 0 20 20"
                fill="none"
                stroke="currentColor"
              >
                <path
                  d="M7 7l3-3 3 3m0 6l-3 3-3-3"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </span>
          </button>
        </span>

        <div v-show="isOpen">
          <div
            class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg"
            @mouseover="isOnSelecting = true"
            @mouseleave="isOnSelecting = false"
          >
            <input
              ref="searchInput"
              v-model="search"
              class="block w-full border-gray-300 focus:outline-none sm:text-sm rounded-md"
              :class="{
                'pr-10 text-red-900 border-red-300 focus:ring-red-500 focus:border-red-500': error,
                'focus:ring-primary-500': !error,
              }"
              placeholder="Search options"
              type="text"
              @blur="closeWithBlur"
              @keydown.esc="close"
              @keydown.up="highlightPrev"
              @keydown.down="highlightNext"
              @keydown.enter.prevent="selectHighlighted"
              @keydown.tab.prevent
            >
            <ul
              ref="optionsSelect"
              class="py-1 overflow-y-scroll text-base leading-6 rounded-md shadow-xs max-h-36 focus:outline-none sm:text-sm sm:leading-5"
            >
              <li
                v-for="(item, index) in filteredOptions"
                :key="item.id"
                :class="[
                  `relative py-2 pl-3 select-none pr-9 hover:bg-blue-500 hover:text-white rounded-md cursor-pointer`,
                  index == highlightedIndex
                    ? 'bg-blue-500 text-white'
                    : 'text-gray-900 ',
                ]"
                @click="select(item)"
              >
                <span class="block font-normal truncate">{{ item.name }} </span>
              </li>
              <li
                v-if="filteredOptions.length === 0"
                :class="[
                  `relative py-2 pl-3 text-gray-900 select-none pr-9 rounded-md cursor-default`,
                ]"
              >
                <span
                  class="block font-normal truncate"
                >No results found for "{{ search }}"</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <p
        v-if="error"
        :id="`${id}-error`"
        class="mt-2 text-sm text-red-600"
      >
        {{ error }}
      </p>
    </div>
  </div>
</template>

<script>
import {ExclamationCircleIcon} from '@heroicons/vue/solid';
import {defineComponent, ref, onMounted, computed} from 'vue';

const makeid = (length) => {
  let result = '';
  const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
  const charactersLength = characters.length;
  for (let i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
};

export default defineComponent({
  components: {
    ExclamationCircleIcon,
  },
  props: {
    id: {
      type: String,
      default() {
        return `search-select-input-${makeid(10)}`;
      },
    },
    options: {required: true, type: Array},
    selectText: {
      type: String,
      default: 'Select an option',
    },
    modelValue: {
      type: Number,
      default: 0,
    },
    type: {
      type: String,
      default: 'text',
    },
    label: String,
    error: String,
    isShowLine: {
      type: Boolean,
      default: true,
    },
    filterFunction: {
      default: (search, options) => {
        // console.log(options);
        return options.filter((option) => {
          // console.log(option.name);
          return (
            option.name &&
            option.name.toLowerCase().includes(search.toLowerCase())
          );
        });
      },
      type: Function,
    },
  },
  setup(props, context) {
    const searchInput = ref();
    const optionsSelect = ref();

    const isOpen = ref(true);
    const search = ref('');
    const highlightedIndex = ref(0);
    const isOnSelecting = ref(false);
    onMounted(() => {
      isOpen.value = false;
    });

    const open = async () => {
      isOpen.value = true;
      await setInterval(() => {
      }, 0);
      highlight(selectedOptionIndex.value);
      searchInput.value.focus();
    };
    // methods
    const close = () => {
      isOpen.value = false;
    };

    const scrollToHighlighted = () => {
      if (filteredOptions.value.length == 0) {
        return;
      }
      optionsSelect.value.children[highlightedIndex.value].scrollIntoView({
        block: 'nearest',
      });
    };
    const select = async (item) => {
      await context.emit('update:modelValue', item.id);
      search.value = '';
      highlightedIndex.value = selectedOptionIndex.value;
      close();
    };
    const selectHighlighted = () => {
      select(filteredOptions.value[highlightedIndex.value]);
    };
    const highlight = (index) => {
      highlightedIndex.value = index;

      if (highlightedIndex.value < 0) {
        highlightedIndex.value = filteredOptions.value.length + 1;
      }

      if (highlightedIndex.value > filteredOptions.value.length - 1) {
        highlightedIndex.value = 0;
      }
      scrollToHighlighted();
    };
    const highlightPrev = () => {
      highlight(highlightedIndex.value - 1);
    };
    const highlightNext = () => {
      highlight(highlightedIndex.value + 1);
    };
    const closeWithBlur = (event) => {
      if (!isOnSelecting.value) {
        close();
      }
    };
    // computed
    const filteredOptions = computed(() => {
      const list = props.filterFunction(search.value, props.options);
      // if (list.length > 0) {
      //   list.unshift({ id: null, name: props.selectText });
      // }
      return list;
    });

    const selectedOption = computed(() => {
      return props.options.find((option) => option.id == props.modelValue);
    });

    const selectedOptionIndex = computed(() => {
      const index = filteredOptions.value
        .map(function (e) {
          // console.log(e);
          return e.id;
        })
        .indexOf(props.modelValue);
      return index;
    });

    return {
      closeWithBlur,

      isOnSelecting,
      close,
      filteredOptions,
      highlight,
      highlightNext,
      highlightPrev,
      highlightedIndex,
      isOpen,
      open,
      optionsSelect,
      scrollToHighlighted,
      search,
      searchInput,
      select,
      selectHighlighted,
      selectedOption,
      selectedOptionIndex,
    };
  },
});
</script>

<style>
</style>
