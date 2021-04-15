<template>
    <div>
        <h3>Vue Js funcionando no {{project}}</h3>
    </div>
</template>

<script>

    export default {
      name: 'Products',
      data: () => {
      return {
        'project': 'Symfony 5 com VueJS'
      }
    }
}

</script>