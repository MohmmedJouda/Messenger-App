import { createApp } from "vue";
import Messenger from "./components/messages/Messenger.vue";
import ChatList from "./components/messages/ChatList.vue";
import Friends from "./components/messages/Friends.vue";
import CreateChat from "./components/messages/CreateChat.vue";
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
            soundEnabled: true,
            token: token,
            users: friends,
        };
    },
    mounted() {
        this.alertAudio.addEventListener("ended", () => {
            this.alertAudio.currentTime = 0;
        });
        // console.log(process.env.MIX_PUSHER_APP_KEY);
        this.laravelEcho = new Echo({
            broadcaster: "pusher",
            key: process.env.MIX_PUSHER_APP_KEY,
            cluster: process.env.MIX_PUSHER_APP_CLUSTER ?? "ap2",
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
                        fetch(`/conversation/${data.message.conversation_id}`)
                            .then((response) => response.json())
                            .then((json) => {
                                this.conversations.push(json);
                            });
                    }
                }

                this.playAudio();
            });
        this.chatChannel = this.laravelEcho
            .join("Chat")
            .here((users) => {
                // 'users' هي مصفوفة بكل الأشخاص المتصلين الآن لحظة دخولك أنت
                users.forEach((user) => {
                    this.updateUserStatus(user.id, true);
                });
            })
            .joining((user) => {
                for (let i in this.conversations) {
                    let conversation = this.conversations[i];
                    if (conversation.participants[0].id == user.id) {
                        this.conversations[i].participants[0].isOnline = true;
                        return;
                    }
                }
                this.updateUserStatus(user.id, true);
            })
            .leaving((user) => {
                for (let i in this.conversations) {
                    let conversation = this.conversations[i];
                    if (conversation.participants[0].id == user.id) {
                        this.conversations[i].participants[0].isOnline = false;
                        return;
                    }
                }
                this.updateUserStatus(user.id, false);
            })
            .listenForWhisper("typing", (e) => {
                console.log("استقبال حدث كتابة:", e); // للتأكد في الـ Console
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
        updateUserStatus(userId, status) {
            this.conversations.forEach((conversation) => {
                // نبحث عن المشارك الذي ليس "أنا" (صاحب الحساب الحالي)
                let participant = conversation.participants.find(p => p.id == userId);
                if (participant) {
                    participant.isOnline = status;
                }
            });
        },
        isOnline(user) {
            if (!user) return false;
            let usersArray = Array.isArray(this.users) ? this.users : (this.users?.data || []);
            let found = usersArray.find(u => u && u.id == user.id);
            return found ? found.isOnline : false;
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
            fetch(`/conversations/${conversation.id}/read`, {
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
            fetch(`/messages/${message.id}`, {
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
        playAudio() {
            if (this.soundEnabled) {
                this.alertAudio.pause();
                this.alertAudio.currentTime = 0;
                this.alertAudio.play().catch(e => console.log("Audio play failed:", e));
            }
        },
    },
});
chatApp.component("Messenger", Messenger);
chatApp.component("ChatList", ChatList);
chatApp.component("Friends", Friends);
chatApp.component("CreateChat", CreateChat);
chatApp.mount("#chat-app");
