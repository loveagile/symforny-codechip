<template>
  <div class="card">
    <img v-if="product.thumb" :src="'/uploads/products/' + product.thumb" alt="..." class="card-img-top">

    <img v-else :src="'/images/no-photo.jpg'" alt="" class="img-fluid">

    <div class="card-body">
      <h5 class="card-title">{{ product.name }}</h5>
      <p class="card-text">{{ product.description }}</p>
      <h5 class="d-block mt-3 mb-3 text-danger text-right">
        {{ priceBrazil }}
      </h5>
      <a :href="product.slug" class="d-block text-right">Ver Produto</a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductSingle',
  props: {
    product: {
      required: true,
      type: Object
    }
  },
  computed: {
    priceBrazil() {
      return this.product.floatPrice
                 .toLocaleString('pt-BR', {minimumFractionDigits: 2, style: 'currency', currency: 'BRL'});
    }
  }
}
</script>