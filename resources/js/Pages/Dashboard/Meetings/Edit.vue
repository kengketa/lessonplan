<template>
    <div>
        <Breadcrumbs
            :breadcrumbs="breadcrumbs"
            :back="route('dashboard.meetings.index')"
        />
        <PageHeading>
            Edit Meeting
        </PageHeading>
        <div>
            <MeetingForm :meeting="meeting" type="edit" />
        </div>
    </div>
</template>

<script>
    import Layout from '@/Shared/Layout';
    import MeetingForm from "@/Components/Forms/MeetingForm";
    import Breadcrumbs from '@/Components/Breadcrumbs';
    import PageHeading from '@/Components/PageHeading';
    export default {
        name: 'MeetingEdit',
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
                    {name: this.meeting.name, href: route('dashboard.meetings.show', this.meeting.id)},
                    {name: 'Edit', href: '#'},
                ],
            };
        },
        methods: {},
        mounted() {
        }
    };
</script>
