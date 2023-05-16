<template>
  <el-form label-position="top" ref="registrationForm" @submit.prevent :model="form_data" :rules="rules">
    <el-divider content-position="left">Данные о вас</el-divider>
    <el-form-item ref="full_name" label="ФИО" prop="full_name">
      <el-input
          v-model="form_data.full_name"
          label="Фимиля"
          placeholder="Фамилия Имя Отчество"
          :validate-event="false"
      />
    </el-form-item>
    <el-form-item ref="dob" label="Дата рождения" prop="dob">
      <el-date-picker
          v-model="form_data.dob"
          type="date"
          placeholder="Выберите дату рождения"
          format="DD.MM.YYYY"
          value-format="YYYY-MM-DD"
          class="w-full"
          :validate-event="false"
      />
    </el-form-item>

    <el-form-item ref="phone" type="tel" class="mb-10" label="Контактный номер телефона" prop="phone">
      <el-input v-model="form_data.phone" v-mask="'8-(###)-###-##-##'" placeholder="8-(###)-###-##-##"
                :validate-event="false"/>
    </el-form-item>

    <el-divider content-position="left">Данные для входа в систему</el-divider>

    <el-alert class="mb-2" type="info" show-icon :closable="false">
      <p>Укажите действующий Email, на него будет отправлено письмо с подтверждением регистрации.</p>
    </el-alert>
    <el-form-item ref="email" label="Email" prop="email">
      <el-input v-model="form_data.email" type="email" placeholder="Введите Email" :validate-event="false"/>
    </el-form-item>
    <el-form-item ref="password" label="Пароль" prop="password">
      <el-input
          v-model="form_data.password"
          type="password"
          placeholder="Введите пароль"
          show-password
          :validate-event="false"
      />
    </el-form-item>
    <el-form-item ref="password_confirmation" label="Подтверждение пароля" prop="password_confirmation">
      <el-input
          v-model="form_data.password_confirmation"
          type="password"
          placeholder="Подтверждение пароля"
          show-password
          :validate-event="false"
      />
    </el-form-item>
    <el-form-item class="mt-10">
      <el-button :loading="loading" class="w-full" type="primary" @click="submit()">Зарегистрироваться</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
import {mask} from 'vue-the-mask'
import AuthService from "../../services/Api/AuthService.js";
import NoticeService from "../../services/NoticeService.js";
import {useAuthStore} from "../../stores/auth.js";

export default {
  setup() {
    const authStore = useAuthStore();
    return {authStore};
  },
  name: "RegistrationForm",
  directives: {
    mask
  },
  data() {
    return {
      loading: false,
      form_data: {
        full_name: '',
        phone: '',
        email: '',
        password: '',
        password_confirmation: '',
        dob: null,
      },
      rules: {
        full_name: [
          {required: true, message: 'Введите Фамилию Имя Отчество'},
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
        email: [
          {required: true, message: 'Введите почтовый адрес'},
          {type: 'email', message: 'Введите корректный почтовый адрес'},
        ],
        password: [
          {
            required: true,
            message: 'Введите пароль',
          },
          {
            min: 6,
            message: 'Минимальная длина пароля 6',
          },
          {
            message: 'Пароль должен содержать 0-9, !@#$%^&*, a-z, A-Z',
            pattern: /(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{6,}/,
          },
        ],
        password_confirmation: [
          {required: true, message: 'Введите подтверждение пароля'},
          {
            validator: (rule, value, callback) => {
              if (value !== this.form_data.password) {
                callback(new Error('Пароли не совпадают'))
              } else {
                callback()
              }
            },
          }
        ],
        dob: [
          {type: 'date', required: true, message: 'Выберите дату рождения'},
        ],
      },
    }
  },
  methods: {
    submit() {
      this.$refs.registrationForm.validate(isValid => {
        if (isValid) {
          this.loading = true;
          let data = JSON.parse(JSON.stringify(Object.assign(this.form_data, {phone: this.unmaskedNumbers(this.form_data.phone)})))

          this.authStore.registration(data)
              .then(() => {
                this.$router.push('/home')
                NoticeService.onSuccess('Регистрация', 'Вы успешно зарегистрировались. Подтвердите почту.')
              })
              .catch(err => {
                const response = err.response

                if (response && response.status === 422 && response.data) {

                  NoticeService.onError('Ошибка регистрации', response.data.message)

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
              })
              .finally(() => {
                this.loading = false
              })
        }
      })
    },
    unmaskedNumbers(val) {
      if (val) {
        return val.match(/\d/g).join('');
      }
    }
  }
}
</script>

<style>

</style>