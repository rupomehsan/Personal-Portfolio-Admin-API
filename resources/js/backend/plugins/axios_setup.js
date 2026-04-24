import moment from "moment";
import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// window.axios.defaults.headers.common["Authorization"] = `Bearer ${window.localStorage?.token}`;
axios.defaults.baseURL = location.origin + "/api/v1/";

async function setToken(config = {}) {
    config.headers.set('Authorization', `Bearer ${localStorage.getItem('auth_token')}`);
}

axios.interceptors.request.use(
    async function (config) {
        await setToken(config);

        $(".loader_body").addClass("active");
        $(".loader_body").css({ top: $(".main_content").scrollTop() });
        $("#backend_body .main_content").css({ overflowY: "hidden !mportant" });
        $("form button").prop("disabled", true);
        Pace?.restart();
        window.count_left_time_sec = 1;
        sessionStorage.setItem(
            "last_time",
            moment().format("DD/MM/YYYY HH:mm:ss")
        );
        localStorage.setItem("last_time", new moment());
        return {
            ...config,
            // onUploadProgress,
            // onDownloadProgress,
        };
    },
    function (error) {
        // Do something with request error
        console.log("request error");
        return Promise.reject(error);
    }
);

window.remove_form_action_classes = function () {
    $(".loader_body").removeClass("active");
    $("input,select,textarea").removeClass("border-warning");
    $("form button").prop("disabled", false);
    $(`.error.text-warning`).remove();
};


// window.errorReset = function (event) {
//     console.log(event.target)
// }

function errorReset(event) {
    console.log(event.target)
}

window.render_form_errors = function (object, selector = "name") {
    for (const key in object) {
        if (Object.hasOwnProperty.call(object, key)) {
            const element = object[key];
            // console.log("resss",element);
            let el = document.querySelector(`input[${selector}="${key}`);
            let txarea = document.querySelector(`textarea[${selector}="${key}`);
            if (!el) {
                el = document.getElementById(`${key}`);
            }
            if (txarea) {
                el = document.querySelector(`textarea[${selector}="${key}`);
            }

            /**
             *  if html element found then take action
             */
            if (el) {
                $(`<div class="error text-warning">${element[0]}</div>`).insertAfter(el);
                el.classList.add("border-warning");
            }
        }
    }
};

window.axios.interceptors.response.use(
    (response) => {
        remove_form_action_classes();
        return response;
    },
    (error) => {
        remove_form_action_classes();
        let object = error.response?.data?.errors;
        render_form_errors(object);

        const status = error.response?.status;
        const data = error.response?.data;

        // Handle connection/network errors
        if (!error.response) {
            window.s_error("Network error! Please check your internet connection.");
            return Promise.reject(error);
        }

        // Handle 404 errors
        if (status === 404) {
            const message = data?.message || "Resource not found (404)";
            window.s_error(message);
            return Promise.reject(error);
        }

        // Handle 401 Unauthorized
        if (status === 401) {
            const message = data?.message || "Unauthorized! Please login again.";
            window.s_error(message);
            window.clear_session?.();
            return Promise.reject(error);
        }

        // Handle 403 Forbidden
        if (status === 403) {
            const message = data?.message || "Forbidden! You don't have permission.";
            window.s_error(message);
            return Promise.reject(error);
        }

        // Handle validation errors (422)
        if (status === 422) {
            const message = data?.message || "Validation failed! Please check your input.";
            window.s_error(message);
            return Promise.reject(error);
        }

        // Handle server errors (5xx)
        if (status >= 500) {
            const message = data?.message || "Server error! Please try again later.";
            window.s_error(message);
            return Promise.reject(error);
        }

        // Handle response with message
        if (data?.message) {
            if (data.status === "server_error") {
                window.s_warning(data.message);
            } else if (data.status === "error") {
                window.s_error(data.message);
            } else {
                window.s_error(data.message);
            }
            return Promise.reject(error);
        }

        // Fallback error message
        const fallbackMessage = `Error ${status}: ${error.message}`;
        window.s_error(fallbackMessage);

        return Promise.reject(error);
    }
);


