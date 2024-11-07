<template>
    <div class="row justify-content-center my-2">
        <div class="col-md-12">
            <div class="card border-0">
                <div class="card-header bg-transparent">
                    <h5 class="float-start">Orders</h5>
                    <router-link  :to="{ name: 'orders.create' }" class="btn btn-primary btn-sm float-end">
                        Create Order
                    </router-link>
                </div>
                <div class="card-body shadow-sm">
                    <div class="mb-4">
                        <!-- Single search input -->
                        <input v-model="searchQuery" type="text" placeholder="Search by Order# or Product..." class="form-control w-25" @input="debouncedSearch" />
                    </div>
                    <div class="table-responsive">
                        <table class="table" width="100%">
                            <thead>
                            <tr>
                                <th class="text-start">ORDER ID</th>
                                <th class="text-left">ORDER #</th>
                                <th class="text-left">USERNAME</th>
                                <th class="text-left">PRODUCT</th>
                                <th class="text-left">QTY</th>
                                <th class="text-left">AMOUNT</th>
                                <th class="text-left">DATE</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="order in orders.data" :key="order.id">
                                <td class="text-sm">{{ order.id }}</td>
                                <td class="text-sm">{{ order.order_number }}</td>
                                <td class="text-sm">{{ order.username }}</td>
                                <td class="text-sm">{{ order.product }}</td>
                                <td class="text-sm">{{ order.quantity }}</td>
                                <td class="text-sm">{{ order.amount }}</td>
                                <td class="text-sm">{{ order.date_created }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <Pagination :data="orders" :limit="3" @pagination-change-page="handlePageChange" class="mt-4" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { debounce } from 'lodash';
import useOrders from '../../composables/orders.js';

const searchQuery = ref('');

const { orders, getOrders } = useOrders();


// Debounced search handler
const debouncedSearch = debounce(() => {
    getOrders(1, searchQuery.value);  // No ordering required now
}, 300);

// Handle pagination change
const handlePageChange = (page) => {
    getOrders(page, searchQuery.value);  // No ordering required now
};

// Get categories on initial load
onMounted(() => {
    getOrders();
});
</script>
