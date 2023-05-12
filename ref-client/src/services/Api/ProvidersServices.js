import $api from "../../http/index.js";

class ProvidersGetRequestDto {
    /** @type {integer} */
    site_id;
    /** @type {integer} */
    city_id;

    constructor(data = null) {
        if (!data?.site_id || !data?.city_id) {
            throw new Error('missing required parameters')
        }

        this.site_id = data.site_id;
        this.city_id = data.city_id;
    }
}

class ProvidersServices {
    /**
     * @param {ProvidersGetRequestDto} data
     */
    static async list(data) {
        return $api.get(`/providers/list`, {params: data})
    }
}

export {ProvidersGetRequestDto, ProvidersServices}