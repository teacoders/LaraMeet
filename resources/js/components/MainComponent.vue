<template>
  <div>
    <div class="h-100" >
        <div class="row m-0 p-0 h-100" v-if="!isCallAccepted">
            <user-list-component @showChat="showUserChat"></user-list-component>
            <chat-component v-if="receiver" v-on:callAccepted="showVideo" :receiver="receiver" :sender="loggedin_user" :isCalling="isUserCalling" ></chat-component>
        </div>
        <call-modal-component v-if="isUserCalling" v-on:callAccepted="showVideo" :caller="caller" :callReceiver="callReceiver" @declineCall="declineCall"></call-modal-component>
    </div>
    <video-component v-if="isCallAccepted" v-on:endCall="endCall" :pusherKey="pusherKey" :pusherCluster="pusherCluster" :caller="caller" :callReceiver="callReceiver" :logged_user_id="loggedin_user"></video-component>
</div>
</template>

<script>
import CallModalComponent from './CallModalComponent.vue'
import ChatComponent from './ChatComponent.vue'
import UserListComponent from './UserListComponent.vue'
export default {
  components: { CallModalComponent, ChatComponent, UserListComponent },
  props :['loggedin_user','pusherKey', 'pusherCluster'],
  data(){
    return {
      receiver : null,
      isUserCalling : false,
      caller : null,
      callReceiver : null,
      isCallAccepted : false
    }
  },
  created(){
    Echo.private(`callToUser.${this.loggedin_user.id}`)
    .listen('CallToUser',(e) => {
      this.caller = e.caller;
      this.isUserCalling = true;
      this.callReceiver = this.loggedin_user
    })
    Echo.private(`endCall.${this.loggedin_user.id}`)
    .listen('EndCall',(e) => {
      this.isUserCalling = false
      this.isCallAccepted = false
    })
  },
  methods:{
    showUserChat(receiver){
      this.receiver = receiver
    },
    declineCall(){
      this.isUserCalling = false
    },
    showVideo(user){
      if(user.callReceiver){
        this.caller = user.caller;
        this.callReceiver = user.callReceiver
      }
      this.isUserCalling = false
      this.isCallAccepted = true
    },
    endCall(){
      this.isUserCalling = false
      this.isCallAccepted = false
      let userId = this.loggedin_user.id == this.caller.id ? this.callReceiver.id : this.caller.id
      axios.post('/end-call',{userId : userId})
      .then((res) => {
        console.log(res);
      })
    }
  }
}
</script>

<style>

</style>