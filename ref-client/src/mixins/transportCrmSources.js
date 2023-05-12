import SourcesService from "../services/Api/SourcesService.js";

export default {
    methods: {
        transportCrmSources(list) {
            return SourcesService.list()
                .then(({data}) => {
                    let res = data.data.map(item => {
                        let name = item.name;
                        if (item.provider_name) {
                            name = item.provider_name
                        }

                        return {
                            id: item.id,
                            source_name: item.name,
                            name: name
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