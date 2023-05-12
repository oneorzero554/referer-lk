import $api from "../../http/index.js";

class RequestIndexDto {

}

class RequestCreateDto {

}

class RequestsService {
    static async index(data) {
        return $api.get('/requests/index', {params: data})
    }

    static async create(data) {
        return $api.post('/requests/create', data)
    }

    static async update(data) {
        return $api.post(`/requests/update`, data)
    }
}

export {RequestsService, RequestIndexDto, RequestCreateDto}