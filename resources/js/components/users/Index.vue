<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Users</div>

                <div class="card-body">
                    <user v-for="user in users" :key="user.id" :user="user"></user>
                    <pagination v-if="meta.current_page" :meta="meta" @pagination:switched="switchPage "></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import User from './partials/User'
    import Pagination from '../pagination/Pagination'
    export default {
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
        watch: {
            '$route.query' (query) {
                this.getUsers(query.page)
            }
        },
        created () {
            this.getUsers()
        },
        methods: {
            getUsers (page=this.$route.query.page) {
                axios.get(`/api/users`, {
                    params: {
                        page
                    }
                })
                    .then((response) => {
                        this.users = response.data.data
                        this.meta = response.data.meta
                    })
            },
            switchPage (page) {
                this.$router.replace({
                    name:'users.index',
                    query: {
                        page
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
