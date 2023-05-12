<template>
  <el-dialog
      :model-value="visible"
      :title="title"
      width="30%"
      @open="open"
      :align-center="true"
  >
    <el-form label-position="top" ref="form" :model="form_data" :disabled="loading" :rules="rules" @submit.prevent>
      <el-form-item prop="ref_comment" label="Комментарий">
        <el-input
            placeholder="Введите комментарий"
            type="textarea"
            v-model="form_data.ref_comment"
            :maxlength="1000"
            :show-word-limit="true"
            :validate-event="false"
            :autosize="{minRows: 6}"
        />
      </el-form-item>

      <el-button type="primary" :disabled="loading" :loading="loading" @click="submit">Сохранить</el-button>
    </el-form>
  </el-dialog>
</template>

<script>
import {RequestsService} from "../../services/Api/RequestsService.js";
import NoticeService from "../../services/NoticeService.js";

export default {
  name: "CommentChanger",
  emits: ['commentChange'],
  props: {
    initial: {
      required: true
    },
    id: {
      type: Number,
      required: true,
    },
    title: {
      type: String,
      required: true
    },
    visible: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      form_data: {
        ref_comment: ''
      },
      loading: false,
      rules: {
        ref_comment: [
          {max: 1000, message: 'Превышен максимальный размер символов'}
        ]
      }
    }
  },
  methods: {
    open() {
      this.form_data.ref_comment = this.initial || ''
    },
    submit() {
      this.$refs.form.validate(isValid => {
        if (isValid) {
          this.loading = true;

          const data = Object.assign(JSON.parse(JSON.stringify(this.form_data)), {request_id: this.id})

          RequestsService.update(data)
              .then(({data}) => {
                this.$emit('commentChange', {data: data})
              })
              .catch(err => {
                NoticeService.onError('Изменение комментария', 'Внутреняя ошибка сервера, попробуйте позже.')
              })
              .finally(() => {
                this.loading = false;
              })
        }
      })
    },
  }
}
</script>

<style scoped>

</style>