<template>
  <div
    v-if="$page.props.flash.success != null || $page.props.flash.error != null"
  >
    <div :class="`rounded-md bg-${getColorString}-50 p-4 mb-3`">
      <div class="flex">
        <div class="flex-shrink-0">
          <CheckCircleIcon
            v-if="$page.props.flash.success != null"
            class="h-5 w-5 text-green-400"
            aria-hidden="true"
          />
          <XCircleIcon
            v-if="$page.props.flash.error != null"
            class="h-5 w-5 text-red-400"
            aria-hidden="true"
          />
        </div>
        <div class="ml-3">
          <p :class="`text-sm font-medium text-${getColorString}-800`">
            {{ $page.props.flash.success ?? $page.props.flash.error }}
          </p>
        </div>
        <div class="ml-auto pl-3">
          <div class="-mx-1.5 -my-1.5">
            <button
              @click="hide"
              type="button"
              :class="`inline-flex bg-${getColorString}-50 rounded-md p-1.5 text-${getColorString}-500 hover:bg-${getColorString}-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-${getColorString}-50 focus:ring-${getColorString}-600`"
            >
              <span class="sr-only">Dismiss</span>
              <XIcon class="h-5 w-5" aria-hidden="true" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { CheckCircleIcon, XCircleIcon, XIcon } from "@heroicons/vue/solid";
export default {
  components: {
    CheckCircleIcon,
    XCircleIcon,
    XIcon,
  },

  methods: {
    hide: function () {
      this.$page.props.flash.success = null;
      this.$page.props.flash.error = null;

    },
  },
  computed: {
    getColorString() {
      if (this.$page.props.flash.success != null) {
        return `green`;
      } else if (this.$page.props.flash.error != null) {
        return `red`;
      }
    },
  },
  mounted() {},
};
</script>
