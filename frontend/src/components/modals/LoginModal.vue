<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/useAuthStore'
import { useModalStore } from '@/stores/useModalStore'
import { useToast } from 'vue-toast-notification'
import API from '@/utils/axios'

const auth = useAuthStore()
const modal = useModalStore()
const toast = useToast()

const email = ref('')
const password = ref('')

// realizujemy wysyłkę danych do endopint login, jeśli logowanie
// jest udane- aktualizujemy dane, zapisujemy token odebrany z backendu
const submit = () => {
    API.post('/login', { email: email.value, password: password.value })
        .then((response) => {
            auth.setUser(response.data.user, response.data.token)
            toast.success('Zalogowano pomyślnie')
            modal.close()
        })
        .catch(() => {
            toast.error('Błędny login lub hasło')
        })
}
</script>
<template>
    <div class="modal-content p-4 bg-white rounded" style="width: 300px;">
        <h5 class="mb-3">Logowanie</h5>
        <form @submit.prevent="submit">
            <div class="mb-2">
                <label>Email</label>
                <input v-model="email" type="email" class="form-control" required />
            </div>
            <div class="mb-2">
                <label>Hasło</label>
                <input v-model="password" type="password" class="form-control" required />
            </div>
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button class="btn btn-secondary" @click="modal.close()">Anuluj</button>
                <button class="btn btn-primary" type="submit">Zaloguj się</button>
            </div>
        </form>
    </div>
</template>
