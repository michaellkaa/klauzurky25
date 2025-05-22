<script setup>
import { ref, computed } from 'vue'
import { format, startOfMonth, endOfMonth, startOfWeek, endOfWeek, eachDayOfInterval, isSameDay, isSameMonth } from 'date-fns'
import cs from 'date-fns/locale/cs'

const props = defineProps({
  events: Array, // [{ date: '2025-11-23', title: 'Událost' }]
})

const today = new Date()
const currentMonth = ref(new Date())

const days = computed(() => {
  const start = startOfWeek(startOfMonth(currentMonth.value), { locale: cs, weekStartsOn: 1 })
  const end = endOfWeek(endOfMonth(currentMonth.value), { locale: cs, weekStartsOn: 1 })
  return eachDayOfInterval({ start, end })
})

function getEventsForDay(day) {
  return props.events.filter(event =>
    isSameDay(new Date(event.date), day)
  )
}
</script>

<template>
  <div class="p-4 bg-white rounded-xl shadow">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold">
        {{ format(currentMonth, 'LLLL yyyy', { locale: cs }) }}
      </h2>
    </div>

    <div class="grid grid-cols-7 text-center text-gray-500 font-semibold mb-2">
      <div v-for="day in ['Po','Út','St','Čt','Pá','So','Ne']" :key="day">{{ day }}</div>
    </div>

    <div class="grid grid-cols-7 gap-1 text-sm">
      <div
        v-for="day in days"
        :key="day"
        class="p-2 rounded border relative"
        :class="{
          'bg-gray-100 text-gray-400': !isSameMonth(day, currentMonth),
          'bg-blue-50': isSameDay(day, today),
        }"
      >
        <div class="font-semibold">{{ format(day, 'd') }}</div>

        <ul class="mt-1 space-y-0.5">
          <li
            v-for="event in getEventsForDay(day)"
            :key="event.title"
            class="text-xs bg-blue-100 text-blue-800 rounded px-1"
          >
            {{ event.title }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
