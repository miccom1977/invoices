<script setup>
defineProps({
    pagination: Object
})

const emit = defineEmits(['page-changed'])

const changePage = (url) => {
    if (url) emit('page-changed', url)
}
</script>
<template>
    <nav v-if="pagination.last_page > 1">
        <ul class="pagination justify-content-center">
            <li class="page-item" :class="{ disabled: !pagination.prev_page_url }">
                <button class="page-link" @click="changePage(pagination.prev_page_url)">
                    &laquo;
                </button>
            </li>
            <li v-for="link in pagination.links" :key="link.label" class="page-item" :class="{ active: link.active, disabled: !link.url }">
                <button
                    class="page-link"
                    @click="changePage(link.url)"
                    v-html="link.label"
                />
            </li>
            <li class="page-item" :class="{ disabled: !pagination.next_page_url }">
                <button class="page-link" @click="changePage(pagination.next_page_url)">
                    &raquo;
                </button>
            </li>
        </ul>
    </nav>
</template>
