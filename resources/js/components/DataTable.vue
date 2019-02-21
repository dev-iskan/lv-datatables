<template>
    <div class="card card-default">
        <div class="card-header">{{response.table.toUpperCase()}}</div>

        <div class="card-body">

            <div class="row">
                <div class="form-group col-md-10">
                    <label for="filter">Quick search</label>
                    <input type="text" id="filter" class="form-control" v-model="quickSearchQuery">
                </div>
                <div class="form-group col-md-2">
                    <label for="limit">Display records</label>
                    <select id="limit" v-model="limit" class="form-control" @change="getRecords()">
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="1000">1000</option>
                        <option value="">All</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col" v-for="column in response.displayable">
                            <span class="sortable" @click="sortBy(column)">{{column}}</span>
                            <div class="arrow" v-if="sort.key === column"
                                 :class="{
                                    'arrow--asc' : sort.order === 'asc',
                                    'arrow--desc' : sort.order === 'desc'
                                 }"
                            ></div>
                        </th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="record in filteredRecords ">
                        <td v-for="(columnValue, column) in record">
                            <template v-if="editing.id === record.id && isUpdatable(column)">
                                <div class="form-group">
                                    <input type="text" class="form-control" :class="{'is-invalid': editing.errors[column]}" v-model="editing.form[column]">

                                    <div class="invalid-feedback" v-if="editing.errors[column]">
                                        {{editing.errors[column][0]}}
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                {{columnValue}}
                            </template>

                        </td>
                        <td>
                            <a href="#" @click.prevent="edit(record)" v-if="editing.id !== record.id">Edit</a>
                            <template v-if="editing.id === record.id">
                                <a href="#" @click.prevent="update">Save</a><br>
                                <a href="#" @click.prevent="editing.id = null">Cancel</a>
                            </template>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import queryString from 'query-string'
    export default {
        props: {
            endpoint: {
                required: true,
                type: String
            }
        },

        data () {
            return {
                response: {
                    table: '',
                    displayable: [],
                    records: []
                },
                sort: {
                    key: 'id',
                    order: 'asc'
                },
                quickSearchQuery: '',
                limit: 50,
                editing: {
                    id: null,
                    form: {

                    },
                    errors: []
                }
            }
        },

        created () {
            this.getRecords()
        },

        computed: {
            filteredRecords () {
                let data = this.response.records;

                //filter quick search
                // return array filtered by row
                data = data.filter((row)=> {
                    // sort row keys on some condition
                    return Object.keys(row).some((key) => {
                        // compare string value of row key with comparison of quickSearchQuery
                        return String(row[key]).toLowerCase().indexOf(this.quickSearchQuery.toLowerCase()) > -1
                    })
                });

                // filter by column
                if (this.sort.key) {
                    data = _.orderBy(data, (i) => {
                        let value = i[this.sort.key];

                        if (!isNaN(parseFloat(value))) {
                            return parseFloat(value)
                        }

                        return String(i[this.sort.key]).toLowerCase()
                    }, this.sort.order)
                }

                return data
            }
        },

        methods: {
            getRecords () {
                return axios.get(`${this.endpoint}?${this.getQueryParameters()}`)
                    .then(response => {
                        this.response = response.data.data;
                    })
            },

            getQueryParameters () {
                return queryString.stringify({
                    limit: this.limit
                })
            },

            sortBy (column) {
                this.sort.key=column;
                this.sort.order = this.sort.order === 'asc' ? 'desc' : 'asc';
            },

            edit(record) {
                this.editing.errors = [];
                this.editing.id = record.id;
                //
                this.editing.form = _.pick(record, this.response.updatable)
            },

            isUpdatable (column) {
                return this.response.updatable.includes(column)
            },

            update () {
                axios.patch(`${this.endpoint}/${this.editing.id}`, this.editing.form)
                    .then(response => {
                        this.getRecords().then(() => {
                            this.editing.id = null
                            this.editing.form = {}
                        })
                    })
                    .catch((error) => {
                        this.editing.errors = error.response.data.errors
                    })
            }
        }
    }
</script>


<style lang="scss">
    .sortable {
        cursor: pointer;
    }

    .arrow {
        display: inline-block;
        vertical-align: middle;
        width: 0;
        height: 0;
        margin-left: 5px;
        opacity: .6;
        &--asc {
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-bottom: 4px solid #222;
        }

        &--desc {
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 4px solid #222;
        }

    }
</style>