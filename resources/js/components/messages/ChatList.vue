<template>
<div class="container py-8">
    <!-- Title -->
    <div class="mb-8">
        <h2 class="fw-bold m-0">Chats</h2>
    </div>

    <!-- Search -->
    <div class="mb-6">
        <form action="#">
            <div class="input-group">
                <div class="input-group-text">
                    <div class="icon icon-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-search">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65"
                                y2="16.65"></line>
                        </svg>
                    </div>
                </div>

                <input type="text" class="form-control form-control-lg ps-0"
                    v-model="searchQuery"
                    placeholder="Search messages or users"
                    aria-label="Search for messages or users..." />
            </div>
        </form>
    </div>

    <!-- Chats -->
    <div class="card-list" id="chat-list">
        <a v-for="conversation in filteredConversations" :key="conversation.id"
        :href="'#' + conversation.id"
        @click="setConversation(conversation)"
        class="card border-0 text-reset">
            <div class="card-body">
                <div class="row gx-5">
                    <div class="col-auto">
                        <div class="avatar"
                        :class = "{'avatar-online' : conversation.type !== 'group' && conversation.participants[0] && conversation.participants[0].isOnline}">
                            <img class= "avatar-img" :src="getAvatar(conversation)">
                        </div>
                    </div>

                    <div class="col">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="me-auto mb-0">{{ getName(conversation) }}</h5>
                            <span class="text-muted extra-small ms-2">
                                {{$root.moment(conversation.last_message.created_at).fromNow()}}</span>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="line-clamp me-auto">
                                {{conversation.last_message.type == 'attachment'? conversation.last_message.body.file_name:conversation.last_message.body}}
                            </div>
                            <div v-if="conversation.new_messages" class="badge badge-circle bg-primary ms-5">
                                    <span>{{conversation.new_messages}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- .card-body -->
        </a>
    </div>
    <!-- Chats -->
</div>
</template>
<script>

import axios from 'axios';
export default {

    data(){
        return{
            friends : [],
            searchQuery : ''
        };
    },
    computed: {
        filteredConversations() {
            let conversations = this.$root.conversations || [];
            if (!this.searchQuery) return conversations;
            const query = this.searchQuery.toLowerCase();
            return conversations.filter(conversation => {
                let nameMatches = false;
                let name = conversation.type === 'group' ? conversation.label : (conversation.participants && conversation.participants[0] ? conversation.participants[0].name : '');
                if (name) {
                    nameMatches = name.toLowerCase().includes(query);
                }
                
                let messageMatches = false;
                if (conversation.last_message) {
                    let text = (conversation.last_message.type === 'attachment') 
                               ? conversation.last_message.body.file_name 
                               : conversation.last_message.body;
                    if (text && typeof text === 'string') {
                        messageMatches = text.toLowerCase().includes(query);
                    }
                }
                return nameMatches || messageMatches;
            });
        }
    },
    methods: {
        setConversation(conversation){
            this.$root.conversation = conversation;
            this.$root.markAsRead(conversation);
        },
        getAvatar(conversation) {
            if (conversation.type === 'group') {
                return conversation.avatar_url ? conversation.avatar_url : `https://ui-avatars.com/api/?name=${encodeURIComponent(conversation.label || 'Group')}&background=random`;
            }
            let participant = conversation.participants && conversation.participants[0] ? conversation.participants[0] : null;
            return participant && participant.avatar_url ? participant.avatar_url : `https://ui-avatars.com/api/?name=${encodeURIComponent(participant ? participant.name : 'User')}&background=random`;
        },
        getName(conversation) {
            if (conversation.type === 'group') {
                return conversation.label || 'Group';
            }
            let participant = conversation.participants && conversation.participants[0] ? conversation.participants[0] : null;
            return participant ? participant.name : 'Unknown';
        }
    },
    mounted(){
    axios.get('/conversations')
        .then(response => {
            let conversations = response.data.data;
            conversations.forEach(conv => {
                if (conv.participants && conv.participants.length > 0) {
                    conv.participants[0].isOnline = false;
                }
            });
        this.$root.conversations = conversations;
    })
    .catch(error => console.error("Error fetching conversations:", error));
    },
}
</script>
