<template>
  <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
    <dt class="text-sm font-medium text-gray-500">
      <slot name="label" />
    </dt>
    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
      <slot />
    </dd>
  </div>
</template>

<script>
export default {
  name: "DataDisplayRow"
}
</script>

<style scoped>

</style>
