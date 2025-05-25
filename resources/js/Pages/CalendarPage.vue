<script setup>
import { ref, onMounted } from 'vue'
import CalendarView from '../Components/CalendarView.vue'
import ProfileLink from '../Components/ProfileLink.vue'

const events = ref([])

async function fetchEvents() {
  const res = await fetch('/api/favorite-events', {
    headers: {
      'Accept': 'application/json',
    },
  })
  console.log(res);
  if (res.ok) {
    const data = await res.json()
    events.value = data.map(ev => ({
      title: ev.title,
      date: ev.date,
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
  <div class="space-y-8 p-3 md:p-6">
    <div class="flex justify-between">
      <router-link to="/">
        <img src="../../../public/logo-sfyns.png" alt="Logo" class="w-28 md:w-40 h-auto" />
      </router-link>
      <ProfileLink />

    </div>
  </div>
  <div class="md:max-w-4xl mx-auto md:p-6 max-w-full">
    <CalendarView :events="events" />
  </div>
</template>
