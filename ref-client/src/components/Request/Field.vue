<template>
  <div class="bg-slate-100 rounded-md flex items-end h-full px-1.5 p-2">
    <el-icon :size="25" class="text-gray-400 mr-2">
      <slot name="icon"></slot>
    </el-icon>
    <div class="text-sm">
      <Nullable :value="value"/>
    </div>
  </div>
</template>

<script>
export default {
  name: "Field",
  props: {
    value: {
      required: true
    }
  }
}
</script>

<style scoped>

</style>