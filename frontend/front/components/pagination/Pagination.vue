<template>
    <div class="page">
        <ul>
            <li v-for="n in 5" :key="n">
                {{ n }}
            </li>
        </ul>
        <div>
            <p>&lt;</p>
            <p>{{ this.currentPage }}</p>
            <p>&gt;</p>
        </div>
    </div>
</template>

<script lang="ts">
interface PageInfo {
    currentPage: number
}

export default {
    data(): PageInfo {
        return {
            currentPage: 1
        }
    }
}
</script>
