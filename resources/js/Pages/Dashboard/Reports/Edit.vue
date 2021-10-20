<template>
    <div>
        <Breadcrumbs
            :breadcrumbs="breadcrumbs"
            :back="route('dashboard.reports.index')"
        />
        <PageHeading>
            Edit Report
        </PageHeading>
        <div>
            <ReportForm :report="report" type="edit" />
        </div>
    </div>
</template>

<script>
    import Layout from '@/Shared/Layout';
    import ReportForm from "@/Components/Forms/ReportForm";
    import Breadcrumbs from '@/Components/Breadcrumbs';
    import PageHeading from '@/Components/PageHeading';
    export default {
        name: 'ReportEdit',
        components: {Breadcrumbs, PageHeading, ReportForm},
        layout: Layout,
        props: {
            report: {
                type: Object,
                required: true
            },
            errors: Object,
        },
        data() {
            return {
                breadcrumbs: [
                    {name: 'Reports', href: route('dashboard.reports.index')},
                    {name: this.report.name, href: route('dashboard.reports.show', this.report.id)},
                    {name: 'Edit', href: '#'},
                ],
            };
        },
        methods: {},
        mounted() {
        }
    };
</script>
