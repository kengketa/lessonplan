<template>
    <div>
        <Breadcrumbs
            :breadcrumbs="breadcrumbs"
            :back="route('dashboard.meetings.index')"
        />
        <PageHeading>
            Create Meeting
        </PageHeading>

        <div>
            <MeetingForm :meeting="meeting" type="create" />
        </div>
    </div>
</template>

<script>
    import Layout from '@/Shared/Layout';
    import MeetingForm from '@/Components/Forms/MeetingForm';
    import Breadcrumbs from '@/Components/Breadcrumbs';
    import PageHeading from '@/Components/PageHeading';

    export default {
        name: 'MeetingCreate',
        components: {Breadcrumbs, PageHeading, MeetingForm},
        layout: Layout,
        props: {
        meeting: {
                type: Object,
                required: true
            },
            errors: Object,
        },
        data() {
            return {
                breadcrumbs: [
                    {name: 'Meetings', href: route('dashboard.meetings.index')},
                    {name: 'Create Meeting', href: '#'},
                ],
            };
        },
        methods: {},
        mounted() {
        }
    };
</script>
