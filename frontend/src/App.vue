<template>
    <div v-if="!loading">
        <router-view />
    </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/useAuthStore'
import { onMounted } from 'vue'
import { getToken } from '@/utils/token'
import { storeToRefs } from "pinia";

const auth = useAuthStore()
const { loading } = storeToRefs(auth)

onMounted(() => {
    if (getToken()) {
        auth.fetchUser()
    } else {
        auth.loading = false
    }
})
</script>
