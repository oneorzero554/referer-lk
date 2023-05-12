import {useAuthStore} from "../stores/auth.js";

const authStore = useAuthStore();

class GenerateRefererLinkDto {
    /** @type {string} */
    site_url;
    /** @type {boolean} */
    is_multi;
    /** @type {string} */
    city_url;
    /** @type {string} */
    provider_url;
    /** @type {string} */
    category_url;

    constructor(data = null) {
        if (!data?.site_url) {
            throw new Error('missing required parameters')
        }
        this.site_url = data?.site_url;
        this.is_multi = data?.is_multi || false;
        this.city_url = data?.city_url;
        this.provider_url = data?.provider_url;
        this.category_url = data?.category_url;
    }

    siteUrlPath() {
        return `${this.site_url}/`;
    }

    cityUrlPath() {
        if (this.city_url) {
            return `${this.city_url}/`;
        }

        return null;
    }

    providerUrlPath() {
        if (this.provider_url) {
            return `providers/${this.provider_url}/`;
        }

        return null;
    }

    categoryUrlPath() {
        if (this.category_url) {
            return `${this.category_url}/`;
        }

        return null;
    }
}

class GenerateRefererLinkService {
    /**
     * @param {GenerateRefererLinkDto} params
     */
    static generate(params) {
        if (params.is_multi) {
            return GenerateRefererLinkService.multi(params)
        }

        return GenerateRefererLinkService.mono(params)
    }

    /**
     * @param {GenerateRefererLinkDto} params
     */
    static mono(params) {
        return `${params.siteUrlPath()}${params.cityUrlPath() ? params.cityUrlPath() : ''}${params.categoryUrlPath() ? params.categoryUrlPath() : ''}?referer=${authStore.user.id}`
    }

    /**
     * @param {GenerateRefererLinkDto} params
     */
    static multi(params) {
        return `${params.siteUrlPath()}${params.cityUrlPath() ? params.cityUrlPath() : ''}${params.providerUrlPath() ? params.providerUrlPath() : ''}${params.categoryUrlPath() ? params.categoryUrlPath() : ''}?referer=${authStore.user.id}`
    }
}

export {GenerateRefererLinkService, GenerateRefererLinkDto}