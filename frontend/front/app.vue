<template>
  <div>
    <p>{{data}}</p>
  </div>
</template>

<script setup>
    const {data} = await useFetch('http://localhost:9000/api/check');
</script>
