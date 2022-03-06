import { defineNuxtConfig } from "nuxt3";

// https://v3.nuxtjs.org/docs/directory-structure/nuxt.config
export default defineNuxtConfig({
    typescript: {
        strict: true
    },
    modules: [
        ['@nuxtjs/axios', {baseURL: 'http://my_fave_nginx:9000/api/'}]
    ],
});
