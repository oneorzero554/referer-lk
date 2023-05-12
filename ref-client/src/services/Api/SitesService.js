import $api from "../../http/index.js";

export default class SitesService {
    static async list() {
        return $api.get('/sites/list')
    }
}