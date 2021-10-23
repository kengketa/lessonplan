<template>
  <ConfirmDialog
    v-if="needConfirmText != null"
    v-model="isShowConfirmDialog"
    title="Are you sure?"
    :body="needConfirmText"
    confirm-text="Confirm"
    theme-color="yellow"
    :confirm-event="onSubmit"
  />
  <form @submit.prevent="onSubmit" :class="formClass">
    <slot></slot>
    <div class="pt-5">
      <div class="flex justify-end">
        <button
          type="button"
          class="ml-3 button button-primary"
          @click="isShowConfirmDialog=true"
          v-if="needConfirmText != null"
          :disabled="disabled">
          Save
        </button>
        <SubmitButton
          v-if="needConfirmText == null"
          v-model="isSubmitting"
          text="Save"
          :disabled="disabled">
        </SubmitButton>
      </div>
    </div>
  </form>
</template>

<script>
import SubmitButton from "./SubmitButton";
import ConfirmDialog from "./ConfirmDialog";

export default {
  components: {ConfirmDialog, SubmitButton},
  props: {
    submitEvent: Function,
    formClass: String,
    disabled: {
      type: Boolean,
      default: false
    },
    needConfirmText: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      isSubmitting: false,
      updateState: 0,
      currentState: 0,
      isShowConfirmDialog: false
    };
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
      // this.$page.props.flash.success = null;
      // this.$page.props.flash.errror = null;
      // this.$page.props.errors = {};
    },
  },
};
</script>

<style>
</style>
