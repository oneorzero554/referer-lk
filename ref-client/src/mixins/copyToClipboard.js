import NoticeService from "../services/NoticeService.js";

export default {
    name: "copyToClipboard",
    methods: {
        copyToClipboard(text) {
            let text_area = document.createElement('textarea')

            text_area.style.position = 'fixed'
            text_area.style.top = 0
            text_area.style.left = 0
            text_area.style.width = '2em'
            text_area.style.height = '2em'
            text_area.style.padding = 0
            text_area.style.border = 'none'
            text_area.style.outline = 'none'
            text_area.style.boxShadow = 'none'
            text_area.style.background = 'transparent'
            text_area.value = text

            document.body.appendChild(text_area)

            text_area.focus()
            text_area.select()

            try {
                document.execCommand('copy')
                NoticeService.onSuccess('Реферальная ссылка', 'Реферальная ссылка создана и скопирована.')
            } catch (err) {
                console.log('Unable to copy')
            }

            document.body.removeChild(text_area)
        }
    }
}