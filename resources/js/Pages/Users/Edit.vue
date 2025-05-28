<template>
  <div>
    <Breadcrumbs
      :back="route('dashboard.users.show', userModel.id)"
      :breadcrumbs="breadcrumbs"
    />
    <PageHeading> Edit User: {{ userModel.name }}</PageHeading>
    <div>
      <UserForm
        :companies="companies"
        :roles="roles"
        :schools="schools"
        :user="userModel"
        type="edit"
      />
    </div>
  </div>
</template>

<script>
import Layout from "@/Shared/Layout";
// import Form from "./Form.vue";
import UserForm from "./UserForm.vue";
import Breadcrumbs from "@/Components/Breadcrumbs";
import PageHeading from "@/Components/PageHeading";

export default {
  name: "Index",
  layout: Layout,
  components: {PageHeading, Breadcrumbs, UserForm},
  props: {
    roles: Object,
    userModel: Object,
    errors: Object,
    companies: Object,
    schools: Array
  },
  data() {
    return {
      breadcrumbs: [
        // { name: 'Dashboard', href: '#' },
        {name: "Users", href: route("dashboard.users.index")},
        {
          name: this.userModel.name,
          href: route("dashboard.users.show", this.userModel.id),
        },
        {name: "Edit", href: "#"},
      ],
    };
  },
  methods: {},
};
</script>
