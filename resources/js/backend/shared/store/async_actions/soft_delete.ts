import axios from "axios";
import { buildApiUrl } from "../createStore";

async function execute(this: any) {
    let url = buildApiUrl(this.api_host, this.api_version, this.api_end_point, 'soft-delete');

    try {
        let response = await axios.post(url, {slug: this.item.slug});
        return response;
    } catch (error: any) {
        return error.response;
    }
}

export default execute;
