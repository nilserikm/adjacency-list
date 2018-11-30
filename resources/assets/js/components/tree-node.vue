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
                    <span>{{ efficiencyHours > 0 ? efficiencyHours : 0 }}</span>
                </div>
                <div class="node-registered">
                    <span>Reg.Hours:</span>
                    <span>{{ registeredHours > 0 ? registeredHours : 0 }}</span>
                </div>
                <div v-if="hasChildren" class="node-sum">
                    <span>Sum:</span>
                    <span :class="{ 'flash-sum': flashBackground.sum }">{{ sum }}</span>
                </div>
                <div class="node-estimate">
                    <span>Estimate:</span>
                    <span :class="{ 'flash-estimate': flashBackground.estimate }">{{ estimate }}</span>
                </div>
                <button
                    type="button"
                    class="btn btn-sm btn-primary"
                    @click="incrementEstimate"
                >
                    +
                </button>
                <button
                    v-show="hasChildren"
                    type="button"
                    class="btn btn-sm btn-info"
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
            :efficiencyHours="node.efficiencyHours"
            :estimate="node.estimate"
            :registeredHours="node.registeredHours"
            :show="false"
            @increment="increment"
            @recalculateEstimate="recalculateEstimate"
        >
        </tree-node>
    </div>
</template>

<style lang="scss">
    .flash-sum {
        background-color: indianred;
        color: white;
    }

    .flash-estimate {
        background-color: mediumseagreen;
        color: white;
    }

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
                background-color: #eee;
                padding: 5px 10px;

                a {
                    color: #5e5d5d;
                }
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

                div {
                    span:last-child {
                        padding: 5px;
                    }
                }

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