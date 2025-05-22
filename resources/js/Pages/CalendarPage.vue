<script setup>
import CalendarView from '@/components/CalendarView.vue'

// Ukázkové události
const events = [
  { date: '2025-11-23', title: 'Den otevřených dveří – FI MUNI' },
  { date: '2026-02-28', title: 'Přihlášky – ČVUT FEL' },
]
</script>

<template>
  <div class="max-w-4xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Kalendář událostí fakult</h1>
    <CalendarView :events="events" />
  </div>
</template>
