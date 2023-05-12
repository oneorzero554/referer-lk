<template>
  <el-dialog
      :model-value="visible"
      title="Новая заявка"
      class="rounded-xl w-[calc(100%-30px)] max-w-[500px]"
      :align-center="true"
  >
    <el-form label-position="top" ref="requestForm" @submit.prevent :model="form_data" :disabled="loading" :rules="rules">
      <el-form-item ref="full_name" label="ФИО клиента" prop="full_name">
        <el-input
            v-model="form_data.full_name"
            label="ФИО"
            placeholder="Фамилия Имя Отчество"
            :validate-event="false"
        />
      </el-form-item>
      <el-form-item ref="phone" type="tel" label="Контактный номер телефона" prop="phone">
        <el-input v-model="form_data.phone" v-mask="'8-(###)-###-##-##'" placeholder="8-(###)-###-##-##"
                  :validate-event="false"/>
      </el-form-item>
      <el-form-item ref="source" label="Провайдер" prop="source">
        <RemoteSelect
            :transport="transportCrmSources"
            :clearable="true"
            :multiple="false"
            size="large"
            placeholder="Провайдер"
            @change="selectSource"
            ref="sourcesRemoteSelect"
            :validate-event="false"
        />
      </el-form-item>
      <el-form-item ref="city" label="Населенный пункт" prop="city">
        <el-input
            v-model="form_data.city"
            label="Населенный пункт"
            placeholder="Населенный пункт"
            :validate-event="false"
        />
      </el-form-item>
      <el-form-item ref="address" label="Адрес" prop="address">
        <el-input
            v-model="form_data.address"
            label="Адрес"
            placeholder="Улица, № дома, № кв"
            :validate-event="false"
        />
      </el-form-item>
      <el-form-item ref="building_type" label="Тип помещения" prop="building_type">
        <el-select v-model="form_data.building_type" class="w-full">
          <el-option value="Квартира"/>
          <el-option value="Частный дом"/>
          <el-option value="Офис"/>
        </el-select>
      </el-form-item>
      <el-form-item ref="ref_comment" label="Комментарий" prop="ref_comment">
        <el-input
            type="textarea"
            :show-word-limit="true"
            v-model="form_data.ref_comment"
            label="Комментарий"
            placeholder="Комментарий"
            :validate-event="false"
            maxlength="1000"
            :autosize="{minRows: 4}"
        />
      </el-form-item>
      <el-form-item class="mt-10">
        <el-button :loading="loading" class="w-full" type="primary" @click="submit()">Создать</el-button>
      </el-form-item>
    </el-form>
  </el-dialog>
</template>

<script>
import {mask} from 'vue-the-mask'
import RemoteSelect from "../RemoteSelect.vue";
import transportCrmSources from "../../mixins/transportCrmSources.js";
import {RequestsService} from "../../services/Api/RequestsService.js";
import NoticeService from "../../services/NoticeService.js";

export default {
  name: "RequestForm",
  components: {RemoteSelect},
  mixins: [transportCrmSources],
  directives: {
    mask
  },
  props: {
    visible: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      form_data: {
        full_name: '',
        phone: '',
        address: '',
        city: '',
        building_type: 'Квартира',
        ref_comment: '',
        source: null
      },
      rules: {
        full_name: [
          {required: true, message: 'Введите Фамилию Имя Отчество'},
          {max: 255, message: 'Введите меньше символов'}
        ],
        source: [
          {required: true, message: 'Выберите провайдера'}
        ],
        building_type: [
          {required: true, message: 'Выберите тип помещения'}
        ],
        city: [
          {required: true, message: 'Введите город'},
          {max: 255, message: 'Введите меньше символов'}
        ],
        phone: [
          {
            required: true,
            message: 'Введите номер телефона',
          },
          {
            validator: (rule, value, callback) => {
              if (value.match(/\d/g).length !== 11) {
                callback(new Error('Введите корректный номер телефона'))
              } else {
                callback()
              }
            },
          },
        ],
        address: [
          {max: 255, message: 'Введите меньше символов'}
        ],
        ref_comment: [
          {max: 1000, message: 'Введите меньше символов'}
        ],
      },
    }
  },
  methods: {
    submit() {
      this.$refs.requestForm.validate(isValid => {
        if (isValid) {
          this.loading = true;
          let data = JSON.parse(JSON.stringify(Object.assign(this.form_data, {phone: this.unmaskedNumbers(this.form_data.phone)})))
          RequestsService.create(data)
              .then(({data}) => {

                this.$emit('submitted', {data: data})

                this.$refs['requestForm'].resetFields();
                this.$refs['sourcesRemoteSelect'].reset();
              })
              .catch(err => {
                const response = err.response

                if (response && response.status === 422 && response.data) {

                  const errors = response.data.errors;

                  if (errors) {
                    for (const key in errors) {
                      const ref = this.$refs[key];
                      if (ref) {
                        ref.validateState = 'error'
                        ref.validateMessage = errors[key][0]
                      }
                    }
                  }
                }

                NoticeService.onError('Создание заявки', 'Внутреняя ошибка серевера, попробуйте позже.')
              })
              .finally(() => {
                this.loading = false;
              })
        }
      })
    },
    unmaskedNumbers(val) {
      if (val) {
        return val.match(/\d/g).join('');
      }
    },
    selectSource(value) {
      this.form_data.source = null
      if (value) {
        this.form_data.source = value.source_name
      }
    }
  }
}
</script>

<style>

</style>