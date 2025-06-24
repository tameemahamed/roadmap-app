<script setup>
import { ref, reactive } from 'vue'
import { format } from 'date-fns'
import { router } from '@inertiajs/vue3'

/**
 * Comment Component
 * Handles display and management of comments and replies
 * Supports: posting, replying, editing, deleting
 */

const props = defineProps({
  comments: {
    type: Array, // Array of comments to display
    required: true,
  },
  roadmap_id: {
    type: [Number, String],
    required: true
  }
})

// State for replies per comment
const expandedReplies = reactive({})

// Toggles reply visibility
function toggleReplies(commentId) {
  expandedReplies[commentId] = !expandedReplies[commentId]
}

// add new comment
const newCommentContent = ref('')
const postComment = () => {
  if (!newCommentContent.value.trim()) {
    return
  }
  else {
    const commentData = {
      content: newCommentContent.value,
      roadmap_id: props.roadmap_id
    };
    router.post('/addComment', commentData, {
      preserveScroll: true,
      onSuccess: () => {
        newCommentContent.value = '',
          window.location.reload()
      }
    })
  }
}

// add new Reply
const newReplyContent = reactive({})
const postReply = (comment_id) => {
  const content = newReplyContent[comment_id]?.trim()
  if (!content) {
    return
  }
  else {
    const replyData = {
      content: newReplyContent[comment_id],
      comment_id: comment_id
    };
    router.post('/addReply', replyData, {
      preserveScroll: true,
      onSuccess: () => {
        newReplyContent[comment_id] = '',
          window.location.reload()
      }
    })
  }
}

// edit comment 
const editingComment = ref(false)
const editedCommentContent = ref('')

const editCommentButton = (content) => {
  editingComment.value = !editingComment.value
  editedCommentContent.value = content
}

const editComment = (comment_id) => {
  if (!editedCommentContent.value.trim()) {
    return
  }
  else {
    const editedcommentData = {
      content: editedCommentContent.value,
      comment_id: comment_id
    }
    router.post('/editComment', editedcommentData, {
      preserveScroll: true,
      onSuccess: () => {
        window.location.reload()
      }
    })
  }
}


// Delete Comment
const deleteComment = (comment_id) => {
  router.post('/deleteComment', { comment_id }, {
    preserveScroll: true,
    onSuccess: () => {
      window.location.reload()
    }
  })
}

// Edit Reply
const editingReplies = reactive({})
const editedReplyContent = reactive({})

const editReplyButton = (reply_id, replyContent) => {
  editingReplies[reply_id] = !editingReplies[reply_id]
  editedReplyContent[reply_id] = replyContent
}

const editReply = (reply_id) => {
  const content = editedReplyContent[reply_id]?.trim()
  if (!content) {
    return
  }
  const editedReplyData = {
    reply_id: reply_id,
    content: content
  }
  router.post('/editReply', editedReplyData, {
    preserveScroll: true,
    onSuccess: () => {
      window.location.reload()
    }
  })
}

// Delete Reply
const deleteReply = (reply_id) => {
  router.post('/deleteReply', { reply_id }, {
    preserveScroll: true,
    onSuccess: () => {
      window.location.reload()
    }
  })
}


</script>

