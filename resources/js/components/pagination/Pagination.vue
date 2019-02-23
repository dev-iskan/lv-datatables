<template>
    <ul class="pagination" role="navigation">
        <!--go to previous page-->
        <li class="page-item" :class="{'disabled': meta.current_page === 1}">
            <a href="#" @click.prevent="switched(meta.current_page - 1)" class="page-link">&lsaquo;</a>
        </li>

        <template v-if="section > 1">
            <li class="page-item">
                <a class="page-link" href="#" @click.prevent="switched(1)">1</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#" @click.prevent="goBackwardSection">...</a>
            </li>
        </template>

        <!--go to page number ...-->
        <li class="page-item" :class="{'active': page === meta.current_page}" v-for="page in pages" :key="page">
            <a class="page-link" href="#" @click.prevent="switched(page)">{{ page }}</a>
        </li>

        <template v-if="section < sections">
            <li class="page-item">
                <a class="page-link" href="#" @click.prevent="goForwardSection">...</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#" @click.prevent="switched(meta.last_page)">{{meta.last_page}}</a>
            </li>
        </template>

        <!--go to next page-->
        <li class="page-item" :class="{'disabled': meta.current_page === meta.last_page}">
            <a href="#" class="page-link" @click.prevent="switched(meta.current_page + 1)">&rsaquo;</a>
        </li>
    </ul>
</template>

<script>
    export default {
        props: {
            meta: {
                required: true
            }
        },
        data () {
            return {
              numbersPerSection: 7
            }
        },
        computed: {
            sections () {
                return Math.ceil(this.meta.last_page / this.numbersPerSection)
            },

            section () {
                return Math.ceil(this.meta.current_page / this.numbersPerSection)
            },

            lastPage () {
                let last = ((this.section - 1)*this.numbersPerSection) + this.numbersPerSection

                if (this.section === this.sections) {
                    last = this.meta.last_page
                }

                return last
            },

            pages () {
                return _.range(
                    (this.section - 1) * this.numbersPerSection + 1,
                    this.lastPage + 1)
            }
        },
        mounted () {
            if (this.meta.current_page > this.meta.last_page) {
                this.switched(this.meta.last_page)
            }
        },
        methods: {
            switched(page) {
                if (this.pageIsOutOfBound(page)) {
                    return
                }
                this.$emit('pagination:switched', page)
            },

            pageIsOutOfBound (page) {
                return page <=0 || page>this.meta.last_page
            },

            goForwardSection () {
                this.switched(this.firstPageOfSection(this.section + 1))
            },

            goBackwardSection () {
                this.switched(this.firstPageOfSection(this.section - 1))
            },

            firstPageOfSection (section) {
                return (section - 1) * this.numbersPerSection + 1
            }
        }
    }
</script>

<style scoped>

</style>
