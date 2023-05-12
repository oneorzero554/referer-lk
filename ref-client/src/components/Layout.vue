<template>
  <div class="container py-3 flex flex-col min-h-full">
    <header>
      <div class="flex mb-3">
        <div class="py-3 px-2 bg-zinc-100 rounded-xl shrink-0 mr-3 lg:w-64 xl:py-4 xl:px-3">
          <div class="flex items-center h-full">
            <el-link :underline="false" @click="drawer = !drawer" class="w-full flex item-center">
              <img width="160" :src="Logo" alt="" class="max-w-[100%] mr-2 hidden lg:flex">
              <img width="40" :src="LogoMini" alt="" class="max-w-[100%] mr-2 lg:hidden">
              <el-icon :size="20" color="#c0c4cc">
                <i-mdi-menu-down/>
              </el-icon>
            </el-link>
          </div>
        </div>
        <div class="flex justify-end py-3 px-2 bg-zinc-100 rounded-xl grow xl:py-4 xl:px-3">
          <el-dropdown :hide-on-click="false" trigger="click">
            <div class="flex items-center bg-[#409eff] text-white rounded-xl py-1.5 px-3">
              <div class="flex-col items-end mr-3 hidden sm:flex">
                <span class="mb-1 text-right font-bold">{{ user.full_name }}</span>
                <span class="text-xs">{{ user.email }}</span>
              </div>
              <el-icon :size="30">
                <i-mdi-account-circle-outline/>
              </el-icon>
            </div>
            <template #dropdown>
              <div>
                <div class="flex flex-col items-start p-4">
                  <span>{{ user.full_name }}</span>
                  <span>{{ user.email }}</span>
                </div>
                <el-dropdown-menu>
                  <el-dropdown-item @click="logout()">Выйти</el-dropdown-item>
                </el-dropdown-menu>
              </div>

            </template>
          </el-dropdown>
        </div>
      </div>
    </header>
    <el-container>
      <el-aside class="mr-3 w-64 hidden lg:flex overflow-visible">
        <div
            class="py-3 px-2 bg-zinc-100 rounded-xl xl:py-4 xl:px-3 w-full max-h-[calc(100vh-25px)] sticky top-[0.75rem]">
          <RouterMenu/>
        </div>
      </el-aside>
      <el-main class="p-0 flex flex-col min-h-full">
        <slot></slot>
      </el-main>
    </el-container>
  </div>

  <el-drawer v-model="drawer" title="I'm outer Drawer" direction="ltr" :show-close="true" size="320">
    <template v-slot:header>
      <div class="flex flex-col mr-auto">
        <img width="160" :src="Logo" alt="" class="max-w-[200px]">
        <span class="text-lg text-[#333]">Реферальная система</span>
      </div>
    </template>
    <div>
      <RouterMenu @select="drawer = false"/>
    </div>
  </el-drawer>
</template>

<script>
import {useAuthStore} from "../stores/auth.js";
import RouterMenu from "./RouterMenu.vue";
import Logo from "../assets/img/m-logo.svg"
import LogoMini from "../assets/img/m-logo-mini.svg"

export default {
  components: {RouterMenu},
  setup() {
    const authStore = useAuthStore();
    return {authStore, Logo, LogoMini}
  },
  name: "Layout",
  data() {
    return {
      drawer: false
    }
  },
  computed: {
    user() {
      return this.authStore.user
    }
  },
  methods: {
    logout() {
      this.authStore.logout()
    },
  }
}
</script>

<style scoped>

</style>