import SourcesService from "../services/Api/SourcesService.js";

export default {
    methods: {
        transportCrmProviders(list) {
            return SourcesService.list()
                .then(({data}) => {
                    let res = data.data.map(item => {
                        if (item.provider_id) {
                            return {
                                id: item.provider_id,
                                name: item.provider_name
                            }
                        }
                    })

                    res.forEach(item => {
                        list.push(item)
                    })
                })
                .catch(err => {
                    console.log(err.response)
                })
        }
    }
}