<template>
  <div>
    <Breadcrumbs
      :breadcrumbs="breadcrumbs"
      :back="route('dashboard.schools.show',report.school.id)"
    />
    <PageHeading>
      <div class="flex sm:justify-between">
        <p>Edit Lesson Plan</p>
        <button @click="next()" type="button" class="button button-primary">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>
    </PageHeading>
    <div>
      <ReportForm :report="report" type="edit" />
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout';
import ReportForm from "@/Components/Forms/ReportForm";
import Breadcrumbs from '@/Components/Breadcrumbs';
import PageHeading from '@/Components/PageHeading';

export default {
  name: 'ReportEdit',
  components: {Breadcrumbs, PageHeading, ReportForm},
  layout: Layout,
  props: {
    report: {
      type: Object,
      required: true
    },
    errors: Object,
  },
  data() {
    return {
      breadcrumbs: [
        {name: this.report.school.name, href: route('dashboard.schools.show', this.report.school.id)},
        {name: 'Edit Lesson Plan', href: '#'},
      ],
    };
  },
  methods: {
    next() {
      let url = route('dashboard.reports.next', this.report.id);
      this.$inertia.post(url);
    }
  },
  mounted() {
  }
};
</script>
