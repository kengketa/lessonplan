<template>
  <div>
    <Breadcrumbs
      :breadcrumbs="breadcrumbs"
      :back="route('dashboard.reports.index')"
    />
    <PageHeading>
      Create Lesson Plan
    </PageHeading>
    <div>
      <ReportForm :report="report" type="create" />
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout';
import ReportForm from '@/Components/Forms/ReportForm';
import Breadcrumbs from '@/Components/Breadcrumbs';
import PageHeading from '@/Components/PageHeading';

export default {
  name: 'ReportCreate',
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
        {name: 'Create Lesson plan', href: '#'},
      ],
    };
  },
  methods: {},
  mounted() {
  }
};
</script>
