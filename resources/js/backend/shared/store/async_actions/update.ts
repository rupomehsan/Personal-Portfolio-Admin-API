import axios from "axios";
import { buildApiUrl } from "../createStore";

async function execute(this: any, event: any) {
    let form = event.target;
    let form_data = new FormData(form);
    form_data.append('id', this.item.id);

    let url = buildApiUrl(this.api_host, this.api_version, this.api_end_point, `update/${this.item.slug}`);

    try {
        let response = await axios.post(url, form_data);
        return response;
    } catch (error: any) {
        if(error.response?.status == 422){
            (window as any).s_alert('Fill the required input fields.','error');
        }
        return error.response;
    }
}

export default execute;
