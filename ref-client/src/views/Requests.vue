<template>
  <div class="flex flex-col grow">
    <div class="py-3 px-2 bg-zinc-100 rounded-xl mb-3 xl:py-4 xl:px-3">
      <PageHeader>
        <template v-slot:breadcrumbs>
          <el-breadcrumb-item :to="{name: 'requests'}">Заявки</el-breadcrumb-item>
        </template>
        <template v-slot:page_title>Заявки</template>
      </PageHeader>
    </div>
    <div class="py-3 px-2 bg-zinc-100 rounded-xl xl:py-4 xl:px-3 grow">
      <el-button type="primary" size="default" class="mb-5" @click="modalVisible = true">
        <span class="mr-1">Новая заявка</span><el-icon :size="25"><i-mdi-plus/></el-icon>
      </el-button>
      <div class="px-4 py-6 bg-white rounded-xl">
        <div class="text-xl font-medium mb-5">Список ваших заявок</div>
        <el-tabs
            type="card"
        >
          <el-tab-pane label="Заявка">
            <template v-slot:label>
              <el-badge :hidden="!requestFilterCount" :value="requestFilterCount">
                Заявка
              </el-badge>
            </template>
            <div class="flex flex-wrap -m-2">
              <div class="p-2 w-full lg:w-1/4">
                <el-input v-model="query.id" size="default" clearable placeholder="#ID" :disabled="requests.loading"/>
              </div>
              <div class="p-2 w-full lg:w-1/4">
                <el-select
                    v-model="query.statuses"
                    placeholder="Статус"
                    size="default"
                    clearable
                    multiple
                    class="w-full"
                    :disabled="requests.loading"
                >
                  <el-option
                      v-for="(opt_name, opt_value) in STATUS_NAME"
                      :key="opt_value"
                      :label="opt_name"
                      :value="opt_value"
                  />
                </el-select>
              </div>
              <div class="p-2 w-full lg:w-1/4">
                <el-date-picker
                    v-model="query.created_from"
                    type="datetime"
                    size="default"
                    placeholder="Создана с"
                    format="DD.MM.YYYY HH:mm:ss"
                    value-format="YYYY-MM-DD HH:mm:ss"
                    class="w-full"
                    clearable
                    :disabled="requests.loading"
                />
              </div>
              <div class="p-2 w-full lg:w-1/4">
                <el-date-picker
                    v-model="query.created_to"
                    type="datetime"
                    size="default"
                    placeholder="Создана по"
                    format="DD.MM.YYYY HH:mm:ss"
                    value-format="YYYY-MM-DD HH:mm:ss"
                    class="w-full"
                    clearable
                    :disabled="requests.loading"
                />
              </div>
              <div class="p-2 w-full lg:w-1/4">
                <RemoteSelect
                    :transport="transportCrmProviders"
                    :clearable="true"
                    :multiple="true"
                    size="default"
                    placeholder="Провайдер"
                    @change="selectProviders"
                    ref="providerRemoteSelect"
                    :disabled="requests.loading"
                />
              </div>
            </div>
          </el-tab-pane>
          <el-tab-pane label="Клиент">
            <template v-slot:label>
              <el-badge :hidden="!clientFilterCount" :value="clientFilterCount">
                Клиент
              </el-badge>
            </template>
            <div class="flex flex-wrap -m-2">
              <div class="p-2 w-full lg:w-1/4">
                <el-input v-model="query.full_name" size="default" clearable placeholder="ФИО"
                          :disabled="requests.loading"/>
              </div>
              <div class="p-2 w-full lg:w-1/4">
                <el-input v-model="query.city" size="default" clearable placeholder="Город"
                          :disabled="requests.loading"/>
              </div>
            </div>
          </el-tab-pane>
          <el-tab-pane label="Комментарий">
            <template v-slot:label>
              <el-badge :hidden="!commentFilterCount" :value="commentFilterCount">
                Комментарий
              </el-badge>
            </template>
            <div class="flex flex-wrap -m-2">
              <div class="p-2 w-full lg:w-full">
                <el-input v-model="query.ref_comment" size="default" clearable placeholder="Комментарий"
                          :disabled="requests.loading"/>
              </div>
            </div>
          </el-tab-pane>
        </el-tabs>
        <div class="mt-4">
          <el-button @click="fetch()" :loading="requests.loading" :disabled="requests.loading" size="default"
                     type="primary">Применить
          </el-button>
          <el-button @click="resetQuery" :loading="requests.loading" :disabled="requests.loading" size="default">
            Сбросить
          </el-button>
        </div>
      </div>
      <div class="mt-10 rounded-xl" v-loading="requests.loading">
        <div v-if="requests.data.length">
          <el-pagination
              v-if="requests.pagination"
              :pager-count="5"
              :page-size="requests.pagination.per_page"
              :current-page="requests.pagination.current_page"
              small
              background
              layout="prev, pager, next"
              :total="requests.pagination.total"
              @current-change="fetch"
              class="mb-5"
          />
          <Request v-for="(request, index) in requestsData" :request="request" :index="index" :key="request.id" @comment-change="commentChange"/>
          <el-pagination
              v-if="requests.pagination"
              :pager-count="5"
              :page-size="requests.pagination.per_page"
              :current-page="requests.pagination.current_page"
              small
              background
              layout="prev, pager, next"
              :total="requests.pagination.total"
              @current-change="fetch"
              class="mt-5"
          />
        </div>
        <el-empty :image-size="100" v-else description="Нет заявок"/>
      </div>
    </div>
  </div>
  <RequestForm @submitted="handleCreatedRequest" :visible="modalVisible" @close="modalVisible = false"/>
