<template>
    <div>
        <div>
            <NotificationItem
                v-for="(notification, key) in notifications"
                :key="key"
                :title="notification.title"
                :message="notification.message"
            />
        </div>
        <Pagination />
    </div>
</template>

<script setup lang="ts">
interface NotificationInfo {
    title: string,
    message: string
}
const notifications: NotificationInfo[] = [
    {
        title: "田中です！",
        message: "フレンド申請よろしくお願い致します！"
    },
    {
        title: "佐藤です！",
        message: "フレンド申請しろよ"
    },
    {
        title: "横井です！",
        message: "フレンド申請よろしくお願い致します！",
    },
]
</script>
