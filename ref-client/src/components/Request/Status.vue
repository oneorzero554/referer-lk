<template>
  <el-tag :type="type">
    {{name}}
  </el-tag>
</template>

<script>
export default {
  name: "Status",
  props: {
    id: {
      required: true,
      type: Number
    },
    name: {
      required: true,
      type: String
    }
  },
  computed: {
    type() {
      switch (this.id) {
        case 1: return 'warning';
        case 2: return 'danger';
        case 3: return 'success'
      }
    }
  }
}
</script>

<style scoped>

</style>