<template>
<div class="container py-8">
    <!-- Title -->
    <div class="mb-8">
        <h2 class="fw-bold m-0">Friends</h2>
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
                    placeholder="Search messages or users"
                    aria-label="Search for messages or users..." />
            </div>
        </form>

        <!-- Invite button -->
        <div class="mt-5">
            <a href="#"
                class="btn btn-lg btn-primary w-100 d-flex align-items-center"
                data-bs-toggle="modal" data-bs-target="#modal-invite">
                Find Friends

                <span class="icon ms-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-user-plus">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20"
                            y2="14"></line>
                        <line x1="23" y1="11" x2="17"
                            y2="11"></line>
                    </svg>
                </span>
            </a>
        </div>
    </div>

    <!-- List -->

    <div v-for="friend in this.$root.users.data" :key="friend.id" class="card-list mb-6">
        <!-- Card -->
        <div class="card border-0">
            <div class="card-body">
                <div class="row align-items-center gx-5">
                    <div class="col-auto">
                        <a href="#" class="avatar">
                            <img class="avatar-img"
                                :src=" friend.avatar_url " alt="" />
                        </a>
                    </div>

                    <div class="col">
                        <h5><a href="#">{{ friend.name}}</a></h5>
                        <p v-if="friend.last_login != null">{{$root.moment(friend.last_login).fromNow() }}</p>
                        <p v-if="friend.last_login == null">Last seen a long time ago</p>
                    </div>

                    <div class="col-auto">
                        <!-- Dropdown -->
                        <div class="dropdown">
                            <a class="icon text-muted" href="#"
                                role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24"
                                    viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-more-vertical">
                                    <circle cx="12" cy="12" r="1">
                                    </circle>
                                    <circle cx="12" cy="5" r="1">
                                    </circle>
                                    <circle cx="12" cy="19" r="1">
                                    </circle>
                                </svg>
                            </a>

                            <ul class="dropdown-menu">
                                <li>
                                    <a @click="setConversation(conversation)" class="dropdown-item" href="#">New
                                        message</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Edit
                                        contact</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger"
                                        href="#">Block user</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>


</div>
</template>

<script>
import axios from 'axios';
export default {
    data() {
        return {
            // friends:[],
            friends:this.$root.users.data,
            userInfo: {},
        }
    },
    mounted() {
        // this.fetchFriends();
    },
    created(){
        this.fetchUser();
    },
    methods:{
        fetchFriends(){

        // fetch('/api/friends', {
        //     method: 'GET',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'Accept': 'application/json',
        //       }
        //     })
        //         .then(response =>{
        //             console.log("this is data from api "+ response.data);

        //         })

        },
        // sortByLetter(){
        //     let last_letter = '';
        //     for (friends in friend){
        //         letter = substr(friend.name, 0, 1);
        //         if (letter != last_letter){
        //                                 <div class="my-5">
        //                 <small
        //                     class="text-uppercase text-muted">{{ letter }}
        //                 </small>
        //             </div>
        //             last_letter = $letter;
        //         }

        //     }

        // },

        async fetchUser() {
            try {
                const response = await axios.get('/api/user');
                this.userInfo = response.data;
                console.log(this.userInfo);


            } catch (error) {
                console.error(error);
            }
        }


    }
}
</script>
