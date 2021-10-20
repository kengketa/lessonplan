<template>
    <div>
        <ConfirmDialog
            v-model="isShowDeleteDialog"
            title="Delete Product?"
            :body="`The Grade: ${ grade.name} and its related data will be deleted. This action cannot be undone.`"
            confirm-text="Delete Grade"
            :confirm-event="deleteGrade"
        />
        <Breadcrumbs
            :breadcrumbs="breadcrumbs"
            :back="route('dashboard.grades.index')"
        />
        <PageHeading>
    <span class="hidden lg:inline">
      <span class="max-w-xs truncate">{{  grade.name  }}</span>
    </span>
            <template #actions>
                <Link
                    :href="route('dashboard.grades.edit', grade.id)"
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
                            Grade Information
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
{{grade.school_id }}
</DataDisplayRow>
<DataDisplayRow>
	<template #label>
		type
	</template>
{{grade.type }}
</DataDisplayRow>
<DataDisplayRow>
	<template #label>
		level
	</template>
{{grade.level }}
</DataDisplayRow>
<DataDisplayRow>
	<template #label>
		room_number
	</template>
{{grade.room_number }}
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
        name: 'GradeShow',
        components: {
            Card, PageHeading, Breadcrumbs, DataDisplayContainer,
            DataDisplayRow, PencilIcon, TrashIcon, ExternalLinkIcon, EyeIcon,
            ConfirmDialog, TableDisplayContainer, TableTh, TableTd,Link
        },
        layout: Layout,
        props: {
        grade: Object,
            flash: Object,
            errorBags: Object,
            errors: Object,
        },
        data() {
            return {
                breadcrumbs: [
                    {name: 'Grades', href: route('dashboard.grades.index')},
                    {name: this.grade.name, href: '#'},
                ],
                deleteForm: useForm({}),
                isShowDeleteDialog: false,
            };
        },
        computed: {

        },
        methods: {
            deleteGrade() {
                this.deleteForm.delete(route('dashboard.grades.destroy', { grade: this.grade.id}));
            },
        },
    };
</script>

<style>
</style>
