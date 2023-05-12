<template>
  <el-form ref="loginForm" label-position="top" @submit.prevent :model="form_data" :rules="rules">
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
    <el-form-item class="mt-10">
      <el-button :loading="loading" class="w-full" type="primary" @click="submit()">Войти</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
import {useAuthStore} from "../../stores/auth.js";
import NoticeService from "../../services/NoticeService.js";


export default {
  setup() {
    const authStore = useAuthStore();
    return {authStore};
  },
  name: "LoginForm",
  data() {
    return {
      loading: false,
      form_data: {
        email: '',
        password: '',
      },
      rules: {
        email: {type: 'email', required: true, message: 'Введите почтовый адрес'},
        password: {required: true, message: 'Введите пароль'}
      }
    }
  },
  methods: {
    submit() {
      this.$refs.loginForm.validate(isValid => {
        if (isValid) {

          this.loading = true;

          let data = JSON.parse(JSON.stringify(this.form_data))

          this.authStore.login(data)
              .then(() => {
                this.$router.push('/home')
              })
              .catch(err => {
                if (err.response.status === 400) {
                  NoticeService.onError('Вход', err.response.data.message)

                  const inputs = [this.$refs.email, this.$refs.password];
                  inputs.forEach(item => {
                    item.validateState = 'error'
                  })
                }
              })
              .finally(() => {
                this.loading = false
              })
        }
      })
    }
  }
}
</script>

<style>

</style>