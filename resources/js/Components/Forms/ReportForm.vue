<template>
    <Card>
        <Form
            form-class="space-y-8 divide-y divide-gray-200"
            :submit-event="submit"
        >
            <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                <div class="space-y-6 sm:space-y-5">
                    <div>
                        <h3 v-if="report.id == null" class="text-lg leading-6 text-gray-900">
                            Create new Report
                        </h3>
                        <h3 v-else class="text-lg leading-6 text-gray-900">
                            Update Report
                        </h3>
                    </div>
                    <div class="space-y-6 sm:space-y-5">
                        <TextInput
v-model="form.grade_id"
:errors="form.errors.grade_id"
label="Grade_id"
placeholder="grade_id"
/>
<TextInput
v-model="form.week_number"
:errors="form.errors.week_number"
label="Week_number"
placeholder="week_number"
/>
<TextInput
v-model="form.lesson_number"
:errors="form.errors.lesson_number"
label="Lesson_number"
placeholder="lesson_number"
/>
<TextInput
v-model="form.date"
:errors="form.errors.date"
label="Date"
placeholder="date"
/>
<TextInput
v-model="form.topic"
:errors="form.errors.topic"
label="Topic"
placeholder="topic"
/>
<TextInput
v-model="form.subject"
:errors="form.errors.subject"
label="Subject"
placeholder="subject"
/>
<TextInput
v-model="form.outcome"
:errors="form.errors.outcome"
label="Outcome"
placeholder="outcome"
/>
<TextInput
v-model="form.outstanding_student"
:errors="form.errors.outstanding_student"
label="Outstanding_student"
placeholder="outstanding_student"
/>
<TextInput
v-model="form.need_improvement_student"
:errors="form.errors.need_improvement_student"
label="Need_improvement_student"
placeholder="need_improvement_student"
/>
<TextInput
v-model="form.creator"
:errors="form.errors.creator"
label="Creator"
placeholder="creator"
/>
<TextInput
v-model="form.approver"
:errors="form.errors.approver"
label="Approver"
placeholder="approver"
/>

                    </div>
                </div>
            </div>
        </Form>
    </Card>
</template>


<script>
    import {useForm} from '@inertiajs/inertia-vue3';
    import Card from '@/Components/Card';
    import TextInput from '@/Components/TextInput';
    import TextareaInput from '@/Components/TextareaInput';
    import {PlusSmIcon, TrashIcon} from '@heroicons/vue/solid';
    import Form from '@/Components/Form';
    export default {
        name: 'ReportForm',
        components: {
            Card,
            TextInput,
            TextareaInput,
            PlusSmIcon,
            TrashIcon,
            Form,
        },
        props: {
            type: {
                default: 'create',
                type: String,
            },
            report: Object,
            errors: Object,
        },
        data() {
            return {
                form: useForm({
                    id: this.report.id,
                    grade_id: this.report.grade_id,
week_number: this.report.week_number,
lesson_number: this.report.lesson_number,
date: this.report.date,
topic: this.report.topic,
subject: this.report.subject,
outcome: this.report.outcome,
outstanding_student: this.report.outstanding_student,
need_improvement_student: this.report.need_improvement_student,
creator: this.report.creator,
approver: this.report.approver,

                }),
            };
        },
        mounted() {

        },
        methods: {
            submit() {
                if (this.type === 'create') {
                    this.form.post(route('dashboard.reports.store'));
                } else {
                    this.form.put(route('dashboard.reports.update', this.report.id));
                }
            },
            makeid(length) {
                let result = '';
                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                const charactersLength = characters.length;
                for (let i = 0; i < length; i++) {
                    result += characters.charAt(
                        Math.floor(Math.random() * charactersLength)
                    );
                }
                return result;
            },
        },
    };
</script>

<style scoped>
</style>

