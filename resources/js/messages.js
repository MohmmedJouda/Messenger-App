import { createApp } from "vue";
import Messenger from "./components/messages/Messenger.vue";
import ChatList from "./components/messages/ChatList.vue";
import Friends from "./components/messages/Friends.vue";
import Echo from "laravel-echo";
import Pusher from "pusher-js";
window.Pusher = Pusher;

const chatApp = createApp({
    data() {
        return {
            messages: [],
            conversations: [],
            conversation: null,
            userId: userId,
            csrfToken: csrf_token,
            laravelEcho: null,
            users: [],
            chatChannel: null,
            alertAudio: new Audio("/assets/audio/new-message.wav"),
            token: token,
            users: friends,
        };
    },
    mounted() {
        this.alertAudio.addEventListener("ended", () => {
            this.alertAudio.current_time = 0;
        });
        // console.log(process.env.MIX_PUSHER_APP_KEY);
        this.laravelEcho = new Echo({
            broadcaster: "pusher",
            key: process.env.MIX_PUSHER_APP_KEY,
            cluster: process.env.MIX_PUSHER_APP_CLUSTER ?? "mt1",
            wsHost:
                process.env.MIX_PUSHER_HOST ??
                `ws-${process.env.MIX_PUSHER_APP_CLUSTER}.pusher.com`,
            wsPort: process.env.MIX_PUSHER_PORT ?? 80,
            wssPort: process.env.MIX_PUSHER_PORT ?? 443,
            forceTLS: (process.env.MIX_PUSHER_SCHEME ?? "https") === "https",
            enabledTransports: ["ws", "wss"],
        });

        this.laravelEcho
            .join(`Messenger.${this.userId}`)
            .listen(".new-message", (data) => {
                //if the new message that response does not within the already open conversation
                let exists = false;
                for (let i in this.conversations) {
                    let conversation = this.conversations[i];
                    if (conversation.id == data.message.conversation_id) {
                        if (!conversation.hasOwnProperty("new-messages")) {
                            conversation.new_messages = 0;
                        }
                        conversation.new_messages++;
                        conversation.last_message = data.message;
                        exists = true;
                        // if the new message that response Within the already open conversation
                        if (
                            this.conversation &&
                            this.conversation.id == conversation.id
                        ) {
                            this.messages.push(data.message);
                            var container =
                                document.getElementById("chat-body");
                            container.scrollTop = container.scrollHeight;
                        }
                        break;
                    }
                    if (!exists) {
                        fetch(
                            `/api/conversation/${data.message.conversation_id}`
                        )
                            .then((response) => data.json())
                            .then((json) => {
                                this.conversations.push(json);
                            });
                    }
                }

                this.alertAudio.play();
            });
        this.chatChannel = this.laravelEcho
            .join("Chat")
            .joining((user) => {
                for (let i in this.conversations) {
                    let conversation = this.conversations[i];
                    if (conversation.participants[0].id == user.id) {
                        this.conversations[i].participants[0].isOnline = true;
                        return;
                    }
                }
            })
            .leaving((user) => {
                for (let i in this.conversations) {
                    let conversation = this.conversations[i];
                    if (conversation.participants[0].id == user.id) {
                        this.conversations[i].participants[0].isOnline = false;
                        return;
                    }
                }
            })
            .listenForWhisper("typing", (e) => {
                let user = this.findUser(e.id, e.conversation_id);
                if (user) {
                    user.isTyping = true;
                }
            })
            .listenForWhisper("stopped-typing", (e) => {
                let user = this.findUser(e.id, e.conversation_id);
                if (user) {
                    user.isTyping = false;
                }
            });
    },
    methods: {
        moment(time) {
            return moment(time);
        },
        isOnline(user) {
            for (let i in this.users) {
                if (this.users[i].id == user.id) {
                    return this.users[i].isOnline;
                }
            }
            return false;
        },
        findUser(id, conversation_id) {
            for (let i in this.conversations) {
                let conversation = this.conversations[i];
                if (
                    (conversation.id === conversation_id &&
                        conversation.participants[0].id) == id
                ) {
                    return this.conversations[i].participants[0];
                }
            }
        },
        markAsRead(conversation = null) {
            if (conversation == null) {
                conversation = this.conversation;
            }
            fetch(`/api/conversations/${conversation.id}/read`, {
                method: "PUT",
                mode: "cors",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    _token: this.$root.csrfToken,
                }),
            })
                .then((response) => response.json())
                .then((json) => {
                    conversation.new_messages = 0;
                });
        },
        deleteMessage(message) {
            fetch(`/api/messages/${message.id}`, {
                method: "DELETE",
                mode: "cors",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    _token: this.$root.csrfToken,
                }),
            })
                .then((response) => response.json())
                .then((json) => {
                    let index = this.messages.indexOf(message);
                    this.messages.splice(index, 1);
                    message.body = "Message deleted..";
                });
        },
        getAllUsers(friends) {
            console.log(friends);
        },
    },
});
chatApp.component("Messenger", Messenger);
chatApp.component("ChatList", ChatList);
chatApp.component("Friends", Friends);
chatApp.mount("#chat-app");
