<template>
  <div class="py-3 px-2 bg-zinc-100 rounded-xl mb-3 xl:py-4 xl:px-3">
    <PageHeader>
      <template v-slot:breadcrumbs>
        <el-breadcrumb-item :to="{name: 'links'}">Реферальные ссылки</el-breadcrumb-item>
      </template>
      <template v-slot:page_title>Реферальные ссылки</template>
    </PageHeader>
  </div>

  <div class="py-3 px-2 bg-zinc-100 rounded-xl xl:py-4 xl:px-3 grow space-y-5">
    <div class="p-4 bg-white rounded-xl">
      <LinkGeneratorMulti :sites="multi" v-loading="loading"/>
    </div>
    <div class="p-4 bg-white rounded-xl">
      <LinkGeneratorMono :sites="mono" v-loading="loading"/>
    </div>
  </div>
</template>

<script>
import PageHeader from "../components/PageHeader.vue";
import LinkGeneratorMono from "../components/LinkGeneratorMono.vue";
import LinkGeneratorMulti from "../components/LinkGeneratorMulti.vue";
import SitesService from "../services/Api/SitesService.js";

export default {
  name: "Links",
  components: {PageHeader, LinkGeneratorMono, LinkGeneratorMulti},

  mounted() {
    this.fetch()
  },
  data() {
    return {
      loading: false,
      sites: []
    }
  },
  computed: {
    mono() {
      if (this.sites.length) {
        return this.sites.filter(item => !item.is_multi)
      }
      return [];
    },
    multi() {
      if (this.sites.length) {
        return this.sites.filter(item => item.is_multi)
      }
      return [];
    }
  },
  methods: {
    fetch() {
      this.loading = true

      SitesService.list()
          .then(({data}) => {
            this.sites = data.data.map(item => {
              item.is_multi = item.providers.length > 1
              return item
            });
          })
          .catch(err => {
            console.log(err.response)
          })
          .finally(() => {
            this.loading = false
          })
    }
  }
}
</script>

<style>

</style>