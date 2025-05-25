<template>
  <div>
    <h2 class="text-2xl font-semibold mb-4">{{ title }}</h2>

  <div class="grid grid-cols-3 gap-4 max-h-[18rem] overflow-hidden relative">
    <FieldCard
  v-for="field in fields"
  :key="field"
  :field="field"
  @click="handleClick(field)"
/>
    </div>

    <Button
      v-if="fields.length > maxVisible"
      @click="toggleShowAll"
      :type="'primary'"
      class="mt-4"
    >
      {{ showAll ? 'Zobrazit méně' : 'Zobrazit více' }}
    </Button>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import FieldCard from './FieldCard.vue';
import Button from './Button.vue';
import { useRouter } from 'vue-router'

const router = useRouter()


defineProps({
  title: String
})

const fields = [
  "biologie",
  "biomedicína a zdravotnické obory",
  "chemie",
  "doprava",
  "ekonomie",
  "ekologické",
  "elektro",
  "farmacie",
  "filozofie",
  "fyzika",
  "IT",
  "jazyky",
  "lékařské obory",
  "matematika",
  "ostatní humanitní",
  "ostatní přírodovědné",
  "ostatní technické",
  "pedagogika",
  "policejní a vojenské obory",
  "právo",
  "psychologie",
  "sport",
  "stavebnictví",
  "strojařina",
  "umělecké obory",
  "veterinářství",
  "zemědělské obory"
];

const maxVisible = 12;
const showAll = ref(false);

const visibleFields = computed(() => {
  return showAll.value ? fields : fields.slice(0, maxVisible);
});

function toggleShowAll() {
  showAll.value = !showAll.value;
}

function handleClick(field) {
  console.log('Kliknul jsi na:', field);
  router.push({ path: '/field-faculties', query: { field } })

}
</script>
