<template>
  <div class="flex items-center bg-gray-50 justify-center h-screen">
    <div class="bg-white shadow sm:rounded-lg mx-2">
      <FlashMessage></FlashMessage>
      <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Setup your password
        </h3>
        <div class="mt-2 max-w-xl text-sm text-gray-500">
          <p>Change the password you want associated with your account.</p>
        </div>
        <Form :submitEvent="savePassword">
          <TextInput
            class="mb-1"
            v-model="resetForm.password"
            id="password"
            :error="resetForm.errors.password"
            type="password"
            label="Password"
          />
          <TextInput
            class="mb-1"
            v-model="resetForm.password_confirmation"
            id="password_confirmation"
            :error="resetForm.errors.password_confirmation"
            type="password"
            label="Password Confirmation"
          />
        </Form>
      </div>
    </div>
  </div>
</template>

<script>
import TextInput from "@/Components/TextInput";
import FlashMessage from "@/Components/FlashMessage";
import Form from "@/Components/Form";
import { useForm } from "@inertiajs/inertia-vue3";
import { CheckCircleIcon } from "@heroicons/vue/solid";

export default {
  components: { TextInput, FlashMessage, CheckCircleIcon, Form },
  props: {
    resetModel: Object,
  },
  data() {
    return {
      resetForm: useForm({
        password: "",
        password_confirmation: "",
        token: this.resetModel.token,
        user_id: this.resetModel.user_id,
        id: this.resetModel.id,
      }),
    };
  },
  methods: {
    savePassword() {
      this.resetForm.post(route("user-setup.save"));
    },
  },
};
</script>
