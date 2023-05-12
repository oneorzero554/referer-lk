import $api from "../../http/index.js";

export default class SourcesService {
    static async list() {
        return $api.get('/sources/list')
    }
}