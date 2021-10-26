<template>
  <div
    class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6"
  >
    <!-- desktop pagination -->
    <div class="flex-1 hidden lg:flex items-center justify-between ">
      <div>
        <p class="text-sm text-gray-700">
          Showing

          {{ " " }}

          <span class="font-medium">{{ data.from }}</span>

          {{ " " }}

          to

          {{ " " }}

          <span class="font-medium">{{ data.to }}</span>

          {{ " " }}

          of

          {{ " " }}

          <span class="font-medium">{{ data.total }}</span>

          {{ " " }}

          results
        </p>
      </div>

      <div>
        <nav
          class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
        >
          <div v-for="link in data.links" :key="link.label">
            <Link
              v-if="link.url != null && link.label.includes('Previous')"
              class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
              :href="link.url"
            >
              <span class="sr-only">Previous</span>
              <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
            </Link>
            <div
              v-else-if="link.url == null && link.label.includes('Previous')"
              class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300"
            >
              <span class="sr-only">Previous</span>
              <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
            </div>

            <Link
              v-else-if="link.url != null && link.label.includes('Next')"
              class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
              :href="link.url"
            >
              <span class="sr-only">Next</span>
              <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
            </Link>

            <div
              v-else-if="link.url == null && link.label.includes('Next')"
              class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300"
            >
              <span class="sr-only">Next</span>
              <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
            </div>

            <Link
              v-else-if="link.url != null"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
              :href="link.url"
              :class="{ 'bg-gray-50': link.active }"
            >
              {{ link.label }}
            </Link>

            <div
              v-else
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500"
            >
              {{ link.label }}
            </div>
          </div>
        </nav>
      </div>
    </div>
    <!-- end desktop pagination -->

    <!-- mobile pagination -->
    <div class="lg:hidden w-full">
      <nav
        class="relative z-0 inline-flex w-full justify-between rounded-md -space-x-px"
        aria-label="Pagination"
      >
        <div v-for="link in data.links" :key="link.label">
          <Link
            v-if="link.url != null && link.label.includes('Previous')"
            class="relative inline-flex items-center px-3 py-2 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
            :href="link.url"
          >
            <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
            <span class="mx-2">Previous</span>
          </Link>
          <div
            v-else-if="link.url == null && link.label.includes('Previous')"
            class="relative inline-flex items-center px-3 py-2 rounded-md border border-gray-200 bg-white text-sm font-medium text-gray-300"
          >
            <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
            <span class="mx-2">Previous</span>
          </div>

          <Link
            v-else-if="link.url != null && link.label.includes('Next')"
            class="relative inline-flex items-center px-3 py-2 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
            :href="link.url"
          >
            <span class="mx-2">Next</span>
            <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
          </Link>

          <div
            v-else-if="link.url == null && link.label.includes('Next')"
            class="relative inline-flex items-center px-3 py-2 rounded-md border border-gray-200 bg-white text-sm font-medium text-gray-500"
          >
            <span class="mx-2">Next</span>
            <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
          </div>
        </div>
      </nav>
    </div>
    <!-- end mobile pagination -->
  </div>

</template>


<script>
import {ChevronLeftIcon, ChevronRightIcon} from "@heroicons/vue/solid";
import {Link} from "@inertiajs/inertia-vue3";

export default {
  components: {ChevronLeftIcon, ChevronRightIcon, Link},
  props: {
    data: Object,
  },
  data() {
    return {};
  },
};
</script>
