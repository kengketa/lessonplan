<template>
    <div>
        <Breadcrumbs
            :breadcrumbs="breadcrumbs"
            :back="route('dashboard.grades.index')"
        />
        <PageHeading>
            Edit Grade
        </PageHeading>
        <div>
            <GradeForm :grade="grade" type="edit" />
        </div>
    </div>
</template>

<script>
    import Layout from '@/Shared/Layout';
    import GradeForm from "@/Components/Forms/GradeForm";
    import Breadcrumbs from '@/Components/Breadcrumbs';
    import PageHeading from '@/Components/PageHeading';
    export default {
        name: 'GradeEdit',
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
                    {name: this.grade.name, href: route('dashboard.grades.show', this.grade.id)},
                    {name: 'Edit', href: '#'},
                ],
            };
        },
        methods: {},
        mounted() {
        }
    };
</script>
