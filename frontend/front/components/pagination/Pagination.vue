<template>
    <div class="page">
        <ul>
            <li v-for="n in 5">
                {{ n }}
            </li>
        </ul>
        <div>
            <p>&<</p>
            <p>{{this.currentPage}}</p>
            <p>&></p>
        </div>
    </div>
</template>

<script lang="ts">
interface PageInfo{
    currentPage: number
}
export default {
    data(): PageInfo{
        return{
            currentPage: 1
        }
    }
}
</script>
