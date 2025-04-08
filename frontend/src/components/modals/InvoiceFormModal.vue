<script setup>
import { reactive, watch } from 'vue'
import { useModalStore } from '@/stores/useModalStore'
import { useToast } from 'vue-toast-notification'
import API from '@/utils/axios'

const toast = useToast()
const modal = useModalStore()
const validationErrors = reactive({})

const emit = defineEmits(['updated'])

const props = defineProps({
    invoice: Object,
    mode: String
})

const form = reactive({
    number: '',
    buyer_nip: '',
    seller_nip: '',
    product_name: '',
    net_amount: '',
    issue_date: '',
    updated_at: ''
})

// nasłuchujemy, czy jesteśmy w trybie edycji, jeśli tak- uzupełniamy form danymi faktury invoice
watch(
    () => props.invoice,
    (invoice) => {
        if (props.mode === 'edit' && invoice) {
            Object.assign(form, invoice)
        }
    },
    { immediate: true }
)

// Obsługa wysyłki danych z formularza do backendu (edycja i tworzenie faktury)
// aktualizacja danych walidacji
const submitForm = () => {
    Object.keys(validationErrors).forEach(key => delete validationErrors[key])

    const method = props.mode === 'edit' ? 'put' : 'post'
    const url = props.mode === 'edit'
        ? `/invoices/${props.invoice.id}`
        : '/invoices'

    API[method](url, form)
        .then((response) => {
            toast.success(response.data.message || 'Sukces!')
            emit('updated')
            modal.close()
        })
        .catch((error) => {
                if (error.response?.status === 422) {
                    Object.assign(validationErrors, error.response.data.errors)
                } else {
                    toast.error(error.response?.data?.error || 'Coś poszło nie tak')
                }
                console.error(error)
            }
        )
}
</script>
<template>
    <div class="modal-content p-4 bg-white rounded">
        <h5 class="mb-4">{{ mode === 'edit' ? 'Edytuj fakturę' : 'Dodaj fakturę' }}</h5>

        <form @submit.prevent="submitForm">
            <table class="table table-borderless align-middle">
                <tbody>
                <tr>
                    <th scope="row" class="text-end w-50">Nr faktury</th>
                    <td>
                        <input v-model="form.number" class="form-control" required />
                        <div v-if="validationErrors.number" class="text-danger small">
                            {{ validationErrors.number[0] }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="text-end">NIP kupującego</th>
                    <td>
                        <input v-model="form.buyer_nip" class="form-control" required />
                        <div v-if="validationErrors.buyer_nip" class="text-danger small">
                            {{ validationErrors.buyer_nip[0] }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="text-end">NIP sprzedającego</th>
                    <td>
                        <input v-model="form.seller_nip" class="form-control" required />
                        <div v-if="validationErrors.seller_nip" class="text-danger small">
                            {{ validationErrors.seller_nip[0] }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="text-end">Produkt</th>
                    <td>
                        <input v-model="form.product_name" class="form-control" required />
                        <div v-if="validationErrors.product_name" class="text-danger small">
                            {{ validationErrors.product_name[0] }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="text-end">Kwota netto</th>
                    <td>
                        <input v-model="form.net_amount" type="number" step="0.01" class="form-control" required />
                        <div v-if="validationErrors.net_amount" class="text-danger small">
                            {{ validationErrors.net_amount[0] }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="text-end">Data wystawienia</th>
                    <td>
                        <input v-model="form.issue_date" type="date" class="form-control" required />
                        <div v-if="validationErrors.issue_date" class="text-danger small">
                            {{ validationErrors.issue_date[0] }}
                        </div>
                    </td>
                </tr>
                <tr v-if="form.updated_at">
                    <th scope="row" class="text-end">Data edycji</th>
                    <td><input readonly v-model="form.updated_at" type="date" class="form-control"/></td>
                </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3 gap-2">
                <button type="button" class="btn btn-secondary" @click="modal.close()">Anuluj</button>
                <button type="submit" class="btn btn-primary">Zapisz</button>
            </div>
        </form>
    </div>
</template>
