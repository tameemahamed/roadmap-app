<script setup>
import { ref, computed } from 'vue'
import { format } from 'date-fns'
import { router } from '@inertiajs/vue3';

/**
 * Roadmap Layout Component
 * Displays roadmap card with expandable details
 * Handles upvoting functionality
 */

const expanded = ref(false) // controls expanded/collapsed state

const props = defineProps({
  roadmap: {
    type: Object,
    required: true,
  }
})


// date formattings
const previewDate = computed(() =>
  props.roadmap.preview_available_date
    ? format(new Date(props.roadmap.preview_available_date), 'MMM yyyy')
    : null
)

const rolloutDate = computed(() =>
  props.roadmap.rollout_start_date
    ? format(new Date(props.roadmap.rollout_start_date), 'MMM yyyy')
    : null
)

const createDate = computed(() => {
  return props.roadmap.created_at
    ? format(new Date(props.roadmap.created_at), 'MM/dd/yyyy')
    : null
})

const updateDate = computed(() => {
  return props.roadmap.updated_at
    ? format(new Date(props.roadmap.updated_at), 'MM/dd/yyyy')
    : null
})


const votted = ref(props.roadmap.upvotted !== 0) // upvote status

// Handle upvote button click
const likeButtonPress = () => {
  router.post('/liked', { roadmap_id: props.roadmap.id }, {
    onSuccess: () => {
      if (votted.value) {
        props.roadmap.upvotes_count -= 1
      }
      else {
        props.roadmap.upvotes_count += 1
      }
      votted.value = !votted.value
    },
    preserveScroll: true
  })
}

// status indicator squares
const squares = computed(() => {
  const n = props.roadmap.status_id
  return Array.from({ length: 3 }, (_, i) => i < n)
})


</script>

<template>
  <div class="bg-gray-800 rounded-xl p-6 max-w-6xl mx-auto border border-gray-700">
    <!-- Header: Title, status, dates and expand button -->
    <div class="flex items-start justify-between mb-4">
      <div class="flex-1">
        <!-- Title -->
        <h2 class="text-white text-xl font-semibold mb-3">
          {{ roadmap.title }}
        </h2>

        <!-- Status with squares -->
        <div class="flex items-center gap-2">
          <div class="flex gap-1">
            <div v-for="(filled, idx) in squares" :key="idx" 
              :class="filled ? 'bg-blue-500' : 'bg-gray-500'"
              class="w-2 h-2 rounded-sm">
            </div>
          </div>
          <span class="text-blue-400 text-sm font-medium">{{ roadmap.status }}</span>
        </div>
      </div>

      <!-- Date information and expand toggle -->
      <div class="flex items-start gap-8">
        <div class="flex gap-8">
          <div v-if="previewDate" class="text-right">
            <div class="text-blue-400 text-sm font-medium">PREVIEW AVAILABLE</div>
            <div class="text-white font-medium">{{ previewDate }}</div>
          </div>
          <div v-if="rolloutDate" class="text-right">
            <div class="text-blue-400 text-sm font-medium">ROLLOUT START</div>
            <div class="text-white font-medium">{{ rolloutDate }}</div>
          </div>
        </div>

        <!-- Expand button -->
        <button @click="expanded = !expanded" class="p-2 rounded-md bg-gray-700 hover:bg-gray-600 flex-shrink-0">
          <span class="text-xl font-bold text-white">{{ expanded ? '−' : '+' }}</span>
        </button>
      </div>
    </div>

    <!-- Tags Display -->
    <div class="flex flex-wrap gap-2 mb-6">
      <span v-for="tag in roadmap.tags" :key="tag" class="bg-gray-700 text-gray-300 px-3 py-1.5 rounded-md text-sm">
        {{ tag }}
      </span>
    </div>

    <!-- Expanded Content -->
    <div v-if="expanded" class="space-y-6">
      <div class="text-gray-300 leading-relaxed">
        <p>{{ roadmap.description }}</p>
      </div>

      <!-- Metadata -->
      <div class="flex gap-6 text-sm text-gray-400 pt-4 border-t border-gray-700">
        <div v-if="createDate">
          <span class="font-medium">Added to roadmap:</span> {{ createDate }}
        </div>
        <div v-if="updateDate">
          <span class="font-medium">Last modified:</span> {{ updateDate }}
        </div>
      </div>
    </div>

    <!-- Upvote and comment buttons -->
    <div class="flex items-center gap-6 mt-6">
      <button @click="likeButtonPress" class="flex items-center gap-2"
        :class="votted ? 'text-blue-400' : 'text-gray-300 hover:text-white'">
        <img src="https://img.icons8.com/ios-glyphs/30/ffffff/thumb-up.png" alt="Upvote" class="w-5 h-5" />
        <span class="font-medium">Upvote</span>
        <span class="text-sm">({{ roadmap.upvotes_count }})</span>
      </button>
      <Link :href="`/roadmap/${roadmap.id}`" class="flex items-center gap-2 text-gray-300 hover:text-white">
      <img src="https://img.icons8.com/ios-glyphs/30/ffffff/comments.png" alt="Comment" class="w-5 h-5" />
      <span class="font-medium">Comment</span>
      <span class="text-sm">({{ roadmap.comments_count }})</span>
      </Link>
    </div>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
