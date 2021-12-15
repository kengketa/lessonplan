<template>
  <div>
    <ConfirmDialog
      v-model="isShowDeleteDialog"
      title="Delete Product?"
      :body="`The Meeting: ${ meeting.name} and its related data will be deleted. This action cannot be undone.`"
      confirm-text="Delete Meeting"
      :confirm-event="deleteMeeting"
    />
    <Breadcrumbs
      :breadcrumbs="breadcrumbs"
      :back="route('dashboard.meetings.index')"
    />
    <PageHeading>
    <span class="hidden lg:inline">
      <span class="max-w-xs truncate">{{ meeting.name }}</span>
    </span>
      <template #actions>
        <Link
          :href="route('dashboard.meetings.edit', meeting.id)"
          class="button button-primary mr-2"
        >
          <PencilIcon class="h-5 w-5 mr-2" aria-hidden="true" />
          Edit
        </Link>
        <form class="inline-flex" @submit.prevent="isShowDeleteDialog = true">
          <button type="submit" class="button button-danger">
            <TrashIcon class="h-5 w-5 mr-2" aria-hidden="true" />
            Delete
          </button>
        </form>
      </template>
    </PageHeading>

    <Card>
      <div class="relative">
        <p class="absolute -top-4 right-0">{{ meeting.date }} {{ meeting.time }}</p>
        <p class="absolute top-0 right-0 text-sm">{{ meeting.school }} , {{ meeting.location }}</p>
      </div>
      <div class="mt-4">
        <p class="text-2xl font-semibold text-center">Meeting minutes</p>
        <p class="text-center text-xl">{{ meeting.title }}</p>
      </div>
      <div class="mt-4 grid grid-cols-1 md:lg:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2 gap-4">
        <div v-for="agenda in meeting.agendas" :key="agenda.id" class="border rounded-md py-2 px-4">
          <p class="text-lg font-semibold">{{ agenda.topic }}</p>
          <p class="text-sm" v-html="agenda.detail"></p>
        </div>
      </div>
      <div class="mt-4">
        <p class="font-semibold ">Attendee:</p>
        <div class="flex flex-wrap">
          <p v-for="(person,index) in meeting.attendee" :key="index"
             class="px-2 py-0.5 bg-yellow-100 rounded-md mr-1 mb-1 text-sm">
            {{ person }}
          </p>
        </div>
      </div>
    </Card>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumbs from '@/Components/Breadcrumbs';
import Card from '@/Components/Card';
import PageHeading from '@/Components/PageHeading';
import DataDisplayContainer from '@/Components/DataDisplayContainer';
import DataDisplayRow from '@/Components/DataDisplayRow';
import ConfirmDialog from '@/Components/ConfirmDialog';
import TableDisplayContainer from "@/Components/TableDisplayContainer";
import TableTh from "@/Components/TableTh";
import TableTd from "@/Components/TableTd";
import {Link} from '@inertiajs/inertia-vue3';

import {
  ExternalLinkIcon,
  EyeIcon,
  PencilIcon,
  TrashIcon,
  CheckCircleIcon,
  ExclamationCircleIcon
} from '@heroicons/vue/solid';
import {useForm} from '@inertiajs/inertia-vue3';
import Button from '@/Jetstream/Button';

export default {
  name: 'MeetingShow',
  components: {
    Card, PageHeading, Breadcrumbs, DataDisplayContainer,
    DataDisplayRow, PencilIcon, TrashIcon, ExternalLinkIcon, EyeIcon,
    ConfirmDialog, TableDisplayContainer, TableTh, TableTd, Link
  },
  layout: Layout,
  props: {
    meeting: Object,
    flash: Object,
    errorBags: Object,
    errors: Object,
  },
  data() {
    return {
      breadcrumbs: [
        {name: 'Meetings', href: route('dashboard.meetings.index')},
        {name: this.meeting.title, href: '#'},
      ],
      deleteForm: useForm({}),
      isShowDeleteDialog: false,
    };
  },
  computed: {},
  methods: {
    deleteMeeting() {
      this.deleteForm.delete(route('dashboard.meetings.destroy', {meeting: this.meeting.id}));
    },
  },
};
</script>

<style>
</style>
