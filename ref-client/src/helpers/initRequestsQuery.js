import moment from "moment-timezone";

const query = {
    id: '',
    statuses: [],
    providers: [],
    created_from: null,
    created_to: null,
    full_name: '',
    city: '',
    ref_comment: '',
    timezone: moment.tz.guess()
}

export default query;