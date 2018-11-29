<template>
    <div class="nodi-entry">
        <span class="node-title">{{ title }}</span>
        <span class="node-estimate" style="font-weight: bold; margin-right: 20px;">{{ estimate }}</span>
        <span v-if="hasChildren" class="node-sum" style="border-bottom: 3px solid indianred;">{{ sum }}</span>
        <button
            type="button"
            class="btn btn-sm"
            @click="incrementEstimate"
        >
            +
        </button>
        <button
            v-show="hasChildren"
            type="button"
            class="btn btn-sm"
            @click="expand"
        >
            Expand
        </button>
        <tree-node
            v-for="node in children"
            :key="node.id"
            :id="node.id"
            :isRoot="false"
            v-if="display"
            :children="node.children"
            :loading="true"
            :title="node.title"
            :estimate="node.estimate"
            :show="false"
            @increment="increment"
            @recalculate="recalculate"
        >
        </tree-node>
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

<script src="./tree-node.js"></script>