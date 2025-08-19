<template>
  <div>
    <h2 class="text-2xl font-bold uppercase tracking-wide mb-4 ml-3">{{ title }}</h2>


    <div class="grid gap-4 relative overflow-hidden 
             grid-cols-1 sm:grid-cols-2 md:grid-cols-3"
      :class="{ 'max-h-[18rem]': !showAll, 'max-h-[24rem] sm:max-h-[30rem]': !showAll }">
      <FieldCard v-for="field in visibleFields" :key="field" :field="field.name" @click="handleClick(field)" />
    </div>

    <Button v-if="fields.length > maxVisible" @click="toggleShowAll" :type="'primary'" class="mt-4">
      {{ showAll ? 'Zobrazit méně' : 'Zobrazit více' }}
    </Button>
  </div>
</template>


<script setup>
import { ref, onMounted, computed } from 'vue';
import FieldCard from './FieldCard.vue';
import Button from './Button.vue';
import { useRouter } from 'vue-router'

const router = useRouter()
const fields = ref([])
const maxVisible = 9;
const showAll = ref(false);

defineProps({
  title: String
})

onMounted(async () => {
  try {
    const res = await fetch('/api/fields')
    fields.value = await res.json()
  } catch (err) {
    console.error("Chyba při načítání oborů:", err)
  }
})

const visibleFields = computed(() => {
  return showAll.value ? fields.value : fields.value.slice(0, maxVisible);
});

function toggleShowAll() {
  showAll.value = !showAll.value;
}

function handleClick(field) {
  router.push({ path: '/field-faculties', query: { field: field.name } })
}
</script>
