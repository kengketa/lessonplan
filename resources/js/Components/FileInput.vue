<template>
    <div
            :class="
      isShowLine
        ? 'sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5'
        : ''
    "
    >
        <label
                v-if="label"
                :for="id"
                class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
        >{{ label }}</label
        >
        <div class="mt-1 sm:mt-0 sm:col-span-2">
            <div class="relative max-w-lg flex items-center">
                <button type="button"
                        @click="$refs.input.click()"
                        class="button button-primary bg-white text-black border border-gray-300 hover:bg-gray-100 focus:outline-none">
                    {{ btn_label }}
                </button>
                <label class="ml-4 truncate">{{ file_name }}</label>
                <input
                        type="file"
                        :id="id"
                        v-bind="$attrs"
                        ref="input"
                        :value="modelValue"
                        :accept="accept"
                        class="hidden"
                        v-bind:aria-invalid="error ? 'true' : null"
                        v-bind:aria-describedby="error ? `${id}-error` : null"
                        @change="fileChange"
                />
                <div
                        v-if="error"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                >
                    <ExclamationCircleIcon
                            class="h-5 w-5 text-red-500"
                            aria-hidden="true"
                    />
                </div>
            </div>
            <p v-if="error" class="mt-2 text-sm text-red-600" :id="`${id}-error`">
                {{ error }}
            </p>
        </div>
    </div>
</template>

<script>
    import {ExclamationCircleIcon} from "@heroicons/vue/solid/esm";
    import Label from "@/Jetstream/Label";

    const makeid = (length) => {
        let result = "";
        const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        const charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    };

    export default {
        inheritAttrs: false,
        props: {
            id: {
                type: String,
                default() {
                    return `text-input-${makeid(10)}`;
                    // return `text-input-123`
                },
            },
            modelValue: String,
            label: String,
            error: String,
            isShowLine: {
                type: Boolean,
                default: true,
            },
            accept: String,
        },
        components: {
            Label,
            ExclamationCircleIcon,
        },
        data: function() {
            return {
                btn_label: 'Select File',
                file_name: '',
            }
        },
        methods: {
            fileChange (event) {
                if(event.target.files[0]){
                    this.$emit('update', event.target.files[0]);
                    this.btn_label = 'Change';
                    this.file_name = event.target.files[0].name;
                }
            }
        }
    }
</script>

<style scoped>
</style>