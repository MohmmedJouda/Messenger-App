<template>
    <div class="chat-footer pb-3 pb-lg-7 position-absolute bottom-0 start-0">
        <!-- Chat: Files -->
        <div class="dz-preview bg-dark" id="dz-preview-row" data-horizontal-scroll=""></div>
        <!-- Chat: Files -->

        <!-- Chat: Form -->
        <form method="POST" action="/api/messages" @submit.prevent="sendMessage()"
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
                        <textarea name="message" v-model="message" @focus="$root.markAsRead()" @keypress="startTyping()"
                            class="form-control px-0 txt1" id="inputMessage" placeholder="Type your message..." rows="1"
                            data-emoji-input="" data-autosize="true"
                            style="overflow: hidden; overflow-wrap: break-word; resize: none; height: 47.2px;"></textarea>

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
    methods:{
        startTyping(){
            console.log(!this.start_typing);

            if(!this.start_typing){
                this.start_typing = true;
                this.$root.chatChannel.whisper('typing',{
                id: this.$root.userId,
                conversation_id: this.$root.conversation.id,

            });
            }
            if(this.timeout){
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(()=>{
                this.start_typing = false;
                this.$root.chatChannel.whisper('stopped-typing',{
                id: this.$root.userId,
                conversation_id: this.$root.conversation.id,
                });
            },1000);
        },
        sendMessage(){
            // check if value of message is empty or not.

            let emoji = document.getElementById('inputMessage').value;
            if (this.message == "" && emoji == "") {
                console.log("empty");
                // alert('Please Enter The Message..')
            }else{
                // add emoji to input
                this.message = emoji;
                // send the message

                let data = {
                    conversation_id: this.$root.conversation.id,
                    message: this.message,
                    _token: this.$root.csrfToken
                };
                let formData = new FormData();
                formData.append('conversation_id', this.$root.conversation.id);
                formData.append('message', this.message);
                formData.append('_token', this.$root.csrfToken);
                formData.append('attachment', this.attachment);
                if (this.attachment) {
                    formData.append('attachment', this.attachment);
                }
                fetch('/api/messages', {
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(json => {
                        this.$root.messages.push(json);
                        var container = document.getElementById("chat-body");
                        container.scrollIntoView();
                    });
                this.message = "";
                this.attachment = null;
            }
        },
        selectFile(){
            let fileElm = document.createElement('input');
            fileElm.setAttribute('type','file');
            fileElm.addEventListener ('change',() => {
                if(fileElm.files.length == 0){
                    return;
                }
            this.attachment = fileElm.files[0];
                this.sendMessage();
            });
            fileElm.click();
        }
    }
}
</script>
