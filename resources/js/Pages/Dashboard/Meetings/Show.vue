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
      <span class="max-w-xs truncate">{{  meeting.name  }}</span>
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
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Meeting Information
                        </h3>
                    </div>
                </div>
            </div>
            <div class="mt-5 border-t border-gray-200">
                <DataDisplayContainer>
                    <DataDisplayRow>
	<template #label>
		school_id
	</template>
{{meeting.school_id }}
</DataDisplayRow>
<DataDisplayRow>
	<template #label>
		title
	</template>
{{meeting.title }}
</DataDisplayRow>
<DataDisplayRow>
	<template #label>
		date
	</template>
{{meeting.date }}
</DataDisplayRow>
<DataDisplayRow>
	<template #label>
		status
	</template>
{{meeting.status }}
</DataDisplayRow>

                </DataDisplayContainer>
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
            ConfirmDialog, TableDisplayContainer, TableTh, TableTd,Link
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
                    {name: this.meeting.name, href: '#'},
                ],
                deleteForm: useForm({}),
                isShowDeleteDialog: false,
            };
        },
        computed: {

        },
        methods: {
            deleteMeeting() {
                this.deleteForm.delete(route('dashboard.meetings.destroy', { meeting: this.meeting.id}));
            },
        },
    };
</script>

<style>
</style>
