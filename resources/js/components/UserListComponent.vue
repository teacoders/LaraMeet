<template>
   <div class="col-md-4 chat p-0">
    <div class="card mb-sm-3 mb-md-0 contacts_card">
        <div class="card-header d-flex align-items-center">
            <div class="input-group">
                <input type="text" placeholder="Search..." name="" v-model="search" class="form-control search">
                <div class="input-group-prepend">
                    <span class="input-group-text search_btn"></span>
                </div>
            </div>
            <div class="ml-2 text-white">
                <span id="action_menu_btn" @click="handleLogout()" ><i class="fa fa-sign-out"></i></span>
            </div>
        </div>
        <div class="card-body contacts_body">
            <ul class="contacts" id="users_list">
                <li class="active" v-for="user in filtered_user" :key="user.id">
                    <div class="d-flex bd-highlight">
                        <div class="img_cont">
                            <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img">
                            <span class="online_icon" v-if="user.login_status == 1" ></span>
                            <span class="offline" v-else></span>
                        </div>
                        <div class="user_info" style="cursor:pointer" @click="showUserChat(user)"  >
                            <span>{{ user.name }}</span>
                            <p>{{ user.login_status == 1 ? 'online' : 'offline' }}</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data(){
        return {
            users : [],
            search : null
        }
    },
    created(){
        this.getUsers();
        Echo.channel('updateUserStatus')
        .listen('UserLoginStatus',(e) => {
           this.updateUserList(e.user)
        })
    },
    computed:{
        filtered_user(){
            return this.users.filter((user) => {
                if(this.search){
                    if(user.name.toLowerCase().includes(this.search.toLowerCase()))
                        return true;
                    return;
                }
                return true;
            })
        }
    },
    methods:{
        handleLogout(){
            document.getElementById('logout-form').submit();
        },
       getUsers(){
            axios.post('/get-users')
            .then((res) => {
                this.users = res.data
            }).catch((err) => {
                console.log(err);
            })
        },
        updateUserList(newUser){
            this.users.map((user) => {
                if(user.id == newUser.id){
                   user.login_status = newUser.login_status;
                }
            })
        },
        showUserChat(receiver){
            this.$emit('showChat',receiver)
        }
    }
}
</script>

<style>

</style>