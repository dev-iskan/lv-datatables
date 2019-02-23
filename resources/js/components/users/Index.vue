<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Users</div>

                <div class="card-body">
                    <user v-for="user in users" :key="user.id" :user="user"></user>
                    <pagination :meta="meta" @pagination:switched="getUsers"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import User from './partials/User'
    import Pagination from '../pagination/Pagination'
    export default {
        props: {
            endpoint: {
                required: true,
                type: String
            }
        },
        components: {
            User,
            Pagination
        },
        data () {
            return {
                users: [],
                meta: {}
            }
        },
        created () {
            this.getUsers()
        },
        methods: {
            getUsers (page=1) {
                axios.get(`${this.endpoint}`, {
                    params: {
                        page
                    }
                })
                    .then((response) => {
                        this.users = response.data.data
                        this.meta = response.data.meta
                    })
            }
        }
    }
</script>

<style scoped>

</style>
