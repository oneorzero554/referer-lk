<template>
  <div class="h-full p-5 flex overflow-y-auto">
    <el-card shadow="always" header="Вход" style="max-width: 450px; width: 100%" class="m-auto">
      <LoginForm />
      <el-divider/>

      <el-text class="mr-1" type="info">Еще нет учетной записи?</el-text>
      <router-link to="/registration">
        <el-text type="primary">Зарегистрироваться</el-text>
      </router-link>

    </el-card>
  </div>
</template>

<script>
import LoginForm from "../components/Forms/LoginForm.vue";

export default {
  name: "Login",
  components: {LoginForm},

  data: () => {
    return {

    }
  }
}
</script>

<style>

</style>