<template>
  <div class="col-md-8 chat p-0 w-100" >
        <div class="card" >
            <div class="card-header msg_head">
                <div class="d-flex bd-highlight">
                    <div class="img_cont">
                        <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img">
                        <!-- <span class="online_icon"></span> -->
                        <span class="online_icon" v-if="isReceiverOnline" ></span>
                        <span class="offline" v-else></span>
                    </div>
                    <div class="user_info">
                        <span>{{ receiver.name }}</span>
                        <p>{{ isReceiverOnline ? 'online' : 'offline'  }}</p>
                    </div>
                        <!-- <p class="text-danger" v-if="isCalling">Please wait</p> -->
                    <div class="video_cam" v-if="!isCalling && receiver.login_status" @click="callInit(receiver.id)">
                        <span><i class="fas fa-video"  ></i></span>
                    </div>
                </div>
            </div>
            <div class="card-body msg_card_body py-0" style="overflow:scroll" >
                <ul class="messages"  v-chat-scroll>
                    <li v-for="message in messages" :key="message.id"  >
                        <div class="d-flex justify-content-start mb-4" v-if="message.sender_id == sender.id" >
                            <div class="img_cont_msg">
                                <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img_msg">
                            </div>
                            <div class="msg_cotainer align-items-center justify-content-between" >
                                <span >{{ message.message }}</span>
                                <img :src="message.file" class="rounded img-fluid" v-if="message.mime_type == 'image'" alt="">
                                <video  controls v-else-if="message.mime_type == 'video'"></video>
                                <a target="_blank" :href="message.file" v-else-if="message.file">
                                    <i class="fa fa-download fa-3x"></i>
                                </a>
                                <p class="msg_time text-muted p-0 m-0">{{ message.created_at }}</p> 
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mb-4" v-else>
                            <div class="msg_cotainer_send justify-content-between align-items-center">
                                <span >{{ message.message }}</span>
                                <img :src="message.file" class="rounded img-fluid" v-if="message.mime_type == 'image'" alt="">
                                <video  controls v-else-if="message.mime_type == 'video'"></video>
                                <a target="_blank" :href="message.file" v-else-if="message.file" >
                                    <i class="fa fa-download fa-3x"></i>
                                </a>
                                <p class="msg_time_send text-muted p-0 m-0">{{ message.created_at }}</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="card-footer p-0">
                <p class="text-white mx-4 ">{{ fileCount }}</p>
                <form enctype="multipart/form-data" @submit.prevent="sendMessage()" >
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text attach_btn" @click="selectFile()"><i class="fas fa-paperclip"></i></span>
                        </div>
                        <input type="file" name="file" id="file"  class="d-none" @change="handleFile()" >
                        <input name="" class="form-control type_msg"  v-model="message" autofocus placeholder="Type your message..." >
                        <button class="input-group-append send_btn" type="submit" style="border:none;outline:none">
                            <span class="input-group-text my-auto" style="background: none;outline: none;border: none;color: white;"><i class="fas fa-location-arrow"></i></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props : ['receiver','sender'],
    data(){
        return {
            messages : [],
            message  : null,
            file     : null,
            fileCount: null,
            isCalling: false
        }
    },
    created(){
       this.getUserMessage(this.receiver.id,this.sender.id);
        Echo.private(`receiveMessage.${this.sender.id}`)
        .listen('.receiveMessage',(e) => {
            this.getUserMessage(e.receiverId,e.senderId)
            // console.log(e);
        })
        Echo.private(`sendCallResponse.${this.sender.id}`)
        .listen('SendCallResponse',(e) => {
            this.isCalling = false
            if(e.callReceiver){
                this.$emit('callAccepted',e)
            }
        })
    },
    computed:{
        isReceiverOnline(){
            if(this.receiver.login_status  == 1)
                return true;
            return;
        }
    },
    watch:{
        receiver(){
            this.getUserMessage(this.receiver.id,this.sender.id);
        }
    },
    methods:{
        sendMessage(){
            if(this.message || this.file){
                let formData = new FormData;    
                if(this.message)
                    formData.set('message', this.message);
                if(this.file)
                    formData.set('file',this.file);
                    formData.set('receiver',this.receiver.id)
                    formData.set('sender',this.sender.id)
                axios.post('/send-message',formData)
                .then((res) => {
                    this.messages.push(res.data)
                    this.message = null
                }).catch((err) => {
                    console.log(err);
                })
            }
        },
        handleFile(){
            let file = event.target.files[0];
            if(file){
                this.file = file;
                this.fileCount = '1 file selected'
            }else{
                this.fileCount = null
            }
        },
        getUserMessage(receiverId,senderId){
            axios.post('/get-user-messages',{ receiver : receiverId, sender : senderId })
            .then((res) => {
                if(res.status == 200){
                    this.messages = []
                    this.messages = res.data;
                }
            }).catch((err) =>{
                console.log(err);
            });
        },
        selectFile(){
            $('#file').click()
        },
        callInit(userId){
            axios.post('/call-init',{ receiver : userId, caller : this.sender })
            .then((res) => {
                console.log(res);
            }).catch((err) => {
                console.log(err);
            })
            this.isCalling = true
        }
    }
}
</script>

<style>

</style>