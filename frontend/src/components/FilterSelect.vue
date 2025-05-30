<template>
  <div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
    <select v-model="selectedValue" @change="onChange" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
      <option value="">Не имеет значения</option>
      <option v-for="item in items" :key="item.id" :value="item.id">
        {{ item.name }}
      </option>
    </select>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api.js'

const props = defineProps(['paramType', 'label'])
const emit = defineEmits(['update'])

const items = ref([])
const selectedValue = ref('')

onMounted(async () => {
  try {
    const response = await api.get(`/many_vacancy_parameters/${props.paramType}`)
    items.value = response.data
  } catch (e) {
    console.error(`Ошибка загрузки ${props.paramType}:`, e)
  }
})

const onChange = () => {
  emit('update', selectedValue.value ? parseInt(selectedValue.value) : null)
}
</script>