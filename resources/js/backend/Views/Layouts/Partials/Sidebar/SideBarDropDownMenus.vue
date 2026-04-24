<template>
    <li :class="`mm-collapse ${is_show && 'mm-active'}`" style="border: 1px solid rgba(128, 128, 128, 0.267);">
        <a @click.prevent="toggleMenu" class="has-arrow" href="#">
            <div class="parent-icon">
                <i v-if="icon" :class="icon"></i>
                <img v-else-if="icon_image" :src="icon_image" alt="">
            </div>
            <div class="menu-title">{{ menu_title }}</div>
        </a>
        <ul :class="`mm-collapse ${is_show && 'mm-show'}`">
            <li v-for="(menu, index) in menus" :key="index" :class="{ 'active': isActiveRoute(menu) }">
                <router-link :to="menu.route ? menu.route : { name: menu.route_name }" :class="{ 'active': isActiveRoute(menu) }">
                    <i :class="menu.icon"></i>
                    {{ menu.title }}
                </router-link>
            </li>
        </ul>
    </li>
</template>

<script>
export default {
    props: {
        menu_title: String,
        menus: Array,
        icon_image: {
            type: String,
            default: "",
        },
        icon: {
            type: String,
            default: "",
        }
    },
    data: () => ({
        is_show: 0,
    }),
    computed: {
        isCurrentMenuActive() {
            // Check if any child route is currently active (supports menu.route object or legacy route_name)
            return this.menus.some(menu => this.isActiveRoute(menu));
        }
    },
    watch: {
        '$route': {
            immediate: true,
            handler() {
                this.updateMenuState();
            }
        }
    },
    mounted() {
        this.updateMenuState();

        // Create unique event name for this menu
        this.collapseEventName = `collapse-${this.menu_title.replace(/\s+/g, '-').toLowerCase()}`;

        // Listen for global collapse events
        window.addEventListener('collapse-all-menus', this.handleCollapseAll);
    },
    beforeDestroy() {
        window.removeEventListener('collapse-all-menus', this.handleCollapseAll);
    },
    methods: {
        toggleMenu() {
            if (!this.is_show) {
                // Before opening this menu, close all others
                this.collapseAllOtherMenus();
                this.is_show = true;
            } else {
                this.is_show = false;
            }
        },
        collapseAllOtherMenus() {
            // Dispatch event to close all other menus
            window.dispatchEvent(new CustomEvent('collapse-all-menus', {
                detail: { except: this.menu_title }
            }));
        },
        handleCollapseAll(event) {
            // Only collapse if this menu is not the exception
            if (event.detail.except !== this.menu_title) {
                this.is_show = false;
            }
        },
        updateMenuState() {
            if (this.isCurrentMenuActive) {
                this.collapseAllOtherMenus();
                this.is_show = true;
            }
        },
        isActiveRoute(menu) {
            if (!menu) return false;
            if (menu.route && menu.route.name) {
                return this.$route.name === menu.route.name;
            }
            if (menu.route_name) {
                return this.$route.name === menu.route_name;
            }
            // fallback if a plain string is passed
            if (typeof menu === 'string') {
                return this.$route.name === menu;
            }
            return false;
        }
    }
}
</script>

<style>
/* Active menu item styling */
.mm-collapse li.active>a,
.mm-collapse li>a.active {
    background-color: #007bff !important;
    color: white !important;
    border-radius: 4px;
    margin: 2px;
}

/* Active dropdown group styling */
.mm-collapse.mm-active>a:first-child {
    background-color: rgba(0, 123, 255, 0.1);
    border-left: 3px solid #007bff;
}
</style>