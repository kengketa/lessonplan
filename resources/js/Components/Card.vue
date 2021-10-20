<template>
  <div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
      <slot />
    </div>
<!--    <div slot="footer" class="bg-gray-50 px-4 py-4 sm:px-6">-->
<!--      <slot />-->
<!--    </div>-->
  </div>
</template>


<script>
export default {
  name: "Card"
}
</script>

<style scoped>

</style>
