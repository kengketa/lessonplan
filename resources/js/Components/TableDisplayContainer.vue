<template>
  <div class="my-2 sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle min-w-full sm:px-6 lg:px-8">
      <div
        class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"
      >
        <div class="overflow-x-auto w-full">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <slot name="header" />
            </thead>
            <tbody>
            <slot name="body" />
            </tbody>
          </table>
        </div>
        <slot name="pagination" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "TableDisplayContainer",
  components: {},
};
</script>
