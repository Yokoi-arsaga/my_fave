<template>
  <div>
    <p>{{data}}</p>
  </div>
</template>

<script setup>
    const {data} = await useFetch('http://my_fave_nginx:9000/api/check');
</script>
