<script setup>
import { ref, onMounted } from 'vue'
import API from '@/utils/axios'
import { storeToRefs } from 'pinia'
import { useModalStore } from '@/stores/useModalStore'
import { useAuthStore } from '@/stores/useAuthStore'
import { Tippy } from 'vue-tippy'
import InvoicesTable from '@/components/InvoicesTable.vue'
import Modal from '@/components/modals/BaseModal.vue'
import PaginatorComponent from '@/components/PaginatorComponent.vue'

const invoices = ref([])
const pagination = ref(null)

const auth = useAuthStore()
const { isLoggedIn, user } = storeToRefs(auth)

const modal = useModalStore()

const fetchInvoices = (url = '/invoices') => {
    API.get(url)
        .then(response => {
            invoices.value = response.data.data
            pagination.value = response.data
        })
        .catch(() => {
            invoices.value = []
            pagination.value = null
        })
}
onMounted(fetchInvoices)
</script>

<template>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">Lista faktur</h1>
            <div>
                <Tippy
                    :content="!isLoggedIn ? 'Zaloguj się, aby usunąć' : ''"
                    theme="light"
                    arrow
                >
                    <button
                        class="btn btn-primary me-2"
                        :disabled="!isLoggedIn"
                        @click="isLoggedIn && modal.open('add')">
                        Dodaj fakturę
                    </button>
                </Tippy>
                <button v-if="!isLoggedIn" class="btn btn-outline-primary" @click="modal.open('login')">
                    Zaloguj się
                </button>

                <button v-else class="btn btn-outline-danger" @click="auth.logout()">
                    Wyloguj
                </button>
                <p v-if="isLoggedIn" class="text-muted small">zalogowany {{ user.name }}</p>
            </div>
        </div>
        <InvoicesTable :invoices="invoices" />
        <PaginatorComponent
            v-if="pagination"
            :pagination="pagination"
            @page-changed="fetchInvoices"
        />
        <Modal @updated="fetchInvoices" />
    </div>
</template>
