import $api from "../../http/index.js";

export default class VerifyEmailService {
    static async resend() {
        return $api.post('/email/verification-notification')
    }
}