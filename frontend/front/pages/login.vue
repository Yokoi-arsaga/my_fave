<template>
    <div>
        <h2>ログイン</h2>
        <form>
            <div class="form-group">
                <label>Eメール</label>
                <input type="text" aria-label="Eメール" v-model="form.email">
            </div>
            <div class="form-group">
                <label>パスワード</label>
                <input type="text" aria-label="Eメール" v-model="form.password">
            </div>
            <button type="submit" @click.prevent="submit">送信</button>
        </form>
    </div>
</template>

<script setup lang="ts">
interface FormData {
    email: string,
    password: string
}

const form:FormData = {
    email: 'example.com',
    password: 'secret'
};
</script>

