<template>
    <div>
        <p>送信日:2022.02.12</p>
        <h2>{{ props.title }}</h2>
        <p>{{ props.message }}</p>
    </div>
</template>

<script setup lang="ts">
interface Props{
    title:string,
    message:string
}
const props: Props = withDefaults(defineProps(),{
    title: "",
    message: "",
})
</script>
