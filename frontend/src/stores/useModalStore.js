import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useModalStore = defineStore('modal', () => {
	const name = ref(null)
	const props = ref({})
	
	const open = (modalName, modalProps = {}) => {
		name.value = modalName
		props.value = modalProps
	}
	
	const close = () => {
		name.value = null
		props.value = {}
	}
	
	return { name, props, open, close }
})
