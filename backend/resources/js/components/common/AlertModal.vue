<template>
  <div v-if="show" class="fixed inset-0 z-[100] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-md text-center">
      <!-- Overlay -->
      <div class="fixed inset-0 bg-black opacity-40" @click="$emit('close')"></div>

      <!-- Modal Content -->
      <div class="relative bg-white rounded-lg overflow-hidden shadow-xl transform transition-all w-[40rem] max-w-[90%] z-[101]">
        <div class="px-md pt-md pb-sm">
          <!-- Header -->
          <div class="flex items-center justify-between mb-md">
            <div class="flex items-center">
              <span class="material-symbols-outlined text-red-500 mr-sm">error</span>
              <h3 class="text-lg font-medium text-gray-900">{{ title }}</h3>
            </div>
            <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600">
              <span class="material-symbols-outlined">close</span>
            </button>
          </div>

          <!-- Message -->
          <div class="mb-md">
            <p class="text-sm text-gray-600">{{ message }}</p>
          </div>

          <!-- Actions -->
          <div class="flex justify-end space-x-sm">
            <button
              @click="$emit('close')"
              class="px-md py-sm bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 text-sm font-medium"
            >
              Đóng
            </button>
            <button
              v-if="showConfirm"
              @click="$emit('confirm')"
              class="px-md py-sm bg-red-600 text-white rounded-full hover:bg-red-700 text-sm font-medium"
            >
              Xác nhận
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  show: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: 'Thông báo'
  },
  message: {
    type: String,
    required: true
  },
  showConfirm: {
    type: Boolean,
    default: false
  }
});

defineEmits(['close', 'confirm']);
</script>

<style scoped>
.material-symbols-outlined {
  font-variation-settings:
  'FILL' 0,
  'wght' 400,
  'GRAD' 0,
  'opsz' 24;
}
</style> 