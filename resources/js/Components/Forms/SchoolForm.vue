<template>
    <Card>
        <Form
            form-class="space-y-8 divide-y divide-gray-200"
            :submit-event="submit"
        >
            <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                <div class="space-y-6 sm:space-y-5">
                    <div>
                        <h3 v-if="school.id == null" class="text-lg leading-6 text-gray-900">
                            Create new School
                        </h3>
                        <h3 v-else class="text-lg leading-6 text-gray-900">
                            Update School
                        </h3>
                    </div>
                    <div class="space-y-6 sm:space-y-5">
                        <TextInput
v-model="form.name"
:errors="form.errors.name"
label="Name"
placeholder="name"
/>
<TextInput
v-model="form.address"
:errors="form.errors.address"
label="Address"
placeholder="address"
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
        name: 'SchoolForm',
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
            school: Object,
            errors: Object,
        },
        data() {
            return {
                form: useForm({
                    id: this.school.id,
                    name: this.school.name,
address: this.school.address,

                }),
            };
        },
        mounted() {

        },
        methods: {
            submit() {
                if (this.type === 'create') {
                    this.form.post(route('dashboard.schools.store'));
                } else {
                    this.form.put(route('dashboard.schools.update', this.school.id));
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

