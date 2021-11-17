<template>
  <div>
    <div v-for="(timeSheet,index) in timeSheets" :key="index" class="w-full page-break">
      <p class="text-center text-2xl">{{ timeSheet.school_name }}</p>
      <p class="text-center text-xl">{{ timeSheet.teacher_name }} Clock-In Report</p>
      <p class="text-center text-lg">{{ timeSheet.month }} {{ timeSheet.year }}</p>
      <div class="px-6 mt-4">
        <table class="min-w-full divide-y divide-gray-300">
          <thead class="bg-gray-50">
          <th
            class="w-1/6 px-6 py-2 text-left whitespace-nowrap text-xs font-medium text-gray-800 uppercase tracking-wider border border-gray-300">
            Date
          </th>
          <th
            class="w-1/6 px-6 py-2 text-left whitespace-nowrap text-xs font-medium text-gray-800 uppercase tracking-wider border border-gray-300">
            Clock-In
          </th>
          <th
            class="w-1/6 px-6 py-2 text-left whitespace-nowrap text-xs font-medium text-gray-800 uppercase tracking-wider border border-gray-300">
            Clock-Out
          </th>
          <th
            class="w-3/6 px-6 py-2 text-left whitespace-nowrap text-xs font-medium text-gray-800 uppercase tracking-wider border border-gray-300">
            Comment
          </th>
          </thead>
          <tbody>
          <tr v-for="(clockIn,clockInIndex) in timeSheet.data" :key="clockInIndex"
              :class="clockIn.day == 'Sat' || clockIn.day == 'Sun' ? 'hidden' :''">
            <td class="px-6 py-1 whitespace-nowrap text-sm font-light text-gray-800 border border-gray-300">
              {{ clockIn.displayDate }}
            </td>
            <td class="px-6 py-1 whitespace-nowrap text-sm font-light text-gray-800 border border-gray-300">
              {{ clockIn.clockedIn != null ? clockIn.clockedIn.clock_in : '-' }}
            </td>
            <td class="px-6 py-1 whitespace-nowrap text-sm font-light text-gray-800 border border-gray-300">
              {{ clockIn.clockedIn != null ? clockIn.clockedIn.clock_out : '-' }}
            </td>
            <td class="px-6 py-1 whitespace-nowrap text-sm font-light text-gray-800 border border-gray-300"></td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'ClockInPrintPreview',
  components: {},
  props: {
    timeSheets: {
      type: Object,
      required: true
    }
  },
  data() {
    return {};
  },
  methods: {
    print() {
      window.print();
    },
  },
  mounted() {

  }
};
</script>
<style scoped>

@media print {
  @page {
    margin: 1cm 0.5cm;
    size: A4 portrait;
  }

  .page-break {
    page-break-after: always;
  }

  .no-print, .no-print * {
    display: none !important;
  }
}
</style>
