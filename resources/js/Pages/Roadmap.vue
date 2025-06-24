<script setup>
import { onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

/**
 * Roadmap Detail Component
 * Shows detailed view of a single roadmap including comments
 */

const props = defineProps({
    roadmap_id: {
        type: [Number, String],
        required:true}
})

// Reactive state
const roadmap = ref({}) // current roadmap data
const comments = ref([]) // associated comments
const page = usePage()

// prepare auth header if token is present
const headers = {};
if (page.props.auth_token) {
    headers['Authorization'] = `Bearer ${page.props.auth_token}`;
}

// Fetch roadmap and comments on mount
onMounted(() => {
    axios.get(`/api/roadmap/${props.roadmap_id}`, { headers })
        .then(response => {
            roadmap.value = response.data.roadmap
            comments.value = response.data.comments
        })
        .catch(error => {
            console.error('Error : ', error)
        })
})
</script>

<template>
    <Head title="Comments" />
    <AuthenticatedLayout>
        <div class="py-12">
                <!-- Roadmap details -->
                <RoadmapLayout v-if="roadmap.id" :roadmap="roadmap" />
                <!-- Comments Section -->
                <CommentLayout :comments="comments" :roadmap_id="roadmap_id"/>
        </div>
    </AuthenticatedLayout>
</template>