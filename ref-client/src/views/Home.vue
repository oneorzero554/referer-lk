<template>
  <div class="py-3 px-2 bg-zinc-100 rounded-xl xl:py-4 xl:px-3 mb-3">
    <PageHeader>
      <template v-slot:breadcrumbs>
        <el-breadcrumb-item :to="{name: 'home'}">Домашняя</el-breadcrumb-item>
      </template>
      <template v-slot:page_title>Домашняя</template>
    </PageHeader>
  </div>
</template>

<script>
import PageHeader from "../components/PageHeader.vue";

export default {
  name: "Home",
  components: {PageHeader},
}
</script>

<style>

</style>