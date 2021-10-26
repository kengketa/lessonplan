<template>
  <div class="bg-white overflow-hidden shadow rounded-lg"
       :class="computedBorderColour"
  >
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
  name: "Card",
  props: {
    approve: {
      type: String,
      default: 'unapproved'
    },
  },
  computed: {
    computedBorderColour() {
      if (this.approve === 'approved') {
        return 'border-2 border-green-500';
      }
      if (this.approve === 'waiting') {
        return 'border-2 border-yellow-500';
      }
      return '';
    }
  }
}
</script>

<style scoped>

</style>
