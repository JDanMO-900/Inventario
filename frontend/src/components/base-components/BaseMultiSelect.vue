<template>
  <div>
    <v-autocomplete
      v-bind="$attrs"
      class="mt-4"
      variant="outlined"
      hide-details
      multiple
      @input="$emit('update:modelValue', $event.target.value)"
    />

    <base-error :rules="rules" />
  </div>
</template>

<script>
import BaseError from "./BaseError.vue";

export default {
  components: { BaseError },
  props: {
    rules: {
      type: Object,
      required: true,
    },
    modelValue: {
      required: true,
      type: String,
    },
  },
};
</script>