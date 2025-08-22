<script setup>
import { ref, onMounted } from 'vue'
import CalendarView from '../Components/CalendarView.vue'
import ProfileLink from '../Components/ProfileLink.vue'
import Logo from '../Components/Logo.vue'

const events = ref([])
const copied = ref(false)
const username = ref(null)


async function fetchEvents() {
  const res = await fetch('/api/favorite-events', {
    headers: {
      'Accept': 'application/json',
    },
  })
  if (res.ok) {
    const data = await res.json()
    events.value = data.map(ev => ({
      title: ev.title,
      date: ev.date,
      university: ev.university,
      faculty: ev.faculty,
    }))
  }
}

fetchEvents()

onMounted(() => {
  fetchEvents()
})

function formatDateForICS(dateStr) {
  const parts = dateStr.split('-')
  if (parts.length !== 3) return '00000000'
  const [day, month, year] = parts
  return `${year}${month.padStart(2,'0')}${day.padStart(2,'0')}`
}
 //tady byl totiz problem v mem uzasnem formatu na ddmmyyy


function downloadAllICS(events) {
  const icsContent = [
    'BEGIN:VCALENDAR',
    'VERSION:2.0',
    ...events.map(ev => `
BEGIN:VEVENT
SUMMARY:${ev.title}
DTSTART;VALUE=DATE:${formatDateForICS(ev.date)}
DTEND;VALUE=DATE:${formatDateForICS(ev.date)}
DESCRIPTION:${ev.university} - ${ev.faculty}
END:VEVENT`.trim())
  , 'END:VCALENDAR'].join('\n')

  const blob = new Blob([icsContent], { type: 'text/calendar;charset=utf-8' })
  const link = document.createElement('a')
  link.href = URL.createObjectURL(blob)
  link.download = `sfyns_kalendar.ics`
  link.click()
}

</script>

<template>
    

  <div class="space-y-8 p-3 md:p-6">
    <div class="flex justify-between">
<router-link to="/">
        <img src="../../../public/logo-sfyns.png" alt="Logo" class="w-28 md:w-40 h-auto" />
      </router-link>
      <ProfileLink />

    </div>
  </div>
  <div class="md:max-w-7xl mx-auto md:p-6 max-w-full">
    <CalendarView :events="events" />
    <div class="flex sm:justify-left sm:flex-row flex-col gap-2">
    <button class="ml-3 px-5 py-2.5 bg-black text-white font-bold rounded-xl shadow hover:bg-gray-800 transition flex items-center" @click="downloadAllICS(events)">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
        </svg>
        Stáhnout všechny události
      </button>
      <!--tady moznost pridat tlacitka, style nachystany-->
  </div>
  </div>
</template>
