<template>
    <div class="nodi-entry">
        <span class="node-title">{{ title }}</span>
        <button @click="expand">Expand</button>
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
        }
    }
</script>