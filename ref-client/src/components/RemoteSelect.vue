<template>
  <el-select
      v-model="value"
      :placeholder="placeholder"
      :size="size"
      :clearable="clearable"
      :multiple="multiple"
      value-key="id"
      class="w-full"
      remote
      @visible-change="(visible) => handleVisible(visible)"
      :loading="loading"
  >
    <el-option
        v-for="item in list"
        :key="item.id"
        :label="item.name"
        :value="item"
    />
  </el-select>
</template>

<script>
export default {
  name: "RemoteSelect",
  props: {
    transport: {
      required: true,
      type: Function
    },
    multiple: {
      type: Boolean,
      default: false
    },
    clearable: {
      type: Boolean,
      default: false
    },
    placeholder: {
      type: String
    },
    size: {
      type: String,
      default: 'default'
    }
  },
  data() {
    return {
      loading: false,
      value: null,
      list: []
    }
  },
  methods: {
    handleVisible(visible) {
      if (visible && !this.list.length && !this.loading) {
        this.loading = true
        this.transport(this.list)
            .finally(() => {
              this.loading = false
            })
      }
    },
    reset() {
      this.list = []
      this.value = null
    }
  }
}
</script>

<style scoped>

</style>