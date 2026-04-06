<template>
    <div class="chat-footer pb-3 pb-lg-7 position-absolute bottom-0 start-0">
        <!-- Chat: Files -->
        <div class="dz-preview bg-dark" id="dz-preview-row" data-horizontal-scroll=""></div>
        <!-- Chat: Files -->

        <!-- Chat: Form -->
        <form method="POST" action="messages" @submit.prevent="sendMessage()"
            class="chat-form rounded-pill bg-dark" data-emoji-form="">
            <input type="hidden" name="_token" :value="$root.csrfToken">
            <input type="hidden" name="conversation_id" :value="conversation? conversation.id : 0">
            <div class="row align-items-center gx-0">
                <div class="col-auto">
                    <a href="#" @click.prevent="selectFile()" class="btn btn-icon btn-link text-body rounded-circle"
                        id="dz-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-paperclip">
                            <path
                                d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48">
                            </path>
                        </svg>
                    </a>
                </div>


                <div class="col">
                    <div class="input-group">
                      <textarea
                        name="message"
                        v-model="message"
                        @focus="conversation.id ? $root.markAsRead() : null"
                        @keypress="startTyping()"
                        class="form-control px-0 txt1"
                        id="inputMessage"
                        placeholder="Type your message..."
                        rows="1">
                    </textarea>

                        <button type="button" id="btn2" class="input-group-text text-body pe-0 fa-solid fa-face-smile fa-lg">
                        </button>
                    </div>
                </div>

                <div class="col-auto">
                    <button class="btn btn-icon btn-primary rounded-circle ms-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-send">
                            <line x1="22" y1="2" x2="11" y2="13">
                            </line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
        <!-- Chat: Form -->
    </div>
</template>
<script>


export default {
    props:[
        'conversation'
    ],
    data() {
        return {
            message:"",
            attachment:"",
            start_typing:false,
            timeout:null
        }
    },
   methods: {
    startTyping() {
        // منع الـ Whisper إذا كانت المحادثة جديدة (ليس لها ID)
        if (!this.$root.conversation.id || !this.$root.chatChannel) {
            return;
        }

        if (!this.start_typing) {
            this.start_typing = true;
            this.$root.chatChannel.whisper('typing', {
                id: this.$root.userId,
                conversation_id: this.$root.conversation.id,
            });
        }

        if (this.timeout) {
            clearTimeout(this.timeout);
        }

        this.timeout = setTimeout(() => {
            this.start_typing = false;
            this.$root.chatChannel.whisper('stopped-typing', {
                id: this.$root.userId,
                conversation_id: this.$root.conversation.id,
            });
        }, 1000);
    },

    sendMessage() {
        this.$root.playAudio();
        let emoji = document.getElementById('inputMessage').value;
        if (this.message == "" && emoji == "" && !this.attachment) {
            return; // فارغ، لا تفعل شيئاً
        }

        this.message = emoji || this.message;

        let formData = new FormData();

        // الخلل هنا: يجب التعامل مع المحادثة الجديدة
        if (this.$root.conversation.id && this.$root.conversation.id != 0) {
            formData.append('conversation_id', this.$root.conversation.id);
        } else {
            // نرسل الـ user_id الخاص بالصديق لأن المحادثة لم تُنشأ بعد
            // نفترض أن أول شخص في المشاركين هو الصديق
            formData.append('user_id', this.$root.conversation.participants[0].id);
            formData.append('conversation_id', 0);
        }

        formData.append('message', this.message);
        formData.append('_token', this.$root.csrfToken);

        if (this.attachment) {
            formData.append('attachment', this.attachment);
        }

        fetch('messages', {
            method: "POST",
            headers: { "Accept": "application/json" },
            body: formData
        })
        .then(response => response.json())
        .then(json => {
            // تحديث الـ ID في الـ root إذا كانت هذه أول رسالة
            if (!this.$root.conversation.id || this.$root.conversation.id == 0) {
                this.$root.conversation.id = json.conversation_id;
            }

            this.$root.messages.push(json);

            // // عمل Scroll للأسفل
            // this.$nextTick(() => {
            //     var container = document.getElementById("chat-body");
            //     if (container) container.scrollIntoView({ behavior: 'smooth', block: 'end' });
            // });

            // داخل sendMessage في ChatFooter
            this.$nextTick(() => {
                const container = document.getElementById("chat-content"); // استهدف الحاوية الأب
                if (container) {
                    container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
                }
            });
        });

        // تصفير الحقول
        this.message = "";
        document.getElementById('inputMessage').value = ""; // تصفير الـ textarea يدوياً بسبب الـ emoji plugin
        this.attachment = null;
    },

    selectFile() {
        let fileElm = document.createElement('input');
        fileElm.setAttribute('type', 'file');
        fileElm.addEventListener('change', () => {
            if (fileElm.files.length == 0) return;
            this.attachment = fileElm.files[0];
            this.sendMessage();
        });
        fileElm.click();
    }
}
}
</script>
