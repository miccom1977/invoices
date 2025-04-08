<script setup>
import { computed } from 'vue'
import { useModalStore } from '@/stores/useModalStore'
import InvoiceFormModal from "@/components/modals/InvoiceFormModal.vue";
import InvoiceDeleteModal from "@/components/modals/InvoiceDeleteModal.vue";
import InvoiceDetailsModal from "@/components/modals/InvoiceDetailsModal.vue";
import LoginModal from "@/components/modals/LoginModal.vue";

const modal = useModalStore()

const componentMap = {
    add: InvoiceFormModal,
    edit: InvoiceFormModal,
    delete: InvoiceDeleteModal,
    show: InvoiceDetailsModal,
    login: LoginModal,
}

const modalName = computed(() => modal.name)
const modalProps = computed(() => modal.props)
const component = computed(() => componentMap[modal.name])
const close = () => modal.close()
</script>

<template>
    <div v-if="modalName" class="modal-backdrop" @click.self="close">
        <component
            :is="component"
            v-bind="modalProps"
            :mode="modalName"
            @updated="$emit('updated', $event)"
        />
    </div>
</template>

<style scoped>
.modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1050;
}

.modal-window {
    background: white;
    border-radius: 8px;
    max-width: 60vw;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    padding: 2rem;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
}

.modal-content {
    width: 30%;
}
</style>
