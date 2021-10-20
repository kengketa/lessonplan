<template>
  <Card>
    <Form formClass="space-y-8 divide-y divide-gray-200" :submitEvent="submit">
      <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
        <div class="space-y-6 sm:space-y-5">
          <div>
            <h3 class="text-lg leading-6 text-gray-900">User Information</h3>
          </div>
          <div class="space-y-6 sm:space-y-5">
            <TextInput
              v-model="form.name"
              id="name"
              :error="form.errors.name"
              label="Full Name"
            />
            <TextInput
              v-model="form.email"
              id="email"
              :error="form.errors.email"
              type="email"
              label="Email address"
            />
            <SelectInput
              label="Role"
              id="role"
              :error="form.errors.role"
              v-model="form.role"
            >
              <template #option>
                <option value="">Please select a role</option>
                <option
                  v-for="(option, key) in roles"
                  :key="option"
                  :value="option"
                >
                  {{ key }}
                </option>
              </template>
            </SelectInput>
          </div>
        </div>
      </div>
    </Form>
  </Card>
</template>


<script>
import { useForm } from "@inertiajs/inertia-vue3";
import Card from "@/Components/Card";
import TextInput from "@/Components/TextInput";
import SelectInput from "@/Components/SelectInput";
import SubmitButton from "@/Components/SubmitButton";
import SearchSelectInput from "@/Components/SearchSelectInput";
import Form from "@/Components/Form";
export default {
  name: "UserForm.vue",
  components: {
    SelectInput,
    Card,
    TextInput,
    SubmitButton,
    SearchSelectInput,
    Form,
  },
  props: {
    type: {
      default: "create",
      type: String,
    },
    companies: Object,
    roles: Object,
    user: Object,
    errors: Object,
  },
  data() {
    return {
      form: useForm({
        name: this.user.name,
        email: this.user.email,
        company_id: this.user.company_id,
        // password: null,
        // password_confirmation: null,
        role: null,
        contact: this.user.contact,
      }),
      isSubmit: false,
    };
  },
  mounted() {
    if (this.user.roles != null) {
      this.form.role = this.user.roles[0].id;
    }
  },
  methods: {
    submit() {
      if (this.form.role != 10) {
        this.form.company_id = null;
      }
      if (this.type === "create") {
        this.form.post(route("users.store"), {});
      } else {
        this.form.put(route("users.update", { user: this.user.id }), {});
      }
    },
  },
};
</script>

<style scoped>
</style>
