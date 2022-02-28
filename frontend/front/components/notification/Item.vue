<template>
    <div>
        <p>送信日:2022.02.12</p>
        <h2>{{ title }}</h2>
        <p>{{ message }}</p>
    </div>
</template>

<script setup lang="ts">

interface Props {
    title: string,
    message: string
}

const {title, message} = defineProps<Props>();

</script>
