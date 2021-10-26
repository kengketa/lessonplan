<template>
    <div>
        <Breadcrumbs
            :breadcrumbs="breadcrumbs"
            :back="route('dashboard.schools.index')"
        />
        <PageHeading>
            Edit School
        </PageHeading>
        <div>
            <SchoolForm :school="school" type="edit" />
        </div>
    </div>
</template>

<script>
    import Layout from '@/Shared/Layout';
    import SchoolForm from "@/Components/Forms/SchoolForm";
    import Breadcrumbs from '@/Components/Breadcrumbs';
    import PageHeading from '@/Components/PageHeading';
    export default {
        name: 'SchoolEdit',
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
                    {name: this.school.name, href: route('dashboard.schools.show', this.school.id)},
                    {name: 'Edit', href: '#'},
                ],
            };
        },
        methods: {},
        mounted() {
        }
    };
</script>
