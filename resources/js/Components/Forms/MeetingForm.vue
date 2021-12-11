<template>
    <Card>
        <Form
            form-class="space-y-8 divide-y divide-gray-200"
            :submit-event="submit"
        >
            <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                <div class="space-y-6 sm:space-y-5">
                    <div>
                        <h3 v-if="meeting.id == null" class="text-lg leading-6 text-gray-900">
                            Create new Meeting
                        </h3>
                        <h3 v-else class="text-lg leading-6 text-gray-900">
                            Update Meeting
                        </h3>
                    </div>
                    <div class="space-y-6 sm:space-y-5">
                        <TextInput
v-model="form.school_id"
:errors="form.errors.school_id"
label="School_id"
placeholder="school_id"
/>
<TextInput
v-model="form.title"
:errors="form.errors.title"
label="Title"
placeholder="title"
/>
<TextInput
v-model="form.date"
:errors="form.errors.date"
label="Date"
placeholder="date"
/>
<TextInput
v-model="form.status"
:errors="form.errors.status"
label="Status"
placeholder="status"
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
        name: 'MeetingForm',
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
            meeting: Object,
            errors: Object,
        },
        data() {
            return {
                form: useForm({
                    id: this.meeting.id,
                    school_id: this.meeting.school_id,
title: this.meeting.title,
date: this.meeting.date,
status: this.meeting.status,

                }),
            };
        },
        mounted() {

        },
        methods: {
            submit() {
                if (this.type === 'create') {
                    this.form.post(route('dashboard.meetings.store'));
                } else {
                    this.form.put(route('dashboard.meetings.update', this.meeting.id));
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

