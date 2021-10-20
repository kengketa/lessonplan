<template>
  <div class="md:grid md:grid-cols-3">
    <div class="mt-5 md:mt-0 md:col-span-3">
      <div class="shadow sm:rounded-md sm:overflow-hidden">
        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
          <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-2">
              <label for="name" class="block text-sm font-medium text-gray-700">
                Name:
              </label>
              <div class="mt-1 flex rounded-md shadow-sm">
                <input
                  v-model="user.name"
                  required
                  minlength="5"
                  maxlength="50"
                  type="text"
                  name="name"
                  id="name"
                  class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                  placeholder="Peter Parker"
                />
              </div>
              <p v-if="errors.name != null" class="text-error">
                {{ errors.name }}
              </p>
            </div>
            <div class="col-span-3 sm:col-span-1">
              <label for="role" class="block text-sm font-medium text-gray-700"
                >Role</label
              >
              <select
                v-model="user.role"
                id="role"
                name="role"
                autocomplete="role"
                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              >
                <option v-for="(value, key) in roles" :key="key" :value="value">
                  {{ key }}
                </option>
              </select>
              <p v-if="errors.role != null" class="text-error">
                {{ errors.role }}
              </p>
            </div>
            <div class="col-span-3 sm:col-span-2">
              <label
                for="email"
                class="block text-sm font-medium text-gray-700"
              >
                Email:
              </label>
              <div class="mt-1 flex rounded-md shadow-sm">
                <input
                  required
                  v-model="user.email"
                  type="email"
                  name="email"
                  id="email"
                  class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                  placeholder="example@mail.com"
                />
              </div>
              <p v-if="errors.email != null" class="text-error">
                {{ errors.email }}
              </p>
            </div>
            <div class="col-span-3 sm:col-span-2">
              <label
                for="password"
                class="block text-sm font-medium text-gray-700"
              >
                Password:
              </label>
              <div class="mt-1 flex rounded-md shadow-sm">
                <input
                  v-model="user.password"
                  type="password"
                  name="password"
                  id="password"
                  class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                  placeholder="password"
                />
              </div>
              <p v-if="errors.password != null" class="text-error">
                {{ errors.password }}
              </p>
            </div>
            <div class="col-span-3 sm:col-span-2">
              <label
                for="password"
                class="block text-sm font-medium text-gray-700"
              >
                Password Confirm:
              </label>
              <div class="mt-1 flex rounded-md shadow-sm">
                <input
                  v-model="user.password_confirmation"
                  type="password"
                  name="password_confirmation"
                  id="password_confirmation"
                  class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                  placeholder="password confirm"
                />
              </div>
            </div>
            <div class="col-span-3 sm:col-span-2">
              <label
                for="contact"
                class="block text-sm font-medium text-gray-700"
              >
                Contact:
              </label>
              <div class="mt-1 flex rounded-md shadow-sm">
                <input
                  v-model="user.contact"
                  required
                  type="text"
                  name="contact"
                  id="contact"
                  class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                  placeholder="+66-111-2323"
                />
              </div>
              <p v-if="errors.contact != null" class="text-error">
                {{ errors.contact }}
              </p>
            </div>
          </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
          <button
            type="submit"
            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Save
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    roles: Object,
    user: Object,
    errors: Object,
  },
  data() {
    return {
      csrf: "",
    };
  },
  mounted() {
    if (this.user.role == null) {
      this.user.role = 0;
    }
  },
  methods: {},
};
</script>

<style>
.text-error {
  color: red;
}
</style>
