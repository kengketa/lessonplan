<template>
    <div>
        <Breadcrumbs
            :breadcrumbs="breadcrumbs"
            :back="route('dashboard.schools.index')"
        />
        <PageHeading>
            Create School
        </PageHeading>

        <div>
            <SchoolForm :school="school" type="create" />
        </div>
    </div>
</template>

<script>
    import Layout from '@/Shared/Layout';
    import SchoolForm from '@/Components/Forms/SchoolForm';
    import Breadcrumbs from '@/Components/Breadcrumbs';
    import PageHeading from '@/Components/PageHeading';

    export default {
        name: 'SchoolCreate',
        components: {Breadcrumbs, PageHeading, SchoolForm},
        layout: Layout,
        props: {
        school: {
                type: Object,
                required: true
            },
            errors: Object,
        },
        data() {
            return {
                breadcrumbs: [
                    {name: 'Schools', href: route('dashboard.schools.index')},
                    {name: 'Create School', href: '#'},
                ],
            };
        },
        methods: {},
        mounted() {
        }
    };
</script>
