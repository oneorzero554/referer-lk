<template>
  <span v-if="value">{{ value }}</span>
  <span v-else class="text-gray-500">Не задано</span>
</template>

<script>
export default {
  name: "Nullable",
  props: {
    value: {
      required: true
    }
  }
}
</script>

<style scoped>

</style>