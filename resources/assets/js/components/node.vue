<template>
    <div class="nodi-entry">
        <span class="node-title">{{ title }}</span>
        <button
            v-if="hasChildren"
            type="button"
            class="btn btn-sm"
            @click="expand"
        >
            Expand
        </button>
        <node
            v-for="node in nodes"
            :key="node.id"
            v-if="display"
            :nodes="node.children"
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
        props: [
            'nodes',
            'title',
            'estimate',
            'show'
        ],
        name: 'node',
        data() {
            return {
                display: null
            }
        },

        mounted() {
            this.display = this.show;
        },

        methods: {
            expand() {
                this.display = !this.display;
            }
        },

        computed: {
            hasChildren() {
                if (typeof this.nodes === "undefined") {
                    return false;
                } else if (this.nodes === null) {
                    return false;
                } else if (! this.nodes.length > 0) {
                    return false;
                } else {
                    return true;
                }
            }
        }
    }
</script>