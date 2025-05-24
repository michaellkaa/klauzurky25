<script setup>
import { ref, computed } from 'vue'
import {
  format,
  startOfMonth,
  endOfMonth,
  startOfWeek,
  endOfWeek,
  eachDayOfInterval,
  isSameDay,
  isSameMonth,
  addMonths,
  subMonths
} from 'date-fns'
import cs from 'date-fns/locale/cs'

const props = defineProps({
  events: {
    type: Array,
    default: () => [], // [{ date: '23-11-2025', title: 'Událost', faculty: 'Fakulta', university: 'Univerzita' }]
  },
})

const today = new Date()
const currentMonth = ref(new Date())

const selectedEvents = ref([])  // Vybrané eventy pro sidebar
const selectedDate = ref(null)

function nextMonth() {
  currentMonth.value = addMonths(currentMonth.value, 1)
}

function prevMonth() {
  currentMonth.value = subMonths(currentMonth.value, 1)
}

const days = computed(() => {
  const start = startOfWeek(startOfMonth(currentMonth.value), { locale: cs, weekStartsOn: 1 })
  const end = endOfWeek(endOfMonth(currentMonth.value), { locale: cs, weekStartsOn: 1 })
  return eachDayOfInterval({ start, end })
})

// Převod datumu na Date objekt (podobně jako máš)
function parseEventDate(dateStr) {
  if (!dateStr) return null;
  if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
    return new Date(dateStr);
  }
  if (/^\d{2}-\d{2}-\d{4}$/.test(dateStr)) {
    const [day, month, year] = dateStr.split('-');
    return new Date(`${year}-${month}-${day}`);
  }
  return null;
}

// Vrátí eventy pro daný den
function getEventsForDay(day) {
  return props.events.filter(event =>
    isSameDay(parseEventDate(event.date), day)
  )
}

// Po kliknutí na den nastavíme vybrané eventy do sidebaru
function selectDay(day) {
  selectedDate.value = day
  selectedEvents.value = getEventsForDay(day)
}

console.log('Events props:', props.events)

</script>

<template>
  <div class="flex gap-6 p-4 mt-[10%] bg-white rounded-xl shadow">
    <!-- Kalendář -->
    <div class="flex-1 max-w-4xl">
      <!-- Horní lišta -->
      <div class="flex justify-between items-center mb-4">
        <button @click="prevMonth" class="text-gray-600 hover:text-black font-black text-lg">&lt;</button>
        <h2 class="text-xl font-bold capitalize">
          {{ format(currentMonth, 'LLLL yyyy', { locale: cs }) }}
        </h2>
        <button @click="nextMonth" class="text-gray-600 hover:text-black font-black text-lg">&gt;</button>
      </div>

      <!-- Dny v týdnu -->
      <div class="grid grid-cols-7 text-center text-gray-500 font-semibold mb-2">
        <div v-for="day in ['Po','Út','St','Čt','Pá','So','Ne']" :key="day">{{ day }}</div>
      </div>

      <!-- Kalendář dny -->
      <div class="grid grid-cols-7 gap-1 text-sm">
        <div
          v-for="day in days"
          :key="day"
          class="p-2 rounded border min-h-[80px] flex flex-col relative cursor-pointer"
          :class="{
            'bg-gray-50 text-gray-400 border-gray-200': !isSameMonth(day, currentMonth),
            'bg-gray-100 border-gray-400': isSameDay(day, today),
            'border-gray-300': isSameMonth(day, currentMonth) && !isSameDay(day, today),
            'ring-2 ring-black': selectedDate && isSameDay(day, selectedDate),
          }"
          @click="selectDay(day)"
        >
          <div class="font-semibold text-right mb-1 select-none">{{ format(day, 'd') }}</div>
          <ul class="flex flex-col space-y-1 overflow-visible max-h-[60px]">
            <li
              v-for="event in getEventsForDay(day)"
              :key="event.title + event.date"
              class="text-xs block bg-blue-400 text-white rounded px-2 py-0.5 truncate shadow-sm"
              :title="event.title"
            >
              {{ event.title }}
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Sidebar s detaily -->
    <aside class="w-80 p-4 bg-gray-50 rounded-xl shadow-inner overflow-auto max-h-[600px]">
      <h3 class="text-lg font-semibold mb-4">Detaily vybraného dne</h3>
      
      <template v-if="selectedEvents.length > 0">
        <p class="mb-2 font-medium">Datum: {{ selectedDate ? format(selectedDate, 'd. LLLL yyyy', { locale: cs }) : '' }}</p>
        <ul>
          <li v-for="(event, index) in selectedEvents" :key="index" class="mb-4 border-b border-gray-300 pb-2 last:border-b-0">
            <h4 class="font-semibold text-blue-400">{{ event.title }}</h4>
            <p><strong>Univerzita:</strong> {{ event.university }}</p>
          </li>
        </ul>
      </template>

      <template v-else>
        <p class="mb-2 font-medium">Datum: </p>
        <ul>
          <li class="mb-4 border-b border-gray-300 pb-2 last:border-b-0">
            <h4 class="font-semibold text-blue-400">Název události</h4>
            <p><strong>Univerzita:</strong> </p>
          </li>
        </ul>
      </template>
    </aside>
  </div>
</template>
