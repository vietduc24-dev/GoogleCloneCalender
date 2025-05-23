<template>
  <div>
    <AlertModal
      :show="showAlert"
      :title="alertTitle"
      :message="alertMessage"
      :show-confirm="showConfirmButton"
      @close="closeAlert"
      @confirm="handleAlertConfirm"
    />
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen px-md text-center">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black opacity-40" @click="closeModal"></div>

        <!-- Modal Content -->
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all w-[55rem] max-w-[90%] z-50">
          <form @submit.prevent="handleSubmit">
            <div class="px-md pt-md pb-sm">
              <div class="flex items-center justify-between mb-sm -mx-md -mt-md px-md py-sm bg-gray-50">
                <div class="flex items-center">
                  <span class="material-symbols-outlined text-gray-500 cursor-pointer">menu</span>
                </div>
                <button type="button" @click="closeModal" class="text-gray-400 hover:text-gray-600">
                  <span class="material-symbols-outlined">close</span>
                </button>
              </div>

              <!-- Title -->
              <div class="mb-md">
                <input
                  type="text"
                  v-model="formData.title"
                  :placeholder="isEvent ? 'Thêm tiêu đề và thời gian' : 'Thêm tiêu đề cho lời nhắc'"
                  :class="[
                    'w-full border-0 border-b p-sm text-lg focus:outline-none placeholder-gray-400',
                    errors.title ? 'border-red-500 focus:border-red-500' : 'border-gray-200 focus:border-blue-500'
                  ]"
                  maxlength="255"
                  required
                />
                <div class="h-[2rem] mt-xs">
                  <span v-if="errors.title" class="text-sm text-red-600">{{ errors.title }}</span>
                </div>
              </div>

              <!-- Toggle Type -->
              <div v-if="!isEditing" class="flex space-x-sm mb-md">
                <button 
                  type="button" 
                  :class="[
                    'px-sm py-xs rounded-full text-sm font-medium transition-colors',
                    isEvent ? 'bg-blue-100 text-blue-700' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'
                  ]"
                  @click="setType('event')"
                >
                  Sự kiện
                </button>
                <button 
                  type="button" 
                  :class="[
                    'px-sm py-xs rounded-full text-sm font-medium transition-colors',
                    !isEvent ? 'bg-blue-100 text-blue-700' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'
                  ]"
                  @click="setType('reminder')"
                >
                  Lời nhắc
                </button>
              </div>
              <!-- Type Label when editing -->
              <div v-else class="mb-md text-gray-600 text-xl font-medium text-left">
                {{ isEvent ? 'Sự kiện' : 'Lời nhắc' }}
              </div>

              <!-- Event Form -->
              <div v-if="isEvent" class="space-y-sm mb-md">
                <div class="flex items-center">
                  <span class="material-symbols-outlined text-gray-500 mr-sm">schedule</span>
                  <div class="flex-1">
                    <div class="flex items-center space-x-sm mb-sm">
                      <div class="flex-1">
                        <input 
                          type="datetime-local" 
                          v-model="formData.start" 
                          :class="[
                            'w-full border rounded px-sm py-sm focus:outline-none',
                            errors.start ? 'border-red-500 focus:border-red-500' : 'border-gray-200 focus:border-blue-500'
                          ]"
                          required 
                        />
                        <div class="h-[2rem] mt-xs">
                          <span v-if="errors.start" class="text-sm text-red-600">{{ errors.start }}</span>
                        </div>
                      </div>
                      <span class="text-gray-500">-</span>
                      <div class="flex-1">
                        <input 
                          type="datetime-local" 
                          v-model="formData.end" 
                          :class="[
                            'w-full border rounded px-sm py-sm focus:outline-none',
                            errors.end ? 'border-red-500 focus:border-red-500' : 'border-gray-200 focus:border-blue-500'
                          ]"
                          required 
                        />
                        <div class="h-[2rem] mt-xs">
                          <span v-if="errors.end" class="text-sm text-red-600">{{ errors.end }}</span>
                        </div>
                      </div>
                    </div>
                    <label class="inline-flex items-center">
                      <input 
                        type="checkbox" 
                        v-model="formData.all_day" 
                        class="form-checkbox text-blue-600"
                      />
                      <span class="ml-sm text-sm text-gray-600">Cả ngày</span>
                    </label>
                  </div>
                </div>

                <!-- Color and Location -->
                <div class="flex items-center">
                  <span class="material-symbols-outlined text-gray-500 mr-sm">palette</span>
                  <div class="flex items-center justify-between flex-1">
                    <div class="relative color-picker">
                      <button 
                        type="button"
                        @click.stop="toggleColorPicker"
                        class="w-[2.4rem] h-[2.4rem] rounded-full border shadow-sm flex items-center justify-center hover:shadow-md transition-shadow"
                        :style="{ backgroundColor: formData.color }"
                      >
                        <span v-if="!showColorPicker" class="material-symbols-outlined text-white">check</span>
                      </button>
                      
                      <!-- Color Picker Dropdown -->
                      <div 
                        v-if="showColorPicker" 
                        class="absolute top-full left-1/2 transform -translate-x-1/2 mt-sm bg-white border rounded-lg shadow-lg p-sm grid grid-cols-2 gap-sm z-50 w-[10rem]"
                        @click.stop
                      >
                        <button
                          v-for="color in colors"
                          :key="color.value"
                          type="button"
                          class="w-[2.4rem] h-[2.4rem] rounded-full border hover:opacity-80 transition-all hover:scale-110 hover:shadow-md flex items-center justify-center relative group"
                          :style="{ backgroundColor: color.value }"
                          :title="color.name"
                          @click="selectColor(color)"
                        >
                          <span v-if="formData.color === color.value" class="material-symbols-outlined text-white text-xs">check</span>
                        </button>
                      </div>
                    </div>
                    
                    <div class="flex-1 ml-sm">
                      <div class="flex items-center">
                        <span class="material-symbols-outlined text-gray-500 mr-sm">location_on</span>
                        <input
                          type="text"
                          v-model="formData.location"
                          placeholder="Thêm địa điểm"
                          class="w-full border-0 border-b border-gray-200 p-sm text-sm focus:outline-none focus:border-blue-500 placeholder-gray-400"
                        />
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Description -->
                <div class="flex items-start">
                  <span class="material-symbols-outlined text-gray-500 mr-sm mt-sm">description</span>
                  <textarea
                    v-model="formData.description"
                    placeholder="Thêm mô tả hoặc tệp đính kèm"
                    class="flex-1 border rounded p-sm text-sm resize-none focus:outline-none focus:border-blue-500 placeholder-gray-400"
                    rows="3"
                  ></textarea>
                </div>
              </div>

              <!-- Reminder Form -->
              <div v-else class="space-y-sm mb-md">
                <div class="flex items-center">
                  <span class="material-symbols-outlined text-gray-500 mr-sm">schedule</span>
                  <div class="flex-1">
                    <div>
                      <input 
                        type="datetime-local" 
                        v-model="formData.reminder_time" 
                        :class="[
                          'w-full border rounded px-sm py-sm focus:outline-none',
                          errors.reminder_time ? 'border-red-500 focus:border-red-500' : 'border-gray-200 focus:border-blue-500'
                        ]"
                        required 
                      />
                      <div class="h-[2rem] mt-xs">
                        <span v-if="errors.reminder_time" class="text-sm text-red-600">{{ errors.reminder_time }}</span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Color Picker for Reminder -->
                <div class="flex items-center">
                  <span class="material-symbols-outlined text-gray-500 mr-sm">palette</span>
                  <div class="flex items-center justify-between flex-1">
                    <div class="relative color-picker">
                      <button 
                        type="button"
                        @click.stop="toggleColorPicker"
                        class="w-[2.4rem] h-[2.4rem] rounded-full border shadow-sm flex items-center justify-center hover:shadow-md transition-shadow"
                        :style="{ backgroundColor: formData.color }"
                      >
                        <span v-if="!showColorPicker" class="material-symbols-outlined text-white">check</span>
                      </button>
                      
                      <!-- Color Picker Dropdown -->
                      <div 
                        v-if="showColorPicker" 
                        class="absolute top-full left-1/2 transform -translate-x-1/2 mt-sm bg-white border rounded-lg shadow-lg p-sm grid grid-cols-2 gap-sm z-50 w-[10rem]"
                        @click.stop
                      >
                        <button
                          v-for="color in colors"
                          :key="color.value"
                          type="button"
                          class="w-[2.4rem] h-[2.4rem] rounded-full border hover:opacity-80 transition-all hover:scale-110 hover:shadow-md flex items-center justify-center relative group"
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
                  <span class="material-symbols-outlined text-gray-500 mr-sm">notifications</span>
                  <div class="flex-1">
                    <select 
                      v-model="formData.method"
                      class="w-full border rounded px-sm py-sm focus:outline-none focus:border-blue-500 text-gray-700"
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
            <div class="bg-gray-50 px-md py-sm flex justify-between items-center">
              <button
                v-if="isEditing"
                type="button"
                @click="handleDelete"
                class="px-sm py-sm bg-red-50 text-red-600 rounded-full hover:bg-red-100 font-medium text-sm"
              >
                Xoá
              </button>
              <div class="ml-auto space-x-sm">
                <button
                  type="button"
                  @click="closeModal"
                  class="px-md py-sm text-gray-700 hover:bg-gray-100 rounded-full text-sm font-medium"
                >
                  Hủy
                </button>
                <button
                  type="submit"
                  class="px-md py-sm bg-blue-600 text-white rounded-full hover:bg-blue-700 text-sm font-medium"
                >
                  {{ isEditing ? 'Cập nhật' : 'Lưu' }}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { formatDate, formatDateForInput, utcToLocal, localToUtc } from '../../utils/fortmatdatetime';
