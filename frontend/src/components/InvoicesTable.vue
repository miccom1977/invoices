<script setup>
import { useModalStore } from '@/stores/useModalStore'
import { useAuthStore } from '@/stores/useAuthStore'
import { Tippy } from 'vue-tippy'
import { storeToRefs } from "pinia";

const auth = useAuthStore()
const { isLoggedIn } = storeToRefs(auth)
const modal = useModalStore()

defineProps({
    invoices: Array
})
</script>
<template>
    <table v-if="invoices.length" class="table table-bordered table-hover">
        <thead class="table-light">
        <tr>
            <th>Nr faktury</th>
            <th>NIP kupującego</th>
            <th>NIP sprzedającego</th>
            <th>Nazwa produktu</th>
            <th>Kwota netto</th>
            <th>Data wystawienia</th>
            <th>Data edycji</th>
            <th>Funkcje</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="invoice in invoices" :key="invoice.id">
            <td>{{ invoice.number }}</td>
            <td>{{ invoice.buyer_nip }}</td>
            <td>{{ invoice.seller_nip }}</td>
            <td>{{ invoice.product_name }}</td>
            <td>{{ invoice.net_amount }} zł</td>
            <td>{{ invoice.issue_date }}</td>
            <td>{{ invoice.updated_at }}</td>
            <td>
                <Tippy
                    :content="!isLoggedIn ? 'Zaloguj się, aby edytować' : ''"
                    theme="light"
                    arrow
                >
                    <button
                        class="btn btn-sm btn-primary me-1"
                        :disabled="!isLoggedIn"
                        @click="isLoggedIn && modal.open('edit', { invoice })">
                        Edytuj
                    </button>
                </Tippy>

                <Tippy
                    :content="!isLoggedIn ? 'Zaloguj się, aby usunąć' : ''"
                    theme="light"
                    arrow
                >
                    <button
                        class="btn btn-sm btn-danger me-1"
                        :disabled="!isLoggedIn"
                        @click="isLoggedIn && modal.open('delete', { invoice })">
                        Usuń
                    </button>
                </Tippy>
                <button
                    class="btn btn-sm btn-secondary"
                    @click="modal.open('show', { invoice })">
                    Szczegóły
                </button>
            </td>
        </tr>
        </tbody>
    </table>

    <div v-else class="alert alert-info">Brak faktur do wyświetlenia.</div>
</template>
