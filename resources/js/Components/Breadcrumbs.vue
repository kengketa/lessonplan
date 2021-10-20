<template>
  <div>
    <nav class="sm:hidden" aria-label="Back" v-if="back">
      <a :href="back" class="flex items-center text-sm font-normal text-gray-500 hover:text-gray-700">
        <ChevronLeftIcon class="flex-shrink-0 -ml-1 mr-1 h-5 w-5 text-gray-400" aria-hidden="true" />
        Back
      </a>
    </nav>
    <nav class="hidden sm:flex" aria-label="Breadcrumb">
      <ol class="flex items-center space-x-4">
        <li>
          <div>
            <inertia-link :href="route('dashboard')" class="text-sm font-normal text-gray-500 hover:text-gray-700">Dashboard</inertia-link>
          </div>
        </li>
        <li v-for="(item, index) in breadcrumbs" :key="item.name">
          <div class="flex items-center">
            <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-400" aria-hidden="true" />
            <inertia-link :href="item.href" class="ml-4 text-sm font-normal text-gray-500 hover:text-gray-700"
               v-bind:aria-current="index === breadcrumbs.length - 1 ? 'page' : null">{{ item.name }}</inertia-link>
          </div>
        </li>
      </ol>
    </nav>
  </div>
</template>

<script>
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/solid'

export default {
  name: "Breadcrumbs",
  props: {
    breadcrumbs: {
      type: Array,
    },
    back: {
      type: String
    }
  },
  components: {
    ChevronLeftIcon,
    ChevronRightIcon,
  },
}
</script>

<style scoped>

</style>
