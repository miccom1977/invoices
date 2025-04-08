import { defineStore } from 'pinia'
import { ref, computed, watch } from 'vue'
import API from '@/utils/axios'
import { getToken, removeToken, saveToken } from '@/utils/token'

export const useAuthStore = defineStore('auth', () => {
	const user = ref(null)
	const token = ref(getToken())
	const loading = ref(true)
	
	const isLoggedIn = computed(() => !!token.value && !!user.value)
	
	const setUser = (user, token) => {
		token.value = token
		user.value = user
		saveToken(token)
	}
	
	const fetchUser = async () => {
		loading.value = true
		try {
			const response = await API.get('/ping')
			user.value = response.data
		} catch (error) {
			console.warn('Błąd przy aktualizacji danych usera', error)
			token.value = null
			user.value = null
			removeToken()
		} finally {
			loading.value = false
		}
	}
	
	const logout = async () => {
		try {
			await API.post('/logout')
		} catch (error) {
			console.warn('Błąd przy wylogowaniu', error)
		} finally {
			token.value = null
			user.value = null
			removeToken()
		}
	}
	
	watch(token, (newVal) => {
		if (newVal) {
			saveToken(newVal)
		} else {
			removeToken()
		}
	})
	
	return {
		user,
		token,
		loading,
		isLoggedIn,
		setUser,
		fetchUser,
		logout
	}
})
