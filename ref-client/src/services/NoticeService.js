import {ElNotification} from "element-plus";
import 'element-plus/es/components/notification/style/css'

export default class NoticeService {

    static onSuccess(title, message) {
        NoticeService.displayNotice(title, message, 'success')
    }

    static onWarn(title, message) {
        NoticeService.displayNotice(title, message, 'warning')
    }

    static onError(title, message) {
        NoticeService.displayNotice(title, message, 'error')
    }

    static displayNotice(title = 'Успешно', message = '', type = 'success') {
        ElNotification({
            type: type,
            title: title,
            message: message
        })
    }
}