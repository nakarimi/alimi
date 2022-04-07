import Vue from "vue";
import App from "./App.vue";

// Vuesax Component Framework
import Vuesax from "vuesax";
Vue.use(Vuesax);

// vForm start
import { Form, HasError, AlertError } from "vform";
window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
import axios from "axios";
import VueAxios from "vue-axios";
Vue.use(VueAxios, axios);

import moment from "moment";
moment.locale("fa");
Vue.prototype.moment = moment;

var momentj = require("moment-jalaali");
momentj().format("jYYYY/jM/jD");
Vue.prototype.momentj = momentj;

import "./http/requests";

// start sweetalert
import Swal from "sweetalert2";
window.swal = Swal;

// Theme Configurations
import "../themeConfig.js";

// ACL
import acl from "./acl/acl";

// Globally Registered Components
import "./globalComponents.js";

// Vue Router
import router from "./router";

// Vuex Store
import store from "./store/store";

// i18n
import i18n from "./i18n/i18n";

// Vuexy Admin Filters
import "./filters/filters";

// VeeValidate
import VeeValidate from "vee-validate";
Vue.use(VeeValidate);

// Feather font icon
require("@assets/css/iconfont.css");

// VueProgressBar
import VueProgressBar from "vue-progressbar";
Vue.use(VueProgressBar, {
    color: "#432e81",
    // backgound: "linear-gradient(calc(360deg - 175deg), rgba(86, 45, 183, 0.5) 6%, rgba(245, 82, 82, 0.8) 130%) !important",
    failedColor: "red",
    thickness: "4px",
    transition: {
        speed: "0.2s",
        opacity: "0.6s",
        termination: 300
    },
    autoRevert: true,
    // location: "left",
    inverse: true,
    autoFinish: true
});
Vue.filter("NumThreeDigit", function(value) {
    var nStr = value.toString().replace(/[^0-9.]/g, "");
    if (nStr.length > 0 && nStr > 0) {
        nStr = nStr > 0 ? nStr.replace(/^0+/, "") : nStr;
        nStr = nStr.replace(/\,/g, "");
        var x = nStr.split(".");
        var x1 = x[0];
        var x2 = x.length > 1 ? "." + x[1] : "";
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, "$1" + "," + "$2");
        }
        value = x1 + x2;
        return value;
    } else {
        return value;
        return 0;
    }
});

// Persian DatePcicker
import VuePersianDatetimePicker from "vue-persian-datetime-picker";
Vue.component("date-picker", VuePersianDatetimePicker);

import VuePerfectScrollbar from "vue-perfect-scrollbar";
Vue.component("VuePerfectScrollbar", VuePerfectScrollbar);

// define a mixin object
const Mixin = {
    methods: {}
};
//ahmadi
const MyPlugin = {
    install(Vue, options) {
        Vue.prototype.startProcesAndLoad = () => {};
    }
};
Vue.use(MyPlugin);

// Custom Methods
Vue.mixin({
    methods: {
        formatToEnPrice: function(value, sourceObject, field, fieldObject) {
            const number_sign = Math.sign(value);
            if (!value) {
                return value;
            }
            var nStr = value.replace(/[^0-9.]/g, "");
            
            if (nStr.length > 0 && nStr > 0) {
                nStr = nStr > 0 ? nStr.replace(/^0+/, "") : nStr;
                nStr = nStr.replace(/\,/g, "");
                var x = nStr.split(".");
                var x1 = x[0];
                var x2 = x.length > 1 ? "." + x[1] : "";
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, "$1" + "," + "$2");
                }
                value = x1 + x2;
                value = (value > 0 && value < 1) ? value * 1 : value;
                fieldObject[field] = value;
                sourceObject[field] = String(value).replace(/[^0-9.]/g, "");

                number_sign < 0
                    ? (sourceObject[field] = "-" + sourceObject[field])
                    : undefined;
            } else {
                fieldObject[field] = nStr;
                sourceObject[field] = nStr;
            }
        },
        formatToEnPriceSimple: function(value, floatRange = null) {
            const number_sign = Math.sign(value);
            if (!value) {
                return value;
            }
            if (floatRange !== null) {
                value = parseInt(value).toFixed(floatRange);
            }
            var nStr = value.toString().replace(/[^0-9.]/g, "");
            if (nStr.length > 0 && nStr > 0) {
                nStr = nStr > 0 ? nStr.replace(/^0+/, "") : nStr;
                nStr = nStr.replace(/\,/g, "");
                var x = nStr.split(".");
                var x1 = x[0];
                var x2 = x.length > 1 ? "." + x[1] : "";
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, "$1" + "," + "$2");
                }
                value = x1 + x2;
                value = (value > 0 && value < 1) ? value * 1 : value;
                return number_sign < 0 ? "-" + value : value;
            } else {
                return nStr;
            }
        }
    }
});
// End Of Custom Methods
new Vue({
    mixins: [Mixin],
    el: "#app"
});

// Vue select css
// Note: In latest version you have to add it separately
// import 'vue-select/dist/vue-select.css';

Vue.config.productionTip = false;

new Vue({
    router,
    store,
    i18n,
    acl,
    render: h => h(App)
}).$mount("#app");