<template>
  <div class="max-w-6xl mx-auto">
    <!-- new comment input -->
    <div class="bg-gray-800 rounded-xl p-6 mb-6 border border-gray-700">
      <textarea v-model="newCommentContent" rows="3" placeholder="Add a comment..."
        class="w-full bg-gray-700 text-gray-100 rounded-lg p-4 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4"></textarea>
      <button @click="postComment" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
        Post Comment
      </button>
    </div>

    <!-- Existing Comments -->
    <div v-for="comment in comments" :key="comment.id" class="bg-gray-800 rounded-xl p-6 mb-6 border border-gray-700">
      <!-- Comment Header -->
      <div class="w-full flex justify-between items-center mb-4">
        <span class="text-blue-600 font-medium">{{ comment.name }}</span>
        <span class="text-gray-400 text-sm ml-auto">{{ format(new Date(comment.created_at), 'MM/dd/yyyy HH:mm')
          }}</span>
      </div>

      <!-- Comment content and edit UI -->
      <div v-if="editingComment">
        <div class="bg-gray-800 rounded-xl p-6 mb-6 border border-gray-700">
          <textarea v-model="editedCommentContent" rows="3" placeholder="Add a comment..."
            class="w-full bg-gray-700 text-gray-100 rounded-lg p-4 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4"></textarea>
          <button @click="editComment(comment.id)"
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
            Edit Comment
          </button>
        </div>
      </div>
      <p v-else class="text-gray-300 leading-relaxed mb-2">{{ comment.content }}</p>
      <div v-if="comment.edited">
          <p class="text-xs italic text-gray-500 mb-2">Edited</p>
      </div>
      <div class="flex justify-between items-center mb-4">
        <button v-if="$page.props.auth.user.id == comment.user_id" @click="editCommentButton(comment.content)"
          class="text-yellow-400 text-sm hover:underline">Edit</button>
        <button v-if="$page.props.auth.user.id == comment.user_id" @click="deleteComment(comment.id)"
          class="text-red-500 text-sm hover:underline">Delete</button>
      </div>
      
      <!-- Replies -->
      <div v-if="comment.replies && comment.replies.length" class="ml-6 border-l border-gray-700 pl-4 space-y-4">
        <!-- Display replies (limited when collapsed) -->
        <div v-for="(reply, idx) in (expandedReplies[comment.id] ? comment.replies : comment.replies.slice(0, 2))"
          :key="reply.id" class="bg-gray-700 rounded-lg p-4 border border-gray-600">
          <div class="flex justify-between items-center mb-2">
            <span class="text-blue-600 font-medium">{{ reply.name }}</span>
            <span class="text-gray-400 text-xs">{{ format(new Date(reply.created_at), 'MM/dd/yyyy HH:mm') }}</span>
          </div>

          <div v-if="editingReplies[reply.id]" class="mt-4">
            <textarea v-model="editedReplyContent[reply.id]" rows="2" placeholder="Write a reply..."
              class="w-full bg-gray-700 text-gray-100 rounded-lg p-3 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"></textarea>
            <button @click="editReply(reply.id)"
              class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-1 px-3 rounded-lg text-sm">
              Edit Reply
            </button>
          </div>
          <p v-else class="text-gray-300 text-sm">{{ reply.content }}</p>
          <div class="flex space-x-4">
            <button v-if="$page.props.auth.user.id == reply.user_id" 
              @click="editReplyButton(reply.id, reply.content)"
              class="text-yellow-400 text-xs hover:underline">
              Edit
            </button>
            <button v-if="$page.props.auth.user.id == reply.user_id" @click="deleteReply(reply.id)"
              class="text-red-500 text-xs hover:underline">
              Delete
            </button>
          </div>
        </div>

        <!-- View more/hide replies toggle -->
        <div v-if="comment.replies.length > 2" class="mt-2">
          <button @click="toggleReplies(comment.id)" class="text-blue-400 text-sm font-medium hover:underline">
            {{ expandedReplies[comment.id] ? 'Hide replies'
              : `View ${comment.replies.length - 2} more replies` }}
          </button>
        </div>


      </div>
      <!-- new reply input -->
      <div class="mt-4">
        <textarea v-model="newReplyContent[comment.id]" rows="2" placeholder="Write a reply..."
          class="w-full bg-gray-700 text-gray-100 rounded-lg p-3 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"></textarea>
        <button @click="postReply(comment.id)"
          class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-1 px-3 rounded-lg text-sm">
          Reply
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Fade transition for replies */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Override the justify-between to start, so buttons sit next to each other */
.flex.justify-between.items-center.mb-4 {
  justify-content: flex-start !important;
}

/* Add a little spacing between the two buttons */
.flex.justify-between.items-center.mb-4>button+button {
  margin-left: 0.5rem;
}
</style>
