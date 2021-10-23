<template>
  <div>
    <div class="content m-1">
      <Breadcrumbs :breadcrumbs="breadcrumbs" />
      <PageHeading></PageHeading>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layout";
import PageHeading from "@/Components/PageHeading";
import {Link} from "@inertiajs/inertia-vue3";
import Breadcrumbs from "@/Components/Breadcrumbs";

export default {
  name: "Dashboard",
  layout: Layout,
  components: {
    Breadcrumbs
  },
  props: {},
  data() {
    return {
      breadcrumbs: [],
    };
  },
  methods: {},
  watch: {},
  computed: {}
};
</script>

