<script setup>
import API from '@/utils/axios'
import { useModalStore } from '@/stores/useModalStore'
import { useToast } from 'vue-toast-notification'
const toast = useToast()

const modal = useModalStore()

const props = defineProps({
    invoice: Object
})

const emit = defineEmits(['updated'])

const remove = () => {
    API.delete(`/invoices/${props.invoice.id}`)
        .then((response) => {
            emit('updated')
            toast.success(response.data.message || 'Sukces!')
            modal.close()
        })
        .catch((error) => {
            console.error(error)
            toast.error(error.data.error || 'Coś poszło nie tak')
        })
}
</script>
<template>
    <div class="modal-content p-4 bg-white rounded">
        <h5>Potwierdzenie usunięcia</h5>
        <p>Czy na pewno chcesz usunąć fakturę <strong>{{ invoice.number }}</strong>?</p>
        <div class="d-flex justify-content-end gap-2 mt-3">
            <button class="btn btn-secondary" @click="modal.close()">Anuluj</button>
            <button class="btn btn-danger" @click="remove">Usuń</button>
        </div>
    </div>
</template>
