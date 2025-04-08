<script setup>
import { useModalStore } from '@/stores/useModalStore'

const modal = useModalStore()
const props = defineProps({
    invoice: Object
})
</script>
<template>
    <div class="modal-content p-4 bg-white rounded">
        <h5 class="mb-3">Szczegóły faktury</h5>
        <ul class="list-group">
            <li class="list-group-item"><strong>Nr faktury:</strong> {{ props.invoice.number }}</li>
            <li class="list-group-item"><strong>NIP kupującego:</strong> {{ props.invoice.buyer_nip }}</li>
            <li class="list-group-item"><strong>NIP sprzedającego:</strong> {{ props.invoice.seller_nip }}</li>
            <li class="list-group-item"><strong>Produkt:</strong> {{ props.invoice.product_name }}</li>
            <li class="list-group-item"><strong>Kwota netto:</strong> {{ props.invoice.net_amount }} zł</li>
            <li class="list-group-item"><strong>Data wystawienia:</strong> {{ props.invoice.issue_date }}</li>
            <li class="list-group-item"><strong>Data edycji:</strong> {{ props.invoice.updated_at }}</li>
        </ul>
        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-secondary" @click="modal.close()">Zamknij</button>
        </div>
    </div>
</template>
