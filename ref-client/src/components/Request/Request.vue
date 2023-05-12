<template>
  <div class="px-4 py-6 bg-white rounded-xl mb-5 last:mb-0">
    <div class="flex flex-col">
      <div class="flex mb-3">
        <div class="flex text-xs text-gray-400 space-x-2 mr-auto">
          <span>№{{ index + 1 }}</span>
          <span>#{{ request.id }}</span>
          <span>{{ request.created_at.format('DD.MM.YYYY HH:mm:ss') }}</span>
        </div>
        <div>
          <Status :id="request.status_id" :name="request.status_name"/>
        </div>
      </div>
      <div class="flex flex-col xl:flex-row xl:items-end -m-2 mb-2">
        <div class="xl:w-1/4 p-2">
          <Field :value="request.full_name">
            <template #icon>
              <i-mdi-account/>
            </template>
          </Field>
        </div>
        <div class="xl:w-1/4 p-2 ">
          <Field :value="request.city">
            <template #icon>
              <i-mdi-city/>
            </template>
          </Field>
        </div>
        <div class="xl:w-1/4 p-2">
          <Field :value="request.building_type">
            <template #icon>
              <i-mdi-home-city/>
            </template>
          </Field>
        </div>
        <div class="xl:w-1/4 p-2">
          <Field :value="request.provider_name">
            <template #icon>
              <i-mdi-antenna/>
            </template>
          </Field>
        </div>
      </div>
      <div class="mb-4">
        <Field :value="request.ref_comment">
          <template #icon>
            <i-mdi-comment-multiple-outline/>
          </template>
        </Field>
      </div>
      <div class="flex xl:justify-end">
        <el-button type="primary" size="default" @click="modalVisible = true">Изменить комментарий</el-button>
      </div>
    </div>
  </div>
  <CommentChanger
      :title="`Комментарий заявки #${request.id}`"
      :visible="modalVisible"
      :initial="request.ref_comment"
      :id="request.id"
      @close="modalVisible = false"
      @comment-change="commentChange"
  />
</template>

<script>
import Status from "./Status.vue";
import Nullable from "./Nullable.vue";
import Field from "./Field.vue";
import CommentChanger from "./CommentChanger.vue";

export default {
  name: "Request",
  components: {Status, Nullable, Field, CommentChanger},
  emits: ['commentChange'],
  props: {
    request: {
      required: true,
      type: Object
    },
    index: {
      required: true,
      type: Number
    }
  },
  data() {
    return {
      modalVisible: false
    }
  },
  methods: {
    commentChange({data}) {
      this.$emit('commentChange', {
        index: this.index,
        data: data,
      })

      this.modalVisible = false
    }
  }
}
</script>

<style scoped>

</style>