</template>

<script>
import PageHeader from "../components/PageHeader.vue";
import {RequestsService} from "../services/Api/RequestsService.js";
import moment from "moment-timezone";
import Request from "../components/Request/Request.vue";
import {STATUS_NAME} from "../helpers/statuses.js";
import initRequestsQuery from "../helpers/initRequestsQuery.js";
import RemoteSelect from "../components/RemoteSelect.vue";
import transportCrmProviders from "../mixins/transportCrmProviders.js";
import RequestForm from "../components/Forms/RequestForm.vue";

const initQuery = JSON.parse(JSON.stringify(initRequestsQuery));

export default {
  name: "Requests",
  components: {PageHeader, Request, RemoteSelect, RequestForm},
  mixins: [transportCrmProviders],
  mounted() {
    this.fetch()
  },
  data() {
    return {
      requests: {
        data: [],
        pagination: null,
        loading: false
      },
      modalVisible: false,
      query: Object.assign({}, initQuery)
    }
  },
  computed: {
    requestsData() {
      return this.requests.data.map(request => {
        return Object.assign(request, {created_at: moment.tz(request.created_at, 'Asia/Vladivostok').tz(moment.tz.guess())})
      })
    },
    STATUS_NAME: () => STATUS_NAME,
    requestFilterCount() {
      return ((this.query.id !== initQuery.id) +
          (this.query.statuses.length !== initQuery.statuses.length) +
          (this.query.created_from !== initQuery.created_from) +
          (this.query.created_to !== initQuery.created_to) +
          (this.query.providers.length !== initQuery.providers.length));
    },
    clientFilterCount() {
      return ((this.query.full_name !== initQuery.full_name) +
          (this.query.city !== initQuery.city));
    },
    commentFilterCount() {
      return ((this.query.ref_comment !== initQuery.ref_comment) + 0);
    },
  },
  methods: {
    fetch(page = 1) {
      this.requests.loading = true
      const query = JSON.parse(JSON.stringify(this.query));
      query.page = page
      RequestsService.index(query)
          .then(({data}) => {
            this.requests.data = data.requests;
            this.requests.pagination = data.pagination
          })
          .catch(err => {
            console.log(err.response)
          })
          .finally(() => {
            this.requests.loading = false
          })
    },
    resetQuery() {
      this.query = JSON.parse(JSON.stringify(initRequestsQuery))
      this.$refs['providerRemoteSelect'].reset()
      this.fetch()
    },
    selectProviders(value) {
      this.query.providers = []
      if (value.length) {
        value.forEach(item => {
          this.query.providers.push(item.id)
        })
      }
    },
    handleCreatedRequest({data}) {
      this.requests.data.unshift(data)
      this.modalVisible = false
    },
    commentChange({index, data}) {
      this.requests.data[index] = data
    }
  }
}
</script>

<style>
.el-pagination.is-background .el-pager li {
  background: #fff;
}

.el-pagination.is-background .btn-prev {
  background: #fff;
}

.el-pagination.is-background .btn-next {
  background: #fff;
}

.el-loading-mask {
  border-radius: 0.75rem;
}
</style>