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
                <input type="text" aria-label="パスワード" v-model="form.password">
            </div>
            <button type="submit" @click.prevent="submit">送信</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import axios from "axios";

interface FormData {
    email: string,
    password: string
}

const form:FormData = {
    email: 'yokoi4522-test1@gmail.com',
    password: 'test'
};

const submit = async () => {
    await axios.post('http://localhost:9000/api/login', form);
}

</script>

