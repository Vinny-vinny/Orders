import { ref, reactive, inject } from 'vue'
import { useRouter } from "vue-router";
import {useAuthStore} from "../store/auth";

let user = reactive({
    name: '',
    email: '',
})

export default function useAuth() {
    const authStore = useAuthStore();
    const processing = ref(false)
    const validationErrors = ref({})
    const router = useRouter()
    const swal = inject('$swal')

    const loginForm = reactive({
        email: '',
        password: '',
        remember: false
    })

    const resetForm = reactive({
        email: '',
        token: '',
        password: '',
        password_confirmation: ''
    })
    const submitLogin = async () => {
        if (processing.value) return

        processing.value = true
        validationErrors.value = {}

        await axios.post('/api/login', loginForm)
            .then(async response => {
                 console.log(response.data)
                 console.log(response.data.success)
                 console.log(response.data.success===false)
                if (response.data.success === false) {
                    // If the login failed, show the general error message
                    validationErrors.value = { general: response.data.message || 'Unknown error' };
                } else {
                    // If login is successful, fetch the user and navigate
                    await authStore.getUser();
                    await loginUser();

                    // Optionally show success message here
                    // swal({
                    //     icon: 'success',
                    //     title: 'Login successful',
                    //     showConfirmButton: false,
                    //     timer: 1500
                    // });

                    await router.push({ name: 'home.index' });
                }
            })
            .catch(error => {
                if (error.response.status === 401){
                    validationErrors.value = { general: "Invalid credentials, please double check your email or password." }
                }
                else if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
                // If there's some other error (network or unexpected)
                else {
                    validationErrors.value = { general: error.response?.data?.message || 'An unexpected error occurred.' }
                }
            })
            .finally(() => processing.value = false)
    }
    const loginUser = () => {
        user = authStore.user
    }

    const getUser = async () => {
        if (authStore.authenticated) {
            await authStore.getUser()
            await loginUser()
        }
    }

    const logout = async () => {
        if (processing.value) return

        processing.value = true

        axios.post('/api/logout')
            .then(response => {
                user.name = ''
                user.email = ''
                authStore.logout()
                router.push({ name: 'auth.login' })
            })
            .catch(error => {
                // swal({
                //     icon: 'error',
                //     title: error.response.status,
                //     text: error.response.statusText
                // })
            })
            .finally(() => {
                processing.value = false
                // Cookies.remove('loggedIn')
            })
    }

    return {
        loginForm,
        resetForm,
        validationErrors,
        processing,
        submitLogin,
        user,
        getUser,
        logout
    }
}
