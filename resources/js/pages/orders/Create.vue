<template>
    <div class="row justify-content-center my-2">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h5>Create New Order</h5>
                </div>
                <div class="card-body">
                    <form @submit.prevent="submitOrder">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Product</label>
                            <v-select
                                v-if="products.length > 0"
                                v-model="order.product_id"
                                :options="products"
                                label="name"
                                :reduce="product => product.id"
                                option-value="id"
                                placeholder="Select a product"
                                class="form-control"
                                required
                            />
                            <div v-if="validationErrors.product_id" class="text-danger">
                                {{ validationErrors.product_id[0] }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input v-model="order.amount" type="number" class="form-control" id="amount" required />
                            <div v-if="validationErrors.amount" class="text-danger">
                                {{ validationErrors.amount[0] }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input v-model="order.quantity" type="number" class="form-control" id="quantity" required />
                            <div v-if="validationErrors.quantity" class="text-danger">
                                {{ validationErrors.quantity[0] }}
                            </div>
                        </div>
                        <button type="submit" :disabled="isLoading" class="btn btn-primary">
                            {{ isLoading ? 'Saving...' : 'Save Order' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import useOrders from '../../composables/orders'

const { order, products, getProducts, storeOrder, validationErrors, isLoading } = useOrders()

// Get products list on mounted
onMounted(() => {
    getProducts()
})

// Form submission handler
const submitOrder = () => {
    storeOrder(order)
}
</script>