import AlertModal from '../common/AlertModal.vue';

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

// Add error states
const errors = ref({
  title: '',
  start: '',
  end: '',
  reminder_time: ''
});

// Clear errors when form data changes
watch(() => formData.value, () => {
  errors.value = {
    title: '',
    start: '',
    end: '',
    reminder_time: ''
  };
}, { deep: true });

const validateEventForm = () => {
  let isValid = true;
  errors.value = {
    title: '',
    start: '',
    end: '',
    reminder_time: ''
  };

  // Check title length
  if (!formData.value.title.trim()) {
    errors.value.title = 'Vui lòng nhập tiêu đề';
    showValidationError('Lỗi nhập liệu', 'Vui lòng nhập tiêu đề');
    isValid = false;
  } else if (formData.value.title.length > 255) {
    errors.value.title = 'Tiêu đề không được vượt quá 255 ký tự';
    showValidationError('Lỗi nhập liệu', 'Tiêu đề không được vượt quá 255 ký tự');
    isValid = false;
  }

  const startDate = new Date(formData.value.start);
  const endDate = new Date(formData.value.end);

  if (isNaN(startDate.getTime())) {
    errors.value.start = 'Vui lòng nhập thời gian bắt đầu hợp lệ';
    showValidationError('Lỗi nhập liệu', 'Vui lòng nhập thời gian bắt đầu hợp lệ');
    isValid = false;
  }

  if (isNaN(endDate.getTime())) {
    errors.value.end = 'Vui lòng nhập thời gian kết thúc hợp lệ';
    showValidationError('Lỗi nhập liệu', 'Vui lòng nhập thời gian kết thúc hợp lệ');
    isValid = false;
  }

  if (isValid && endDate < startDate) {
    errors.value.end = 'Thời gian kết thúc phải sau thời gian bắt đầu';
    showValidationError('Lỗi nhập liệu', 'Thời gian kết thúc phải sau thời gian bắt đầu');
    isValid = false;
  }

  return isValid;
};

