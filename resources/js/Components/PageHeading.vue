<template>
  <div class="mt-2 md:flex md:items-center md:justify-between mb-6">
    <div class="flex-1 min-w-0">
      <h2 class="text-2xl font-semibold leading-7 text-gray-900 sm:text-3xl sm:truncate">
        <slot />
      </h2>
    </div>
    <div class="mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">
      <slot name="actions"/>
    </div>
  </div>
</template>

<script>
export default {
  name: "PageHeading"
}
</script>

<style scoped>

</style>
