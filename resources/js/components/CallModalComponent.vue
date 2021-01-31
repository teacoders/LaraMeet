<template>
    <div class="modal-dialog modal-custom-dialog" role="document"    >
        <div class="modal-content text-white" style="height:300px;background-color: rgba(0,0,0,0.4) !important;width:300px" >
            <div class="modal-body text-center">
                <div class="mt-4">
                    <h2 class="mb-6">Incoming Call</h2>
                </div>
                <div class="mt-8">
                    <h2 style="font-weight:bold text-capitalize">{{ caller.name }}</h2>
                </div>
            </div>
            <div class="mx-auto">
                <button type="button" class="btn btn-success call_accept_btn h3 mx-4" @click="callResponse(1)">
                    <i class="fa fa-phone text-white" aria-hidden="true" style="transform: rotate(53deg);"></i>
                </button>
                <button type="button" class="btn btn-danger call_accept_btn h3 mx-4" >
                    <i class="fa fa-phone text-white" aria-hidden="true" style="transform: rotate(220deg);" @click="callResponse(0)"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props : ['caller','callReceiver'],
    created(){
        console.log(this.caller);
    },
    methods:{
        callResponse(status){
            axios.post('/call-response',{ caller : this.caller, callReceiver : this.callReceiver, status : status })
            .then((res) => {
                if(status == 0){
                    this.$emit('declineCall')
                }else{
                    this.$emit('callAccepted',this.caller)
                }
            }).catch((err) => {
                console.log(err);
            })
        }
    }
}
</script>

<style>
    .modal-dialog{
        position: absolute;
        top: 15%;
        left: 50%;
        z-index: 10;
    }
</style>