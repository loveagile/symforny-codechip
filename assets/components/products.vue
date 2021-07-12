<template>
  <div class="row">
    <div class="col-3 mb-10" v-for="product in products">
      <div class="card">
        <img v-if="product.thumb" :src="'/uploads/products/' + product.thumb" alt="..." class="card-img-top">
        <img v-else :src="'/images/no-photo.jpg'" alt="" class="img-fluid">
        <div class="card-body">
          <h5 class="card-title">{{ product.name }}</h5>
          <p class="card-text">{{ product.description }}</p>
          <a :href="product.slug">Ver Produto</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
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