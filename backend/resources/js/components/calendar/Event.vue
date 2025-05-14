<template>
    <div
      class="absolute left-0 right-0 mx-1 p-1 rounded border-l-4 overflow-hidden cursor-pointer"
      :class="eventColor"
      :style="{ height: eventHeight + 'px', maxHeight: '100%', zIndex: 10 }"
    >
      <div class="text-xs font-medium truncate">{{ event.title }}</div>
      <div v-if="eventHeight > 40" class="text-xs truncate">
        {{ event.location }}
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import type { EventType } from '../utils/dummyData';
  
  const props = defineProps<{
    event: EventType;
  }>();
  
  // Duration in minutes
  const getDurationInMinutes = (): number => {
    const start = new Date(props.event.start);
    const end = new Date(props.event.end);
    return (end.getTime() - start.getTime()) / (1000 * 60);
  };
  
  // Height in pixels
  const eventHeight = Math.max(
    Math.min((getDurationInMinutes() / 60) * 64, 240),
    24
  );
  
  // Dynamic class based on event type
  const eventColor = (() => {
    switch (props.event.type) {
      case 'meeting':
        return 'bg-blue-100 border-blue-400 text-blue-800';
      case 'personal':
        return 'bg-green-100 border-green-400 text-green-800';
      case 'reminder':
        return 'bg-yellow-100 border-yellow-400 text-yellow-800';
      default:
        return 'bg-gray-100 border-gray-400 text-gray-800';
    }
  })();
  </script>
  