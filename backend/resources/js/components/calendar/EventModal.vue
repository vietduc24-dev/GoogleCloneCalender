<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 text-center">
      <!-- Overlay -->
      <div class="fixed inset-0 bg-black opacity-40" @click="closeModal"></div>

      <!-- Modal Content -->
      <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-xl w-full z-50">
        <form @submit.prevent="handleSubmit">
          <div class="px-6 pt-6 pb-4">
            <div class="flex items-center justify-between mb-4 -mx-6 -mt-6 px-6 py-3 bg-gray-50">
              <div class="flex items-center">
                <span class="material-symbols-outlined text-gray-500 cursor-pointer">menu</span>
              </div>
              <button type="button" @click="closeModal" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined">close</span>
              </button>
            </div>

            <!-- Title -->
            <input
              type="text"
              v-model="formData.title"
              :placeholder="isEvent ? 'Thêm tiêu đề và thời gian' : 'Thêm tiêu đề cho lời nhắc'"
              class="w-full border-0 border-b border-gray-200 p-2 text-lg focus:outline-none focus:border-blue-500 mb-6 placeholder-gray-400"
              required
            />

            <!-- Toggle Type -->
            <div v-if="!isEditing" class="flex space-x-2 mb-6">
              <button 
                type="button" 
                :class="[
                  'px-4 py-1.5 rounded-full text-sm font-medium transition-colors',
                  isEvent ? 'bg-blue-100 text-blue-700' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'
                ]"
                @click="setType('event')"
              >
                Sự kiện
              </button>
              <button 
                type="button" 
                :class="[
                  'px-4 py-1.5 rounded-full text-sm font-medium transition-colors',
                  !isEvent ? 'bg-blue-100 text-blue-700' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'
                ]"
                @click="setType('reminder')"
              >
                Lời nhắc
              </button>
            </div>
            <!-- Type Label when editing -->
            <div v-else class="mb-6 text-gray-600 text-xl font-medium text-left">
              {{ isEvent ? 'Sự kiện' : 'Lời nhắc' }}
            </div>

            <!-- Event Form -->
            <div v-if="isEvent" class="space-y-4 mb-6">
              <div class="flex items-center">
                <span class="material-symbols-outlined text-gray-500 mr-4">schedule</span>
                <div class="flex-1">
                  <div class="flex items-center space-x-2 mb-2">
                    <input 
                      type="datetime-local" 
                      v-model="formData.start" 
                      class="flex-1 border rounded px-3 py-2 focus:outline-none focus:border-blue-500" 
                      required 
                    />
                    <span class="text-gray-500">-</span>
                    <input 
                      type="datetime-local" 
                      v-model="formData.end" 
                      class="flex-1 border rounded px-3 py-2 focus:outline-none focus:border-blue-500" 
                      required 
                    />
                  </div>
                  <label class="inline-flex items-center">
                    <input 
                      type="checkbox" 
                      v-model="formData.all_day" 
                      class="form-checkbox text-blue-600"
                    />
                    <span class="ml-2 text-sm text-gray-600">Cả ngày</span>
                  </label>
                </div>
              </div>

              <!-- Color and Location -->
              <div class="flex items-center">
                <span class="material-symbols-outlined text-gray-500 mr-4">palette</span>
                <div class="flex items-center justify-between flex-1">
                  <div class="relative color-picker">
                    <button 
                      type="button"
                      @click.stop="toggleColorPicker"
                      class="w-6 h-6 rounded-full border shadow-sm flex items-center justify-center hover:shadow-md transition-shadow"
                      :style="{ backgroundColor: formData.color }"
                    >
                      <span v-if="!showColorPicker" class="material-symbols-outlined text-white">check</span>
                    </button>
                    
                    <!-- Color Picker Dropdown -->
                    <div 
                      v-if="showColorPicker" 
                      class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 bg-white border rounded-lg shadow-lg p-3 grid grid-cols-2 gap-3 z-50 w-[100px]"
                      @click.stop
                    >
                      <button
                        v-for="color in colors"
                        :key="color.value"
                        type="button"
                        class="w-6 h-6 rounded-full border hover:opacity-80 transition-all hover:scale-110 hover:shadow-md flex items-center justify-center relative group"
                        :style="{ backgroundColor: color.value }"
                        :title="color.name"
                        @click="selectColor(color)"
                      >
                        <span v-if="formData.color === color.value" class="material-symbols-outlined text-white text-xs">check</span>
                      </button>
                    </div>
                  </div>
                  
                  <div class="flex-1 ml-4">
                    <div class="flex items-center">
                      <span class="material-symbols-outlined text-gray-500 mr-2">location_on</span>
                      <input
                        type="text"
                        v-model="formData.location"
                        placeholder="Thêm địa điểm"
                        class="w-full border-0 border-b border-gray-200 p-2 text-sm focus:outline-none focus:border-blue-500 placeholder-gray-400"
                      />
                    </div>
                  </div>
                </div>
              </div>

              <!-- Description -->
              <div class="flex items-start">
                <span class="material-symbols-outlined text-gray-500 mr-4 mt-2">description</span>
                <textarea
                  v-model="formData.description"
                  placeholder="Thêm mô tả hoặc tệp đính kèm"
                  class="flex-1 border rounded p-2 text-sm resize-none focus:outline-none focus:border-blue-500 placeholder-gray-400"
                  rows="3"
                ></textarea>
              </div>
            </div>

            <!-- Reminder Form -->
            <div v-else class="space-y-4 mb-6">
              <div class="flex items-center">
                <span class="material-symbols-outlined text-gray-500 mr-4">schedule</span>
                <div class="flex-1">
                  <input 
                    type="datetime-local" 
                    v-model="formData.reminder_time" 
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500" 
                    required 
                  />
                </div>
              </div>

              <!-- Color Picker for Reminder -->
              <div class="flex items-center">
                <span class="material-symbols-outlined text-gray-500 mr-4">palette</span>
                <div class="flex items-center justify-between flex-1">
                  <div class="relative color-picker">
                    <button 
                      type="button"
                      @click.stop="toggleColorPicker"
                      class="w-6 h-6 rounded-full border shadow-sm flex items-center justify-center hover:shadow-md transition-shadow"
                      :style="{ backgroundColor: formData.color }"
                    >
                      <span v-if="!showColorPicker" class="material-symbols-outlined text-white">check</span>
                    </button>
                    
                    <!-- Color Picker Dropdown -->
                    <div 
                      v-if="showColorPicker" 
                      class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 bg-white border rounded-lg shadow-lg p-3 grid grid-cols-2 gap-3 z-50 w-[100px]"
                      @click.stop
                    >
                      <button
                        v-for="color in colors"
                        :key="color.value"
                        type="button"
                        class="w-6 h-6 rounded-full border hover:opacity-80 transition-all hover:scale-110 hover:shadow-md flex items-center justify-center relative group"
                        :style="{ backgroundColor: color.value }"
                        :title="color.name"
                        @click="selectColor(color)"
                      >
                        <span v-if="formData.color === color.value" class="material-symbols-outlined text-white text-xs">check</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Notification Method -->
              <div class="flex items-center">
                <span class="material-symbols-outlined text-gray-500 mr-4">notifications</span>
                <div class="flex-1">
                  <select 
                    v-model="formData.method"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 text-gray-700"
                  >
                    <option value="notification">Thông báo</option>
                    <option value="email">Email</option>
                    <option value="both">Cả hai</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="bg-gray-50 px-6 py-3 flex justify-between items-center">
            <button
              v-if="isEditing"
              type="button"
              @click="handleDelete"
              class="px-4 py-2 bg-red-50 text-red-600 rounded-full hover:bg-red-100 font-medium text-sm"
            >
              Xoá
            </button>
            <div class="ml-auto space-x-2">
              <button
                type="button"
                @click="closeModal"
                class="px-6 py-2 text-gray-700 hover:bg-gray-100 rounded-full text-sm font-medium"
              >
                Hủy
              </button>
              <button
                type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 text-sm font-medium"
              >
                {{ isEditing ? 'Cập nhật' : 'Lưu' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { formatDate, formatDateForInput, utcToLocal, localToUtc } from '../../utils/fortmatdatetime';

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  event: {
    type: Object,
    default: null
  },
  defaultDate: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['close', 'submit', 'delete']);

const isEditing = ref(false);
const showColorPicker = ref(false);
const isEvent = ref(true);

const colors = [
  { name: 'Xanh da trời', value: '#4285F4' },
  { name: 'Đỏ', value: '#EA4335' },
  { name: 'Xanh lá', value: '#34A853' },
  { name: 'Vàng', value: '#FBBC05' },
  { name: 'Tím', value: '#A142F4' },
  { name: 'Cam', value: '#FF7F40' },
  { name: 'Hồng', value: '#FF69B4' },
  { name: 'Nâu', value: '#8B4513' },
  { name: 'Xám', value: '#9E9E9E' },
  { name: 'Xanh dương đậm', value: '#1A73E8' }
];

const formData = ref({
  title: '',
  start: '',
  end: '',
  location: '',
  description: '',
  all_day: false,
  color: '#4285F4',
  reminder_time: '',
  method: 'notification'
});

const selectColor = (color) => {
  formData.value.color = color.value;
  showColorPicker.value = false;
};

// Reset form to initial state
const resetForm = () => {
  formData.value = {
    title: '',
    start: '',
    end: '',
    location: '',
    description: '',
    all_day: false,
    color: '#4285F4',
    reminder_time: '',
    method: 'notification'
  };
  showColorPicker.value = false;
  isEvent.value = true;
};

// Watch for changes in show prop
watch(() => props.show, (newShow) => {
  if (!newShow) {
    resetForm();
    isEditing.value = false;
  }
});

// Watch for changes in event and defaultDate props
watch([() => props.event, () => props.defaultDate], ([newEvent, newDefaultDate]) => {
  if (newEvent) {
    isEditing.value = true;
    // Check if it's a reminder by looking at the type
    if (newEvent.type === 'reminder') {
      isEvent.value = false;
      formData.value = {
        title: newEvent.title?.replace('🔔 ', '') || '', // Remove bell emoji if present
        reminder_time: formatDateForInput(newEvent.reminder_time),
        method: newEvent.method || 'notification',
        color: newEvent.color || '#4285F4',
        // Reset event fields
        start: '',
        end: '',
        location: '',
        description: '',
        all_day: false
      };
    } else {
      isEvent.value = true;
      formData.value = {
        title: newEvent.title || '',
        start: formatDateForInput(newEvent.start),
        end: formatDateForInput(newEvent.end),
        location: newEvent.location || '',
        description: newEvent.description || '',
        all_day: newEvent.allDay || false,
        color: newEvent.color || '#4285F4',
        // Reset reminder fields
        reminder_time: '',
        method: 'notification'
      };
    }
  } else if (newDefaultDate) {
    isEditing.value = false;
    formData.value = {
      title: '',
      start: formatDateForInput(newDefaultDate.start),
      end: formatDateForInput(newDefaultDate.end),
      location: '',
      description: '',
      all_day: newDefaultDate.allDay || false,
      color: '#4285F4',
      reminder_time: formatDateForInput(newDefaultDate.start),
      method: 'notification'
    };
  } else {
    resetForm();
    isEditing.value = false;
  }
}, { immediate: true });

const closeModal = () => {
  resetForm();
  emit('close');
};

const handleSubmit = () => {
  if (isEvent.value) {
    // Handle event submission
    const startDate = new Date(formData.value.start);
    const endDate = new Date(formData.value.end);

    if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) {
      alert('Vui lòng nhập ngày giờ hợp lệ');
      return;
    }

    if (endDate < startDate) {
      alert('Thời gian kết thúc phải sau thời gian bắt đầu');
      return;
    }

    emit('submit', {
      type: 'event',
      data: {
        title: formData.value.title.trim(),
        start: startDate,
        end: endDate,
        location: formData.value.location?.trim() || '',
        description: formData.value.description?.trim() || '',
        all_day: formData.value.all_day,
        color: formData.value.color
      }
    });
  } else {
    // Handle reminder submission
    const reminderTime = new Date(formData.value.reminder_time);

    if (isNaN(reminderTime.getTime())) {
      alert('Vui lòng nhập thời gian hợp lệ');
      return;
    }

    emit('submit', {
      type: 'reminder',
      data: {
        title: formData.value.title.trim(),
        reminder_time: reminderTime,
        method: formData.value.method,
        color: formData.value.color
      }
    });
  }
};

