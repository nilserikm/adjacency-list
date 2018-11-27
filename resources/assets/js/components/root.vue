<template>
    <div>
        <div class="title-container">
            <div>
                <h6><span>Bygning</span>: {{ title }}</h6>
            </div>
            <div>
                <scale-loader
                    :loading="loading"
                    height="20px"
                    width="8px"
                ></scale-loader>
                <div
                    v-if="!loading"
                    class="nodes-number"
                >
                    <node
                        :children="descendants"
                        :title="node.title"
                        :estimate="node.estimate"
                        :show="false"
                    >
                    </node>
                    {{ title }}
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
    .title-container {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        margin-bottom: 10px;
        border-bottom: 3px solid lightgray;

        h6 {
            font-weight: bold;
            font-size: 20px;

            span {
                font-weight: normal;
                font-size: 16px;
            }
        }

        div:last-child {
            /*margin-left: 20px;*/
            /*text-align: center;*/
            padding-top: 5px;
        }
    }
</style>

<script>
    export default {
        props: {
            'loading': {
                type: Boolean,
                default: true
            },

            'title': {
                type: String,
                default: ""
            },

            'id': {
                type: Number,
                default: null
            },

            'descendants': null
        },

        name: 'root',

        data() {
            return {
                //
            }
        },

        methods: {
            //
        }
    }
</script>