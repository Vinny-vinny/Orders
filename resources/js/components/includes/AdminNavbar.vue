<template>
    <nav class="navbar navbar-expand-lg sticky-top flex-md-nowrap shadow-sm" style="background-color: #e3f2fd;">
        <div class="container-fluid">
            <router-link to="/" class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 nuxt-link-active mini">
                <span>Orders System</span>
            </router-link>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <!-- Replaced Dropdown with Logout Icon -->
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)" :class="{ 'opacity-25': processing }" :disabled="processing" @click="logout">
                            <!-- Logout Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M7.5 0a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 .5.5h.4a.6.6 0 0 1 .574.426l1.6 5.2a.5.5 0 0 1-.47.674H4.67a.5.5 0 0 1-.47-.674l1.6-5.2a.6.6 0 0 1 .574-.426h.4a.5.5 0 0 1 .5-.5h5V.5a.5.5 0 0 1 .5-.5z"/>
                            </svg>
                            <span class="d-none d-sm-inline ps-2">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script setup>
import {computed} from "vue";
import useAuth from "@/composables/auth";
import {useAuthStore} from "@/store/auth";

const auth = useAuthStore();

const user = computed(() => auth.user);
const {processing, logout} = useAuth();
</script>

<style scoped>
</style>
