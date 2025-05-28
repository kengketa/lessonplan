<template>
  <div>
    <Breadcrumbs :back="route('dashboard.users.index')" :breadcrumbs="breadcrumbs"/>
    <PageHeading>
      Create User
    </PageHeading>

    <div>
      <UserForm :roles="roles" :schools="schools" :user="user_model" type="create"/>
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layout";
import Form from "./Form.vue";
import UserForm from "./UserForm.vue";
import Breadcrumbs from "@/Components/Breadcrumbs";
import PageHeading from "@/Components/PageHeading";

export default {
  name: "Index",
  layout: Layout,
  components: {Breadcrumbs, Form, UserForm, PageHeading},
  props: {
    roles: Object,
    companies: Object,
    user_model: Object,
    errors: Object,
    title: String,
    schools: Array
  },
  data() {
    return {
      breadcrumbs: [
        {name: "Users", href: route("dashboard.users.index")},
        {name: "Create User", href: "#"},
      ],
    };
  },
  methods: {},
};
</script>
