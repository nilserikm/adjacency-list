<template>
    <div class="nodi-entry">
        <span class="node-title">{{ id }} {{ title }}</span>
        <span class="node-estimate">{{ estimate }}</span>
        <span class="node-sum">{{ sum }}</span>
        <button
            v-show="hasChildren"
            type="button"
            class="btn btn-sm"
            @click="expand"
        >
            Expand
        </button>
        <node
            v-for="node in children"
            :key="node.id"
            :id="node.id"
            :isRoot="false"
            v-show="display"
            :children="node.children"
            :loading="true"
            :title="node.title"
            :estimate="node.estimate"
            :show="false"
        >
        </node>
    </div>
</template>

<style lang="scss">
    .hideNode {
        display: block;
    }

    .node-title {
        margin-right: 200px;
    }

    .nodi-entry {
        margin-left: 20px;
        margin-bottom: 5px;
        border-bottom: 1px solid lightgray;
    }
</style>

<script>
    export default {
        props: {
            'children': {
                type: Array,
                default: function() { return [] }
            },
            'estimate': {
                type: Number,
                default: null
            },
            'id': {
                type: Number,
                default: 0
            },
            'isRoot': {
                type: Boolean,
                default: false
            },
            'loading': {
                type: Boolean,
                default: true
            },
            'title': {
                type: String,
                default: ""
            },
            'show': {
                type: Boolean,
                default: false
            }
        },
        name: 'node',
        data() {
            return {
                calculation: 0,
                display: null,
                doCalculation: false,
                sum: 0
            }
        },

        mounted() {
            this.display = this.show;
            if (! this.isRoot) {
                this.doCalculation = true;
            }
        },

        methods: {
            calculate(estimate, children) {
                if (!this.countChildren(children)) {
                    this.sum = estimate;
                } else {
                    for (let i = 0; i < children.length; i++) {
                        this.sum = estimate += this.calculate(children[i]['estimate'], children[i]['children']);
                    }
                }

                return this.sum;
            },

            expand() {
                this.display = !this.display;
            },

            countChildren(children) {
                if (typeof children === "undefined") {
                    return false;
                } else if (children === null) {
                    return false;
                } else if (! children.length > 0) {
                    return false;
                } else {
                    return true;
                }
            }
        },

        computed: {
            hasChildren() {
                if (typeof this.children === "undefined") {
                    return false;
                } else if (this.children === null) {
                    return false;
                } else if (! this.children.length > 0) {
                    return false;
                } else {
                    return true;
                }
            }
        },

        watch: {
            'loading': function() {
                console.log("triggered");
                if (this.isRoot) {
                    if (this.countChildren(this.children)) {
                        this.calculate(this.estimate, this.children);
                    } else {
                        this.sum = this.estimate;
                    }
                } else {
                    this.sum = this.estimate;
                }
            },

            'doCalculation': function() {
                if (this.countChildren(this.children)) {
                    this.calculate(this.estimate, this.children);
                } else {
                    this.sum = this.estimate;
                }
            }
        }
    }
</script>