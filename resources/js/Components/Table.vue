<template>
  <div class="my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    {{ table.head }}
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <div
        class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"
      >
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                ID
              </th>
              <th
                v-for="header in table.header"
                :key="header"
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                {{ header }}
              </th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(item, itemIndex) in table.dataSet.data"
              :key="item.id"
              :class="itemIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
            >
              <td
                v-for="(col, colIndex, index) in item"
                :key="colIndex"
                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
              >
                <span v-if="index == 1">
                  <inertia-link
                    :href="route(showSlug, item.id)"
                    class="hover:underline"
                  >
                    {{ col }}
                  </inertia-link>
                </span>
                <span v-else>
                  {{ col }}
                </span>
              </td>
              <td class="grid">
                <inertia-link
                  :href="route(editSlug, item.id)"
                  class="button button-primary hover:underline"
                >
                  Edit
                </inertia-link>
              </td>
            </tr>
            <tr v-if="table.dataSet.data.length == 0">
              <td class="text-center h-10" :colspan="table.header.length + 1">Not found</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "table component",
  components: {},
  props: {
    table: Object,
    showSlug: String,
    editSlug: String,
  },
  data() {
    return {
      showSlug: this.table.slug + ".show",
      editSlug: this.table.slug + ".edit",
    };
  },
  watch: {},
  methods: {},
};
</script>
