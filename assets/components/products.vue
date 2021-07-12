<template>
  <div class="row">
    <div class="col-3 mb-5" v-for="product in products">

      <ProductSingle :product="product"/>

    </div>
  </div>
</template>

<script>
import ProductSingle from './product-single';

export default {
  components: {
    ProductSingle
  },
  name: 'Products',
  data: () => {
    return {
      'products': ''
    }
  },
  created() {
    fetch('http://127.0.0.1:8000/api/products')
        .then((response) => response.json())
        .then((response) => this.products = response);
  }
}

</script>