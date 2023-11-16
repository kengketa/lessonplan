<template>
  <div>
    <Breadcrumbs
      :back="route('dashboard.schools.index')"
      :breadcrumbs="breadcrumbs"
    />
    <PageHeading>
      Calendar
    </PageHeading>
    <div>
      <vue-pdf-embed v-if="schoolCalendar" :source="schoolCalendar"/>
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumbs from '@/Components/Breadcrumbs';
import PageHeading from '@/Components/PageHeading';
import VuePdfEmbed from 'vue-pdf-embed'

export default {
  name: 'SchoolCalendar',
  components: {Breadcrumbs, PageHeading, VuePdfEmbed},
  layout: Layout,
  props: {
    schoolCalendar: null,
    errors: Object,
  },
  data() {
    return {
      breadcrumbs: [
        {name: 'Schools', href: route('dashboard.schools.index')},
        {name: 'Calendar', href: '#'},
      ],
    };
  },
  methods: {},
  mounted() {
  }
};
</script>
