<template>
  <div>
    <PreLoad
      v-model="isShowEmailSend"
      :loadingText="`mailing to ${userModel.email}`"
    ></PreLoad>
    <ConfirmDialog
      v-model="isShowDeleteDialog"
      :body="`User ${userModel.name} and its related data will be deleted. This action cannot be undone.`"
      :confirmEvent="deleteUser"
      confirmText="Delete User"
      title="Delete user?"
    ></ConfirmDialog>
    <Breadcrumbs :back="route('dashboard.users.index')" :breadcrumbs="breadcrumbs"/>
    <PageHeading>
      <span class="hidden lg:inline">User information:</span> {{ userModel.name }}
      <template #actions>
        <Link
          :href="route('dashboard.users.edit', userModel.id)"
          class="button button-primary mr-2"
        >
          <PencilIcon aria-hidden="true" class="h-5 w-5 mr-2"/>
          Edit
        </Link
        >
        <form class="inline-flex" @submit.prevent="isShowDeleteDialog = true">
          <button class="button button-danger" type="submit">
            <TrashIcon aria-hidden="true" class="h-5 w-5 mr-2"/>
            Delete
          </button>
        </form>
      </template>
    </PageHeading>
    <Card>
      <div class="md:flex md:items-center md:justify-between">
        <div class="flex">
          <div
            class="flex items-center justify-center h-12 w-12 rounded-full bg-indigo-500 text-white mr-2 overflow-hidden"
          >
            <img :src="userModel.profile_photo_url"/>
          </div>
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              {{ userModel.name }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">User information</p>
          </div>
        </div>
      </div>
      <div class="mt-5 border-t border-gray-200">
        <DataDisplayContainer>
          <DataDisplayRow>
            <template #label>Full name</template>
            {{ userModel.name }}
          </DataDisplayRow>
          <DataDisplayRow>
            <template #label>Email address</template>
            <a :href="`mailto:${userModel.email}`" class="link">{{
                userModel.email
              }}</a>
          </DataDisplayRow>
          <DataDisplayRow>
            <template #label>Role</template>
            <span>{{ userModel.role }} </span>
          </DataDisplayRow>
          <DataDisplayRow>
            <template #label>School</template>
            <div v-if="userModel.schools.length > 0">
              <p v-for="(school,index) in userModel.schools">
                - {{ school.name }}
              </p>
            </div>
          </DataDisplayRow>
          <DataDisplayRow v-show="userModel.role == 'Customer'">
            <template #label>Company</template>
            <Link
              v-if="userModel.company != null"
              :href="route('companies.show', { company: userModel.company })"
              class="link"
            >{{ userModel.company.name }}
            </Link
            >
            <span v-else>Undefined</span>
          </DataDisplayRow>
          <DataDisplayRow>
            <template #label>Security</template>
            <button
              class="button button-secondary button-small mr-2 border-indigo-500 text-sm text-indigo-500"
              @click="resetPassword"
            >
              Send reset password email
            </button>
          </DataDisplayRow>
          <DataDisplayRow>
            <template #label>Created at</template>
            {{ userModel.created_at }}
          </DataDisplayRow>
          <DataDisplayRow>
            <template #label>Updated at</template>
            {{ userModel.updated_at }}
          </DataDisplayRow>
        </DataDisplayContainer>
      </div>
    </Card>
  </div>
</template>

<script>
import Layout from "@/Shared/Layout";
import Breadcrumbs from "@/Components/Breadcrumbs";
import Card from "@/Components/Card";
import PageHeading from "@/Components/PageHeading";
import DataDisplayContainer from "@/Components/DataDisplayContainer";
import DataDisplayRow from "@/Components/DataDisplayRow";
import ConfirmDialog from "@/Components/ConfirmDialog";
import {PencilIcon, TrashIcon} from "@heroicons/vue/solid";
import {useForm, Link} from "@inertiajs/inertia-vue3";
import PreLoad from "@/Components/PreLoad";

export default {
  layout: Layout,
  components: {
    ConfirmDialog,
    PreLoad,
    Card,
    PageHeading,
    Breadcrumbs,
    DataDisplayContainer,
    DataDisplayRow,
    PencilIcon,
    TrashIcon,
    Link
  },
  props: {
    userModel: Object,
    title: String,
    flash: Object,
    jetstream: Object,
    errorBags: Object,
    errors: Object,
  },
  data() {
    return {
      breadcrumbs: [
        // { name: 'Dashboard', href: '#' },
        {name: "Users", href: route("dashboard.users.index")},
        {name: this.userModel.name, href: "#"},
      ],
      form: useForm({
        user: this.userModel.id,
      }),
      resetPasswordForm: useForm({
        user: this.userModel.id,
      }),
      resetPinForm: useForm({
        user: this.userModel.id,
      }),
      isShowDeleteDialog: false,
      isShowEmailSend: false,
    };
  },
  methods: {
    deleteUser() {
      this.form.delete(route("dashboard.users.destroy", {user: this.form.user}));
    },
    async resetPassword() {
      this.isShowEmailSend = true;
      await this.resetPasswordForm.post(route("reset-password.sent"), {
        onSuccess: () => {
          this.isShowEmailSend = false;
        },
      });
    },
  },
};
</script>

<style>
</style>
