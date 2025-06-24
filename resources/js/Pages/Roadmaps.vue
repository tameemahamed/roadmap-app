<script setup>
import { onMounted, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

/**
 * Roadmaps Component
 * Displays a list of roadmaps with filtering capabilities
 * Allows filtering by status and sorting by upvotes
 */
// Filter state variables
const status_IDs = ref([1,2,3]);
const filter_upvotes = ref('0');
const roadmaps = ref([]);
const page = usePage();

// prepare auth header if token is present
const headers = {};
if (page.props.auth_token) {
  headers['Authorization'] = `Bearer ${page.props.auth_token}`;
}

// Fetch roadmaps based on current filters
const fetchRoadmaps = () => {
  // join status IDs with comma
  const statuses = status_IDs.value.length ? status_IDs.value.join(',') : '';
  axios
    .get(`/api/roadmaps/${statuses}/${filter_upvotes.value}`, { headers })
    .then(response => {
      roadmaps.value = response.data.roadmaps;
    })
    .catch(error => {
      console.error('Error fetching roadmaps:', error);
    });
};

// Initial fetch
onMounted(fetchRoadmaps);
// Refetch whenever filters change
watch([status_IDs, filter_upvotes], fetchRoadmaps);
</script>

<template>
  <Head title="Roadmaps" />
  <AuthenticatedLayout>
    

    <div class="py-6">
      <!-- Filters Panel -->
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-4">

          <!-- Status Checkboxes -->
          <div class="mb-4">
            <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filter Statuses?</span>
            <div class="mt-2 flex space-x-4">
              <label class="inline-flex items-center">
                <input type="checkbox" value="1" v-model="status_IDs"
                       class="form-checkbox h-4 w-4 text-blue-600" />
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Planned</span>
              </label>
              <label class="inline-flex items-center">
                <input type="checkbox" value="2" v-model="status_IDs"
                       class="form-checkbox h-4 w-4 text-blue-600" />
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">In Progress</span>
              </label>
              <label class="inline-flex items-center">
                <input type="checkbox" value="3" v-model="status_IDs"
                       class="form-checkbox h-4 w-4 text-blue-600" />
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Completed</span>
              </label>
            </div>
          </div>

          <!-- Upvotes Radio Buttons -->
          <div>
            <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sort by Upvotes?</span>
            <div class="mt-2 flex space-x-6">
              <label class="inline-flex items-center">
                <input type="radio" value="1" v-model="filter_upvotes"
                       class="form-radio h-4 w-4 text-blue-600" />
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
              </label>
              <label class="inline-flex items-center">
                <input type="radio" value="0" v-model="filter_upvotes"
                       class="form-radio h-4 w-4 text-blue-600" />
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
              </label>
            </div>
          </div>
        </div>
      </div>

      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
        <!-- Roadmaps List -->
        <div v-for="roadmap in roadmaps" :key="roadmap.id">
          <RoadmapLayout :roadmap="roadmap" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
