<template>
    <div>
        <ConfirmDialog
            v-model="isShowDeleteDialog"
            title="Delete Product?"
            :body="`The Report: ${ report.name} and its related data will be deleted. This action cannot be undone.`"
            confirm-text="Delete Report"
            :confirm-event="deleteReport"
        />
        <Breadcrumbs
            :breadcrumbs="breadcrumbs"
            :back="route('dashboard.reports.index')"
        />
        <PageHeading>
    <span class="hidden lg:inline">
      <span class="max-w-xs truncate">{{  report.name  }}</span>
    </span>
            <template #actions>
                <Link
                    :href="route('dashboard.reports.edit', report.id)"
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
                            Report Information
                        </h3>
                    </div>
                </div>
            </div>
            <div class="mt-5 border-t border-gray-200">
                <DataDisplayContainer>
                    <DataDisplayRow>
	<template #label>
		grade_id
	</template>
{{report.grade_id }}
</DataDisplayRow>
<DataDisplayRow>
	<template #label>
		week_number
	</template>
{{report.week_number }}
</DataDisplayRow>
<DataDisplayRow>
	<template #label>
		lesson_number
	</template>
{{report.lesson_number }}
</DataDisplayRow>
<DataDisplayRow>
	<template #label>
		date
	</template>
{{report.date }}
</DataDisplayRow>
<DataDisplayRow>
	<template #label>
		topic
	</template>
{{report.topic }}
</DataDisplayRow>
<DataDisplayRow>
	<template #label>
		subject
	</template>
{{report.subject }}
</DataDisplayRow>
<DataDisplayRow>
	<template #label>
		outcome
	</template>
{{report.outcome }}
</DataDisplayRow>
<DataDisplayRow>
	<template #label>
		outstanding_student
	</template>
{{report.outstanding_student }}
</DataDisplayRow>
<DataDisplayRow>
	<template #label>
		need_improvement_student
	</template>
{{report.need_improvement_student }}
</DataDisplayRow>
<DataDisplayRow>
	<template #label>
		creator
	</template>
{{report.creator }}
</DataDisplayRow>
<DataDisplayRow>
	<template #label>
		approver
	</template>
{{report.approver }}
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
        name: 'ReportShow',
        components: {
            Card, PageHeading, Breadcrumbs, DataDisplayContainer,
            DataDisplayRow, PencilIcon, TrashIcon, ExternalLinkIcon, EyeIcon,
            ConfirmDialog, TableDisplayContainer, TableTh, TableTd,Link
        },
        layout: Layout,
        props: {
        report: Object,
            flash: Object,
            errorBags: Object,
            errors: Object,
        },
        data() {
            return {
                breadcrumbs: [
                    {name: 'Reports', href: route('dashboard.reports.index')},
                    {name: this.report.name, href: '#'},
                ],
                deleteForm: useForm({}),
                isShowDeleteDialog: false,
            };
        },
        computed: {

        },
        methods: {
            deleteReport() {
                this.deleteForm.delete(route('dashboard.reports.destroy', { report: this.report.id}));
            },
        },
    };
</script>

<style>
</style>
