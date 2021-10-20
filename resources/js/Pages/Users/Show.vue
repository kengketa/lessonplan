<template>
  <div>
    <PreLoad
      v-model="isShowEmailSend"
      :loadingText="`mailing to ${userModel.email}`"
    ></PreLoad>
    <ConfirmDialog
      title="Delete user?"
      :body="`User ${userModel.name} and its related data will be deleted. This action cannot be undone.`"
      confirmText="Delete User"
      v-model="isShowDeleteDialog"
      :confirmEvent="deleteUser"
    ></ConfirmDialog>
    <Breadcrumbs :breadcrumbs="breadcrumbs" :back="route('dashboard.users.index')" />
    <PageHeading>
      <span class="hidden lg:inline">User information:</span> {{ userModel.name }}
      <template #actions>
        <inertia-link
          :href="route('dashboard.users.edit', userModel.id)"
          class="button button-primary mr-2"
        >
          <PencilIcon class="h-5 w-5 mr-2" aria-hidden="true" />
          Edit</inertia-link
        >
        <form @submit.prevent="isShowDeleteDialog = true" class="inline-flex">
          <button type="submit" class="button button-danger">
            <TrashIcon class="h-5 w-5 mr-2" aria-hidden="true" />
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
            <img :src="userModel.profile_photo_url" />
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
          <DataDisplayRow v-show="userModel.role == 'Customer'">
            <template #label>Company</template>
            <inertia-link
              v-if="userModel.company != null"
              class="link"
              :href="route('companies.show', { company: userModel.company })"
            >{{ userModel.company.name }}</inertia-link
            >
            <span v-else>Undefined</span>
          </DataDisplayRow>
          <DataDisplayRow>
            <template #label>Security</template>
            <button
              @click="resetPassword"
              class="button button-secondary button-small mr-2 border-indigo-500 text-sm text-indigo-500"
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
import { PencilIcon, TrashIcon } from "@heroicons/vue/solid";
import { useForm } from "@inertiajs/inertia-vue3";
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
        { name: "Users", href: route("dashboard.users.index") },
        { name: this.userModel.name, href: "#" },
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
      this.form.delete(route("dashboard.users.destroy", { user: this.form.user }));
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
