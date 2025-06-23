<script setup>
import { onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    roadmap_id: {
        type: [Number, String],
        required:true}
})
const roadmap = ref({})
const comments = ref([])
const page = usePage()

const headers = {};
if (page.props.auth_token) {
    headers['Authorization'] = `Bearer ${page.props.auth_token}`;
}
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
                <RoadmapLayout v-if="roadmap.id" :roadmap="roadmap" />
                <!-- <div class="bg-gray-800 space-y-6 rounded-xl p-6 mb-6 border border-gray-700"> -->
                    <CommentLayout :comments="comments" :roadmap_id="roadmap_id"/>
                <!-- </div> -->
        </div>
    </AuthenticatedLayout>
</template>