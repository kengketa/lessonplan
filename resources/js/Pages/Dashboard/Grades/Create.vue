<template>
    <div>
        <Breadcrumbs
            :breadcrumbs="breadcrumbs"
            :back="route('dashboard.grades.index')"
        />
        <PageHeading>
            Create Grade
        </PageHeading>

        <div>
            <GradeForm :grade="grade" type="create" />
        </div>
    </div>
</template>

<script>
    import Layout from '@/Shared/Layout';
    import GradeForm from '@/Components/Forms/GradeForm';
    import Breadcrumbs from '@/Components/Breadcrumbs';
    import PageHeading from '@/Components/PageHeading';

    export default {
        name: 'GradeCreate',
        components: {Breadcrumbs, PageHeading, GradeForm},
        layout: Layout,
        props: {
        grade: {
                type: Object,
                required: true
            },
            errors: Object,
        },
        data() {
            return {
                breadcrumbs: [
                    {name: 'Grades', href: route('dashboard.grades.index')},
                    {name: 'Create Grade', href: '#'},
                ],
            };
        },
        methods: {},
        mounted() {
        }
    };
</script>
