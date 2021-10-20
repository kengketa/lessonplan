<template>
    <Card>
        <Form
            form-class="space-y-8 divide-y divide-gray-200"
            :submit-event="submit"
        >
            <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                <div class="space-y-6 sm:space-y-5">
                    <div>
                        <h3 v-if="grade.id == null" class="text-lg leading-6 text-gray-900">
                            Create new Grade
                        </h3>
                        <h3 v-else class="text-lg leading-6 text-gray-900">
                            Update Grade
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
v-model="form.type"
:errors="form.errors.type"
label="Type"
placeholder="type"
/>
<TextInput
v-model="form.level"
:errors="form.errors.level"
label="Level"
placeholder="level"
/>
<TextInput
v-model="form.room_number"
:errors="form.errors.room_number"
label="Room_number"
placeholder="room_number"
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
        name: 'GradeForm',
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
            grade: Object,
            errors: Object,
        },
        data() {
            return {
                form: useForm({
                    id: this.grade.id,
                    school_id: this.grade.school_id,
type: this.grade.type,
level: this.grade.level,
room_number: this.grade.room_number,

                }),
            };
        },
        mounted() {

        },
        methods: {
            submit() {
                if (this.type === 'create') {
                    this.form.post(route('dashboard.grades.store'));
                } else {
                    this.form.put(route('dashboard.grades.update', this.grade.id));
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

