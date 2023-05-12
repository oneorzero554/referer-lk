import $api from "../../http/index.js";

class CategoriesGetRequestDto {
    /** @type {integer} */
    site_id;
    /** @type {integer} */
    city_id;
    /** @type {integer} */
    provider_id;
    /** @type {integer} */
    per_page;
    /** @type {integer} */
    page;

    constructor(data = null) {
        if (!data?.site_id || !data?.city_id || !data?.provider_id) {
            throw new Error('missing required parameters')
        }

        this.site_id = data.site_id;
        this.city_id = data.city_id;
        this.provider_id = data.provider_id;
        this.per_page = data?.per_page ?? 15;
        this.page = data?.page ?? 1;
    }
}

class CategoriesServices {
    /**
     * @param {CategoriesGetRequestDto} data
     */
    static async list(data) {
        return $api.get('/categories/list', {params: data})
    }
}

export {CategoriesServices, CategoriesGetRequestDto}