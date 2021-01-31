/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueChatScroll from 'vue-chat-scroll'
Vue.use(require('vue-chat-scroll'))

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('main-component', require('./components/MainComponent.vue').default);
Vue.component('user-list-component', require('./components/UserListComponent.vue').default);
Vue.component('chat-component', require('./components/ChatComponent.vue').default);
Vue.component('video-component', require('./components/VideoComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    created(){
        Echo.channel('updateUserStatus')
        .listen('UpdateUserOnlineStatus',(e) =>{
            console.log(e);
        });
    //    Echo.join('testChannel.1')
    //    .listen('TestEvent',(e) => {
    //        console.log(e);
    //    }).here((user) => {
    //     console.log('here',user);
    //    }).joining((user) => {
    //        console.log('join', user);
    //    }).leaving((user) => {
    //        console.log('leaving',user);
    //    })
    }
});
