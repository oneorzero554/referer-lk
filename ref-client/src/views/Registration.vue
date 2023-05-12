<template>
  <div class="h-full p-5 flex overflow-y-auto">
    <el-card shadow="always" header="Регистрация" style="max-width: 450px; width: 100%" class="m-auto">
      <RegistrationForm/>
      <el-divider/>

      <el-text class="mr-1" type="info">Уже есть учетная запись?</el-text>
      <router-link to="/login">
        <el-text type="primary">Войти</el-text>
      </router-link>

    </el-card>
  </div>

</template>

<script>
import RegistrationForm from "../components/Forms/RegistrationForm.vue";

export default {
  name: "Registration",
  components: {RegistrationForm},
  data: () => {
    return {
      form: {
        fullName: '',
        phone: '',
        email: '',
        password: '',
        dob: null
      }
    }
  }
}
</script>

<style>

</style>