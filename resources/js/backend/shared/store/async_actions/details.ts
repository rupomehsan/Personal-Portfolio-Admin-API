import axios from "axios";
import { buildApiUrl } from "../createStore";

async function execute(this: any, id: any) {
    let url = buildApiUrl(this.api_host, this.api_version, this.api_end_point, id);
    try {
        let response = await axios.get(url);
        this.item = response.data.data;
    } catch (error: any) {
        (window as any).s_alert('something is wrong.','error');
        return error.response;
    }
}

export default execute;
