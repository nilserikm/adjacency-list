<template>
    <div class="nodi-entry">
        <div class="node-body">
            <div
                class="node-title"
                :class="{ 'root-title': isRoot }"
            >
                <a>{{ title }}</a>
            </div>

            <div class="node-entries">
                <div class="node-efficiency">
                    <span>Eff.Hours:</span>
                    <span>0</span>
                </div>
                <div class="node-registered">
                    <span>Reg.Hours:</span>
                    <span>0</span>
                </div>
                <div v-if="hasChildren" class="node-sum">
                    <span>Sum:</span>
                    <span>{{ sum }}</span>
                </div>
                <div class="node-estimate">
                    <span>Estimate:</span>
                    <span>{{ estimate }}</span>
                </div>
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
            </div>
        </div>
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
    .nodi-entry {
        margin-left: 20px;
        margin-bottom: 5px;
        border-bottom: 1px solid lightgray;

        .node-body {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;

            .node-title {
                font-weight: bold;
                font-size: 20px;
                background-color: lightgray;
                padding: 5px 10px;
            }

            .root-title {
                background-color: slategray;

                a {
                    color: white;
                }
            }

            .node-entries {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;

                .node-estimate,
                .node-sum,
                .node-efficiency,
                .node-registered,
                button {
                    display: flex;
                    flex-direction: column;
                    margin-right: 20px;

                    span:last-child {
                        font-weight: bold;
                        font-size: 16px;
                        text-align: center;
                    }
                }
            }
        }

    }

    .hideNode {
        display: block;
    }
</style>

<script src="./tree-node.js"></script>