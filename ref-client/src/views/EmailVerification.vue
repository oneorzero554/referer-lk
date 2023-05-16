<template>
  <div class="flex flex-col grow">
    <div class="flex py-3 px-2 bg-zinc-100 rounded-xl xl:py-4 xl:px-3 grow">
      <div class="flex flex-col max-w-[500px] m-auto items-center bg-white rounded-xl p-5">
        <div>
          <el-icon :size="100" color="#409eff">
            <i-mdi-email-seal-outline></i-mdi-email-seal-outline>
          </el-icon>
        </div>
        <div class="mb-8">
          <div class="text-center font-bold text-2xl mb-3">Подтвердите Email адрес</div>
          <div class="text-center mb-3">Ваш адрес электронной почты не подтвержден. Пожалуйста проверьте указанную при
            регистрации электронную почту и следуйте инструкции в письме.
          </div>
          <div class="text-center text-sm text-[#e6a23c]">Если письма нет в папке Входящие, проверьте папку Спам.</div>
        </div>
        <div class="flex flex-col space-y-3 lg:flex-row lg:space-y-0 lg:space-x-3 w-full justify-center">
          <div>
            <el-button
                :loading="resendLoading"
                :disabled="resendLoading || !!countDown" class="w-full"
                type="primary" @click="resend"
            >
              <span v-if="!countDown">
                Отправить повторно
              </span>
              <span v-else>
                Отправить повторно: {{countDown}}
              </span>
            </el-button>
          </div>
          <div>
            <el-button class="w-full" type="danger" @click="reload">Обновить страницу</el-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import VerifyEmailService from "../services/Api/VerifyEmailService.js";
import NoticeService from "../services/NoticeService.js";

export default {
  name: "EmailVerification",
  data() {
    return {
      resendLoading: false,
      resendDisabled: false,
      countDown: 0,
    }
  },
  computed: {},
  methods: {
    resend() {
      this.resendLoading = true
      VerifyEmailService.resend()
          .then((res) => {
            if (res.status === 200) {
              location.reload()
            }
            NoticeService.onSuccess('Подтверждение email', 'Письмо с подтверждением отправлено, проверьте почту.')

            this.countDownTimer(60);
          })
          .catch((err) => {
            console.log(err)
          })
          .finally(() => {
            this.resendLoading = false;
          })
    },
    reload() {
      location.reload();
    },
    countDownTimer(value = null) {
      if (value !== null) {
        this.countDown = value;
      }
      if (this.countDown > 0) {
        setTimeout(() => {
          this.countDown -= 1
          this.countDownTimer()
        }, 1000)
      }
    },
  }
}
</script>

<style>

</style>