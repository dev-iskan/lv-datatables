<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Users</div>

                <div class="card-body">
                    <user v-for="user in users" :key="user.id" :user="user"></user>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import User from './partials/User'
    export default {
        props: {
            endpoint: {
                required: true,
                type: String
            }
        },
        components: {
            User
        },
        data () {
            return {
                users: []
            }
        },
        created () {
            axios.get(`${this.endpoint}`)
                .then((response) => {
                    this.users = response.data.data
                })
        }
    }
</script>

<style scoped>

</style>
