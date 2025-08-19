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
    default: () => [],
  },
})

const today = new Date()
const currentMonth = ref(new Date())

const selectedEvents = ref([])
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

function getEventsForDay(day) {
  return props.events.filter(event =>
    isSameDay(parseEventDate(event.date), day)
  )
}

function selectDay(day) {
  selectedDate.value = day
  selectedEvents.value = getEventsForDay(day)
}

console.log('Events props:', props.events)



function getEventColor(event) {
  if (event.title && event.title.toLowerCase().includes('den otevřených dveří')) {
    return 'bg-indigo-600 text-white'
  }
  if (event.title && event.title.toLowerCase().includes('přijímací zkoušky')) {
    return 'bg-amber-500 text-white'
  }
  if (event.title && event.title.toLowerCase().includes('podání přihlášky')) {
    return 'bg-green-600 text-white'
  }
}

function getEventColorText(event) {
  if (event.title && event.title.toLowerCase().includes('den otevřených dveří')) {
    return 'text-indigo-600'
  }
  if (event.title && event.title.toLowerCase().includes('přijímací zkoušky')) {
    return 'text-amber-500'
  }
  if (event.title && event.title.toLowerCase().includes('podání přihlášky')) {
    return 'text-green-600'
  }
}
</script>

<template>
  <div class="flex flex-col md:flex-row gap-6 p-4 mt-[6%] rounded-xl max-w-full overflow-x-hidden">

    <!-- Kalendář -->
    <div class="w-full md:flex-1">
      <div class="flex justify-between items-center mb-4 px-2 md:px-0">
        <button @click="prevMonth" class="text-gray-600 hover:text-black font-black text-lg">&lt;</button>
        <h2 class="text-xl font-bold capitalize">
          {{ format(currentMonth, 'LLLL yyyy', { locale: cs }) }}
        </h2>
        <button @click="nextMonth" class="text-gray-600 hover:text-black font-black text-lg">&gt;</button>
      </div>

      <div class="grid grid-cols-7 text-center text-gray-900 font-semibold mb-2 px-2 md:px-0">
        <div v-for="day in ['Po', 'Út', 'St', 'Čt', 'Pá', 'So', 'Ne']" :key="day">{{ day }}</div>
      </div>

      <div class="grid grid-cols-7 gap-1 text-sm px-2 md:px-0">
        <div v-for="day in days" :key="day"
          class="p-2 rounded border min-h-[80px] flex flex-col relative cursor-pointer transition-all duration-150"
          :class="{
            'bg-gray-50 text-gray-400 border-gray-200': !isSameMonth(day, currentMonth),
            'ring-2 ring-black': selectedDate && isSameDay(day, selectedDate),
            'border-gray-300': isSameMonth(day, currentMonth) && !isSameDay(day, today),
          }"
          @click="selectDay(day)">
          
          <div class="font-bold text-right mb-1 select-none"
               :class="{ 'rounded-full bg-black text-white w-7 h-7 flex items-center justify-center ml-auto': isSameDay(day, today) }">
            {{ format(day, 'd') }}
          </div>

          <ul class="flex flex-col space-y-1 overflow-visible max-h-[60px]">
            <li v-for="event in getEventsForDay(day)" :key="event.title + event.date"
              class="text-xs block rounded px-2 py-0.5 truncate shadow-sm" :class="getEventColor(event)" :title="event.title">
              {{ event.title }}
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Sidebar s vybranými událostmi -->
    <aside v-if="selectedDate && selectedEvents.length > 0"
      class="w-full md:w-80 p-4 bg-gray-50 rounded-xl shadow-inner overflow-auto max-h-[400px] md:max-h-[600px]">
      <p class="mb-2 font-medium">
        Datum: {{ format(selectedDate, 'd. LLLL yyyy', { locale: cs }) }}
      </p>
      <ul>
        <li v-for="(event, index) in selectedEvents" :key="index"
            class="mb-4 border-b border-gray-300 pb-2 last:border-b-0">
          <h4 class="font-semibold" :class="getEventColorText(event)">{{ event.title }}</h4>
          <p><strong>Univerzita:</strong> {{ event.university }}</p>
        </li>
      </ul>


    </aside>

  </div>
</template>