const toggleColorPicker = () => {
  showColorPicker.value = !showColorPicker.value;
};

// Close color picker when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest('.color-picker')) {
    showColorPicker.value = false;
  }
};

const setType = (type) => {
  isEvent.value = type === 'event';
};

const handleDelete = () => {
  const itemType = isEvent.value ? 'sự kiện' : 'lời nhắc';
  if (confirm(`Bạn có chắc chắn muốn xóa ${itemType} này không?`)) {
    emit('submit', {
      action: 'delete',
      type: isEvent.value ? 'event' : 'reminder'
    });
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
.modal-overlay {
  background-color: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(2px);
}

.modal-container {
  max-width: 500px;
  width: 90%;
  max-height: 90vh;
  border-radius: 8px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.modal-header {
  border-bottom: 1px solid #e5e7eb;
}

.modal-footer {
  border-top: 1px solid #e5e7eb;
}

input[type="datetime-local"] {
  color-scheme: light;
}

.form-checkbox {
  appearance: none;
  width: 1rem;
  height: 1rem;
  border: 1px solid #d1d5db;
  border-radius: 0.25rem;
  background-color: #fff;
  cursor: pointer;
}

.form-checkbox:checked {
  background-color: #2563eb;
  border-color: #2563eb;
  background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
  background-size: 100% 100%;
  background-position: center;
  background-repeat: no-repeat;
}

.form-checkbox:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
}

.color-picker button {
  transition: transform 0.15s ease-in-out;
}

.color-picker button:hover {
  transform: scale(1.1);
}

input:focus, textarea:focus {
  box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
}

.material-symbols-outlined {
  font-variation-settings:
  'FILL' 0,
  'wght' 400,
  'GRAD' 0,
  'opsz' 24
}
</style> 