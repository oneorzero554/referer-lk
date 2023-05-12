<template>
  <el-menu class="border-none bg-transparent space-y-3" :router="true" :default-active="this.$route.name" @select="handleSelect()">
<!--    <el-menu-item :route="{name: 'home'}" index="home" class="py-3 h-auto leading-none [&.is-active]:bg-[#409eff] [&.is-active]:text-white rounded-xl">-->
<!--      <el-icon>-->
<!--        <i-mdi-home-outline />-->
<!--      </el-icon>-->
<!--      <span>Домашняя</span>-->
<!--    </el-menu-item>-->
    <el-menu-item :route="{name: 'requests'}" index="requests" class="py-3 h-auto leading-none [&.is-active]:bg-[#409eff] [&.is-active]:text-white rounded-xl">
      <el-icon>
        <i-mdi-receipt-text-edit-outline />
      </el-icon>
      <span>Заявки</span>
    </el-menu-item>
    <el-menu-item :route="{name: 'links'}" index="links" class="py-3 h-auto leading-none [&.is-active]:bg-[#409eff] [&.is-active]:text-white rounded-xl">
      <el-icon>
        <i-mdi-link-variant />
      </el-icon>
      <span>Реферальные ссылки</span>
    </el-menu-item>
  </el-menu>
</template>

<script>
export default {
  name: "RouterMenu",
  methods: {
    handleSelect() {
      this.$emit('select')
    }
  }
}
</script>