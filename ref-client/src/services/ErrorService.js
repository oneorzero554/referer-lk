import {ElNotification} from "element-plus";
import 'element-plus/es/components/notification/style/css'

export default class ErrorService {
    // constructor() {
    //     this.initHandler();
    // }
    //
    // initHandler() {
    //     window.onerror = (message, url, lineNo, columnNo, error) => {
    //         if (error) {
    //             // ErrorService.onError(error);
    //             // console.log(message, url, lineNo, columnNo, error);
    //             console.log(123)
    //         }
    //     };
    // }

    static onApiError(error) {
        const response = error.response;

        if (response && response.status > 400 && response.status !== 401 && response.status !== 422) {
            ErrorService.displayErrorNotice('Внутреняя ошибка', 'Произошла внутреняя ошибка сервиса. Попробуйте позже.')
        }
    }

    static onError(error) {
        ErrorService.displayErrorNotice('Внутреняя ошибка', 'Произошла внутреняя ошибка сервиса. Попробуйте позже.')
    }

    static displayErrorNotice(title = 'Ошибка', message = '') {
        ElNotification({
            type: 'error',
            title: title,
            message: message
        })
    }

    static parseError(error) {

    }
}