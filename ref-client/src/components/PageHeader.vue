<template>
  <el-page-header title="Назад">
    <template #breadcrumb>
      <el-breadcrumb separator="/">
        <slot name="breadcrumbs"></slot>
      </el-breadcrumb>
    </template>
    <template #content>
        <span class="text-large font-600 mr-3">
          <slot name="page_title"></slot>
        </span>
    </template>
  </el-page-header>
</template>

<script>
export default {
  name: "PageHeader",
}
</script>

<style scoped>

</style>