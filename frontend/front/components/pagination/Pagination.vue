<template>
    <div class="page">
        <ul>
            <li v-for="n in 5" :key="n">
                {{ n }}
            </li>
        </ul>
        <div>
            <p>&lt;</p>
            <p>{{ data.currentPage }}</p>
            <p>&gt;</p>
        </div>
    </div>
</template>

<script setup lang="ts">
interface PageInfo {
    currentPage: number
}

const data: PageInfo = {
    currentPage: 1
};
</script>
