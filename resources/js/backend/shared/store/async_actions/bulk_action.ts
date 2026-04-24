import axios from "axios";
import { buildApiUrl } from "../createStore";

async function execute(this: any, action: any, ids: any) {
    let url = buildApiUrl(this.api_host, this.api_version, this.api_end_point, 'bulk-action');

    try {
        let response = await axios.post(url, { action, ids });
        return response;
    } catch (error: any) {
        if (error.response?.status == 422) {
            (window as any).s_alert('Fill the required input fields.', 'error');
        }
        return error.response;
    }
}

export default execute;
