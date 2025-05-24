
<script setup>
import { ref, onMounted } from 'vue'
import CalendarView from '../Components/CalendarView.vue'

const events = ref([])

async function fetchEvents() {
  // Adjust URL if needed, add auth headers if necessary
  const res = await fetch('/api/favorite-events', {
    headers: {
      'Accept': 'application/json',
      // 'Authorization': `Bearer ${token}`, // if using Sanctum or API tokens
    },
  })
  console.log(res);
  if (res.ok) {
    const data = await res.json()
    // API returns dates as 'dd-mm-yyyy', convert to ISO to simplify or keep as is with parse function
    events.value = data.map(ev => ({
      title: ev.title,
      date: ev.date,  // 'dd-mm-yyyy' from your API, your CalendarView already parses it
      university: ev.university,
      faculty: ev.faculty,
    }))
    console.log(data)
  } else {
    console.error('Failed to fetch events')
  }
}

onMounted(() => {
  fetchEvents()
})
</script>

<template>
  <div class="max-w-4xl mx-auto p-6">
    <CalendarView :events="events" />
  </div>
</template>
