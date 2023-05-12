<template>
  <el-config-provider :locale="locale" size="large">
    <Layout v-if="authStore.isAuth">
      <router-view></router-view>
    </Layout>
    <router-view v-else />
  </el-config-provider>
</template>

<script>
import { ElConfigProvider } from 'element-plus'
import ru from 'element-plus/dist/locale/ru.mjs'
import {useAuthStore} from "./stores/auth.js";
import Layout from "./components/Layout.vue";

export default {

  setup() {
    const authStore = useAuthStore();
    return {authStore};
  },
  components: {ElConfigProvider, Layout},
  data: () => {
    return {
      locale: ru
    }
  }
}

</script>

<style>

</style>
