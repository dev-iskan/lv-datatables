<template>
    <div class="card mb-3">
        <div class="row no-gutters">
            <div class="col-md-2">
                <a href="#" @click.prevent=""><img src="https://via.placeholder.com/150" class="card-img-top" alt=""></a>
            </div>
            <div class="card-body">
                <a href="#" @click.prevent=""><h5 class="card-title text-primary">{{user.name}}</h5></a>
                <p class="card-text">{{user.email}}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            user: {
                required: true,
                type: Object
            }
        }
    }
</script>

<style scoped>

</style>
