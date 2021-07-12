/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
import Vue from 'vue';

import Products from './components/products';

// Vue.component('product-list',{
//    props: {
//      products: {
//          type: Array,
//          required: true
//      }
//    },
//    template: `
//      <div class="row">
//          <div class="col-3 mb-10" v-for="product in products">
//            <div class="card">
//              <img src="" class="card-img-top" alt="...">
//              <img :src="'/images/no-photo.jpg'" alt="" class="img-fluid">
//              <div class="card-body">
//                <h5 class="card-title">{{ product.name }}</h5>
//                <p class="card-text">{{ product.description }}</p>
//                <a :href="product.slug">Ver Produto</a>
//              </div>
//            </div>
//          </div>
//      </div>
//      `
// });
//
new Vue({
   render(h){
       return h(Products);
   }
}).$mount('#app');
console.log('Hello Webpack Encore! Edit me in assets/app.js');

new Vue({
    el: "#app",
    data: {
        'project': 'Symfony 5 com VUEJS'
    },
    template: `
      <h3>VUE JS funcionando no {{project}}</h3>
    `
});
