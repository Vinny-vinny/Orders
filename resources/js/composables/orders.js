import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'

export default function useOrders() {
    const orders = ref({})
    const products = ref({})
    const order = ref({
        product_id: '',
        amount: '',
        quantity: '',
    })
    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')

    const getOrders = async (
        page = 1,
        searchQuery = ''
    ) => {
        axios.get('/api/orders?page=' + page +
            '&search=' + searchQuery
            )
            .then(response => {
                orders.value = response.data;
            })
    }

    const getOrder = async (id) => {
        axios.get('/api/orders/' + id)
            .then(response => {
                order.value = response.data;
            })
    }

    // Fetch products to populate the product_id select field
    const getProducts = async () => {
        axios.get('/api/products')  // Assuming you have a products API
            .then(response => {
                products.value = response.data;
            })
            .catch(error => {
                console.error('Error fetching products:', error);
            });
    }

    const storeOrder = async (order) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        axios.post('/api/orders', order.value)
            .then(response => {
                router.push({name: 'orders.index'})
                swal({
                    icon: 'success',
                    title: 'Order saved successfully'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    return {
        orders,
        products,
        order,
        getOrders,
        getOrder,
        getProducts,
        storeOrder,
        validationErrors,
        isLoading
    }
}
