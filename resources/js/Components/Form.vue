<template>
  <form @submit.prevent="onSubmit" :class="formClass">
    <slot></slot>
    <div class="pt-5">
      <div class="flex justify-end">
        <SubmitButton v-model="isSubmitting" text="Save"></SubmitButton>
      </div>
    </div>
  </form>
</template>

<script>
import SubmitButton from "./SubmitButton";

export default {
  components: {SubmitButton},
  props: {
    submitEvent: Function,
    formClass: String,
  },
  data() {
    return {isSubmitting: false, updateState: 0, currentState: 0};
  },
  async updated() {
    if (this.isSubmitting && this.hasFlash) {
      this.isSubmitting = false;
      this.clearFlash();
    }
  },
  computed: {
    hasFlash() {
      return (
        this.$page.props.flash.success != null ||
        this.$page.props.flash.errror != null ||
        Object.keys(this.$page.props.errors).length > 0
      );
    },
  },
  methods: {
    onSubmit() {
      if (!this.isSubmitting) {
        this.isSubmitting = true;
        this.submitEvent();
      }
    },
    clearFlash() {
      this.$page.props.flash.success = null;
      this.$page.props.flash.errror = null;
      // this.$page.props.errors = {};
    },
  },
};
</script>

<style>
</style>
