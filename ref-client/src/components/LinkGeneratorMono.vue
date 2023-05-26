<template>
  <el-table ref="table" :data="tableData" style="width: 100%" size="default">
    <el-table-column label="Сайт" min-width="200">
      <template #default="{row}">
        <el-link type="primary" :href="'https://'+row.site.url" target="_blank">{{row.site.url}}</el-link>
      </template>
    </el-table-column>
    <el-table-column label="Провайдер" min-width="200">
      <template #default="{row}">
        <el-select
            v-model="row.provider"
            placeholder="Провайдер"
            size="default"
            value-key="id"
            :disabled="row.providersList.length === 1"
            :clearable="true"
            @change="(value) => handleChangeProvider(row, value)"
        >
          <el-option
              v-for="item in row.providersList"
              :key="item.id"
              :label="item.name"
              :value="item"
          />
        </el-select>
      </template>
    </el-table-column>
    <el-table-column label="Населенный пункт" min-width="200">
      <template #default="{$index, row}">
        <el-select
            :ref="`cities-select-${$index}`"
            placeholder="Населенный пункт"
            size="default"
            value-key="id"
            v-model="row.city"
            :remote-show-suffix="true"
            filterable
            :clearable="true"
            remote
            :remote-method="(query) => handleFilterCities(row, query)"
            @change="(value) => handleChangeCity(row, value)"
            @visible-change="(visible) => handleCitiesVisible(row, visible)"
            :loading="row.citiesList.loading"
        >
          <div
              v-infinite-scroll="() => loadMore(row, $index)"
              :infinite-scroll-disabled="row.citiesList.moreLoading || !row.citiesList.meta || (row.citiesList.meta.current_page === row.citiesList.meta.last_page)"
          >
            <div>
              <el-option
                  v-for="item in row.citiesList.data"
                  :key="item.id"
                  :label="item.name"
                  :value="item"
                  class="h-auto"
              >
                <div class="flex flex-col leading-none py-1.5">
                  <span>{{ item.name}}</span>
                  <span class="text-xs text-gray-400">{{ item.region_name }}</span>
                </div>

              </el-option>
            </div>

            <div class="flex w-full items-center justify-center p-1 text-zinc-500" v-if="row.citiesList.moreLoading">
              Загрузка
            </div>
          </div>
        </el-select>
      </template>
    </el-table-column>
    <el-table-column label="Категория" min-width="200">
      <template #default="{row}">
        <el-select
            v-model="row.category"
            placeholder="Категория"
            :clearable="true"
            value-key="id"
            size="default"
            :disabled="!row.site || !row.provider || !row.city || !row.isCategorizable"
            :loading="row.categoriesList.loading"
            @visible-change="(visible) => handleCategoriesVisible(row, visible)"
        >
          <el-option
              v-for="item in row.categoriesList.data"
              :key="item.id"
              :label="item.name"
              :value="item"
          />
        </el-select>
      </template>
    </el-table-column>
    <el-table-column label="Действия" min-width="150">
      <template #default="{$index, row}">
        <el-button size="small" type="primary" @click="generateLink($index, row)">
          Создать ссылку
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>

import {CitiesServices, CitiesGetRequestDto} from "../services/Api/CitiesServices.js";
import {GenerateRefererLinkService, GenerateRefererLinkDto} from "../services/GenerateRefererLinkService.js";
import {CategoriesServices, CategoriesGetRequestDto} from "../services/Api/CategoriesServices.js";
import CopyToClipboard from "../mixins/copyToClipboard.js";

export default {
  name: "LinkGeneratorMono",
  props: {
    sites: {
      type: Array,
      required: true
    }
  },
  mixins: [CopyToClipboard],
  computed: {
    tableData() {
      return this.sites.map(item => {

        let provider = null;
        let category = null;
        let city = null;

        const providers = item.providers.map(provider => {
          return {
            id: provider.id,
            name: provider.name,
            url: provider.url
          }
        });

        if (providers.length === 1) {
          provider = providers[0];
        }

        return {
          is_multi: item.is_multi,
          site: {
            id: item.id,
            url: item.url
          },
          providersList: providers,
          provider,
          city,
          citiesList: {
            loading: false,
            moreLoading: false,
            abortController: null,
            data: [],
            meta: null
          },
          category,
          categoriesList: {
            loading: false,
            data: []
          },
          isCategorizable: item.is_categorizable
        };
      });
    }
  },
  methods: {
    loadMore(row, index) {
      const ref = this.$refs[`cities-select-${index}`];

      const data = new CitiesGetRequestDto({
        query: ref.query,
        site_id: row.site?.id ?? null,
        provider_id: row.provider?.id ?? null,
        page: (row.citiesList.meta.current_page) + 1
      })

      if (row.citiesList.abortController) {
        row.citiesList.abortController.abort();
      }

      row.citiesList.abortController = new AbortController();

      row.citiesList.moreLoading = true

      CitiesServices.list(data, row.citiesList.abortController.signal)
          .then(({data}) => {
            row.citiesList.data = row.citiesList.data.concat(data.data.data)
            row.citiesList.meta = data.data.meta
          })
          .catch(err => {
            console.log(err.response)
          })
          .finally(() => {
            row.citiesList.moreLoading = false
            row.citiesList.abortController = null
          })

    },
    generateLink(index, row) {

      const data = new GenerateRefererLinkDto({
        is_multi: row.is_multi,
        site_url: row.site.url,
        city_url: row?.city?.url ?? null,
        provider_url: row?.provider?.url ?? null,
        category_url: row?.category?.url ?? null
      });
      this.copyToClipboard(GenerateRefererLinkService.generate(data))
    },
    handleCitiesVisible(row, visible) {
      if (visible) {
        this.getCities(row, null)
      } else {
        if (row.citiesList.abortController) {
          row.citiesList.abortController.abort()
        }

        row.citiesList.data = []
        row.citiesList.meta = null
      }
    },
    handleCategoriesVisible(row, visible) {
      if (visible && !row.categoriesList.data.length && !row.categoriesList.loading) {
        this.getCategories(row)
      }
    },
    handleFilterCities(row, query) {
      if (query) {
        this.getCities(row, query)
      }
    },
    getCities(row, query, page = 1) {
      const data = new CitiesGetRequestDto({
        query,
        site_id: row.site?.id ?? null,
        provider_id: row.provider?.id ?? null,
        page
      })

      if (row.citiesList.abortController) {
        row.citiesList.abortController.abort();
      }

      row.citiesList.abortController = new AbortController();

      row.citiesList.loading = true

      CitiesServices.list(data, row.citiesList.abortController.signal)
          .then(({data}) => {
            row.citiesList.data = data.data.data
            row.citiesList.meta = data.data.meta
          })
          .catch(err => {
            console.log(err.response)
          })
          .finally(() => {
            row.citiesList.loading = false
            row.citiesList.abortController = null
          })
    },
    getCategories(row) {
      const data = new CategoriesGetRequestDto({
        site_id: row.site?.id ?? null,
        provider_id: row.provider?.id ?? null,
        city_id: row.city?.id ?? null
      });

      row.categoriesList.loading = true

      CategoriesServices.list(data)
          .then(({data}) => {
            row.categoriesList.data = data.data
          })
          .catch(err => {
            console.log(err.response)
          })
          .finally(() => {
            row.categoriesList.loading = false
          })
    },
    handleChangeProvider(row) {
      row.citiesList.data = []
      row.city = null
    },
    handleChangeCity(row) {
      row.categoriesList.data = []
      row.category = null
    }
  }
}
</script>

<style scoped>

</style>