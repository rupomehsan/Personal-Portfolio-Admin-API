
import axios from "axios";
import { defineStore } from "pinia";

export const auth_store = defineStore("auth_store", {
    state: () => ({
        is_auth: 0,
        auth_info: {},
        role: {},
        isCheckingAuth: false,
    }),
    getters: {
        get_auth_info: function () {
            return this.auth_info
        },
    },
    actions: {

        set_is_auth: function (status) {
            this.is_auth = status;
        },
        log_out: async function () {
            var data = await window.s_confirm("Are you sure want to logout?")
            if (data) {
                axios.post(window.location.origin + "/logout", {});
                localStorage.removeItem("auth_token");
                localStorage.removeItem("auth_role");
                return (location.href = "/login");
            }
        },
        check_is_auth: async function () {
            // Prevent multiple simultaneous auth checks
            if (this.isCheckingAuth) {
                // console.log('Auth check already in progress, skipping...');
                return;
            }

            // If user is already authenticated and has auth_info, skip the check
            if (this.is_auth && this.auth_info && Object.keys(this.auth_info).length > 0) {
                // console.log('User already authenticated, skipping auth check');
                return;
            }

            // Check if token exists in localStorage
            const token = localStorage.getItem('auth_token');
            if (!token) {
                console.log('No auth token found');
                this.is_auth = 0;
                this.auth_info = {};
                return;
            }

            this.isCheckingAuth = true;

            try {
                // Use privateAxios to ensure token is sent
                let response = await window.privateAxios("check_user", "get");
                console.log("Auth check response:", response);

                if (response?.status === 'success') {
                    this.auth_info = response?.data;
                    this.is_auth = 1;
                    this.role = response.role;
                } else {
                    // Token might be invalid
                    this.is_auth = 0;
                    this.auth_info = {};
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('auth_role');
                }
                return response;
            } catch (error) {
                console.error("Auth check error:", error);
                // If error is 401 (unauthorized), clear the token
                if (error.response?.status === 401) {
                    this.is_auth = 0;
                    this.auth_info = {};
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('auth_role');
                }
                return error;
            } finally {
                this.isCheckingAuth = false;
            }
        },
        auth_check: async function () {
            try {
                let response = await window.privateAxios("auth-check", "get");
                if (response?.status == 'success') {
                    this.auth_info = response.data;
                    this.is_auth = 1;
                    this.role = response.data.role;
                }
                return response;
            } catch (error) {
                console.error("Auth check error:", error);
                if (error.response?.status === 401) {
                    this.is_auth = 0;
                    this.auth_info = {};
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('auth_role');
                }
                return error;
            }
        },


    },
});
