import $api from "../../http/index.js";

class CitiesGetRequestDto {
    /** @type {integer} */
    site_id;
    /** @type {integer, null} */
    provider_id;
    /** @type {string} */
    query;
    /** @type {integer} */
    per_page;
    /** @type {integer} */
    page;

    constructor(data = null) {
        if (!data?.site_id) {
            throw new Error('missing required parameters')
        }

        this.site_id = data.site_id;
        this.provider_id = data.provider_id ?? null;
        this.query = data?.query ?? '';
        this.per_page = data?.per_page ?? 15;
        this.page = data?.page ?? 1;
    }
}

class CitiesServices {
    /**
     * @param {CitiesGetRequestDto} data
     * @param signal
     */
    static async list(data, signal) {
        return $api.get('/cities/list', {params: data, signal})
    }
}

export {CitiesServices, CitiesGetRequestDto}