<template>
  <div class="video-container" ref="video-container">
    <video class="video-here m-0 p-0 embed-responsive embed-responsive-16by9" ref="video-here" autoplay></video>  
    <video class="video-there m-0 p-0" ref="video-there" autoplay></video>
        <div class="text-center mb-4" style="margin-top: -100px !important;z-index: 1;position: relative;">
        <button class="btn btn-danger" @click="endCall()">
            <i class="fa fa-phone" aria-hidden="true"></i>
        </button>
        </div>
    </div>
</template>

<script>
import Pusher from 'pusher-js';
import Peer from 'simple-peer';
export default {
		props: ['pusherKey', 'pusherCluster','caller','callReceiver','logged_user_id'],
    	data() {
    		return {
      			channel: null,
      			stream: null,
				peers: {}, 
    		}
  		},
    	created(){
        	Echo.channel('endCall')
            	.listen('EndCall',e => {
                	this.$emit('showChat')
        		});
    	}, 
		async mounted(){
			console.log('caller'+this.caller.id);
			console.log('recevier'+this.callReceiver.id);
			console.log('loggedin'+this.logged_user_id.id);
			// const nav = document.createElement("script");
			// nav.setAttribute("src","/js/navigator.js");
			// nav.async = true;
			// document.body.appendChild(nav);
			this.setupVideoChat();
			setTimeout(function(){ 
				// if (this.logged_user_id.id == this.caller.id) {
                    console.log(this.callReceiver.id);
                    const data = this.getPeer(this.callReceiver.id,true);
                    console.log(data);
				// }
			}.bind(this), 2000);

		},			
    methods:{
        getPeer(userId, initiator) {
            console.log('get_peer');
				if(this.peers[userId] === undefined) {
					let peer = new Peer({
						initiator,
						stream: this.stream,
						trickle: false
					});
					peer.on('signal', (data) => {
						this.channel.trigger(`client-signal-${userId}`, {
							userId: this.logged_user_id.id,
							data: data
						});
					})
					.on('stream', (stream) => {
						console.log('stream');
						const videoThere = this.$refs['video-there'];
						videoThere.srcObject = stream;
					})
					.on('close', () => {
						const peer = this.peers[userId];
						if(peer !== undefined) {
							peer.destroy();
						}
						delete this.peers[userId];
					});
					this.peers[userId] = peer;
				}
				return this.peers[userId];
			},
			async setupVideoChat() {
				// To show pusher errors
				// Pusher.logToConsole = true;
				var constraints = { video: { width: 1280, height: 720 }};
				const stream = await navigator.mediaDevices.getUserMedia(constraints);
				const videoHere = this.$refs['video-here'];
				videoHere.srcObject = stream;
				this.stream = stream;
				const pusher = this.getPusherInstance();
				this.channel = pusher.subscribe('presence-video-chat');
				this.channel.bind(`client-signal-${this.logged_user_id.id}`, (signal) => 
				{
					const peer = this.getPeer(signal.userId, false);
					peer.signal(signal.data);
				});
            },
            getPusherInstance() {
				return new Pusher(this.pusherKey, {
					authEndpoint: '/auth/video_chat',
					cluster: this.pusherCluster,
					wsHost: '127.0.0.1',
					wsPort: 6001,
					wssPort: 6001,
					enabledTransports: ['ws'],
					auth: {
					headers: {
						'X-CSRF-Token': document.head.querySelector('meta[name="csrf-token"]').content
					}
					}
				});
			},
        endCall(){
            this.$emit('endCall');
        }
    },
}
</script>

<style>

</style>