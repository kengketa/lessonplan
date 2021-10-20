<template>
  <inertia-link class="w-full md:w-1/2 xl:w-1/3 p-3" :href="route(link)">
    <div class="bg-white border rounded shadow p-2">
      <div class="flex flex-row items-center">
        <div class="flex-shrink pr-4">
          <slot name="icon" />
        </div>
        <div class="flex-1 text-right md:text-center">
          <h5 class="font-bold uppercase text-gray-500">
            <slot name="head" />
          </h5>
          <h3 class="font-bold text-3xl">
            <slot name="body" />
          </h3>
        </div>
      </div>
    </div>
  </inertia-link>
</template>
<script>
import { Inertia } from "@inertiajs/inertia";

export default {
  name: "DashboardCard",
  components: { Inertia },
  props: {
      link:String
  },
  data() {

    },
}
</script>

<style scoped>

</style>