const validateReminderForm = () => {
  let isValid = true;
  errors.value = {
    title: '',
    start: '',
    end: '',
    reminder_time: ''
  };

  // Check title length
  if (!formData.value.title.trim()) {
    errors.value.title = 'Vui lòng nhập tiêu đề';
    showValidationError('Lỗi nhập liệu', 'Vui lòng nhập tiêu đề');
    isValid = false;
  } else if (formData.value.title.length > 255) {
    errors.value.title = 'Tiêu đề không được vượt quá 255 ký tự';
    showValidationError('Lỗi nhập liệu', 'Tiêu đề không được vượt quá 255 ký tự');
    isValid = false;
  }

  const reminderTime = new Date(formData.value.reminder_time);
  if (isNaN(reminderTime.getTime())) {
    errors.value.reminder_time = 'Vui lòng nhập thời gian hợp lệ';
    showValidationError('Lỗi nhập liệu', 'Vui lòng nhập thời gian hợp lệ');
    isValid = false;
  }

  return isValid;
};

const showValidationError = (title, message) => {
  alertTitle.value = title;
  alertMessage.value = message;
  showConfirmButton.value = false;
  showAlert.value = true;
};

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
    if (!validateEventForm()) return;

    emit('submit', {
      type: 'event',
      data: {
        title: formData.value.title.trim(),
        start: new Date(formData.value.start),
        end: new Date(formData.value.end),
        location: formData.value.location?.trim() || '',
        description: formData.value.description?.trim() || '',
        all_day: formData.value.all_day,
        color: formData.value.color
      }
    });
  } else {
    if (!validateReminderForm()) return;

    emit('submit', {
      type: 'reminder',
      data: {
        title: formData.value.title.trim(),
        reminder_time: new Date(formData.value.reminder_time),
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

// Alert state
const showAlert = ref(false);
const alertTitle = ref('');
const alertMessage = ref('');
const showConfirmButton = ref(false);
const pendingDelete = ref(false);

const closeAlert = () => {
  showAlert.value = false;
  if (pendingDelete.value) {
    pendingDelete.value = false;
  }
};

const handleAlertConfirm = () => {
  if (pendingDelete.value) {
    emit('submit', {
      action: 'delete',
      type: isEvent.value ? 'event' : 'reminder'
    });
    pendingDelete.value = false;
    showAlert.value = false;
  }
};

const handleDelete = () => {
  const itemType = isEvent.value ? 'sự kiện' : 'lời nhắc';
  alertTitle.value = 'Xác nhận xóa';
  alertMessage.value = `Bạn có chắc chắn muốn xóa ${itemType} này không?`;
  showConfirmButton.value = true;
  pendingDelete.value = true;
  showAlert.value = true;
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
/* Styles moved to resources/js/styles/components/modal.scss */
</style> 