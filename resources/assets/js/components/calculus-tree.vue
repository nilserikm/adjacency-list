<template>
    <div class="tree-container">
        <h1>Adjacency List Operations</h1>

        <!-- General statistics about the tree -->
        <div class="initial-data">
            <h2>Tree Stats</h2>
            <ul>
                <li>Feedback: <span class="dataEntry" v-bind:class="{ error : feedbackError }">{{ feedback }}</span></li>
                <li>Timing: <span class="dataEntry">{{ timing.backend }} / {{ timing.frontend }}</span></li>
                <li>Number of nodes in tree:
                    <span class="dataEntry">{{ allCount }}</span>
                    <span
                        v-if="countDifference !== null"
                        class="count-difference"
                    >
                        (
                        <span
                            class="plus-sign"
                            v-if="showPlus">+</span
                        >
                        {{ countDifference }}
                        )
                    </span>
                </li>
            </ul>
        </div>

        <!-- get random node info -->
        <div class="random-section">
            <div class="random-node">
                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    @click="randomNode"
                >
                    Get random node
                </button>
            </div>
            <div class="random-leaf">
                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    @click="randomLeaf"
                >
                    Get random leaf
                </button>
            </div>
        </div>

        <!-- Append node -->
        <div class="tree-section append-node">
            <small>Appends a new, single, and empty node to the node id given</small>
            <div>
                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    @click="appendNode"
                >
                    Append node
                </button>
                <input
                    id="append-node-id"
                    class="form-control"
                    type="text"
                    v-model="appendId"
                />
            </div>
        </div>

        <!-- Duplicate node section -->
        <div class="tree-section copy-node">
            <small>Copies the first id, incl. subtree, and appends it to the second id</small>
            <div>
                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    @click="copyNode"
                >
                    Copy node
                </button>
                <input
                    id="copyId"
                    class="form-control"
                    type="text"
                    v-model="copyNodeId"
                />
                <input
                    id="parentId"
                    class="form-control"
                    type="text"
                    v-model="copyNodeParentId"
                />
            </div>
        </div>

        <div class="tree-section copy-node-chained">
            <small>Copies the first id, incl. subtree, and appends it to the second id</small>
            <small>"Chained-inserts"</small>
            <div>
                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    @click="copyNodeChained"
                >
                    Copy node chained
                </button>
                <input
                    class="form-control"
                    type="text"
                    v-model="copyNodeChainedId"
                />
                <input
                    class="form-control"
                    type="text"
                    v-model="copyNodeChainedParentId"
                />
            </div>
        </div>

        <!-- Duplicate node section -->
        <div class="tree-section duplicate-node-by-id">
            <small>Duplicates the node id, incl. subtree, and adds it as a sibling to that node</small>
            <small>Does <strong>not</strong> work on the root yet</small>
            <div>
                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    @click="duplicateById"
                >
                    Duplicate node
                </button>
                <input
                    id="duplicate-node-id"
                    class="form-control"
                    type="text"
                    v-model="duplicateId"
                />
            </div>
        </div>

        <!-- Delete a node by id section -->
        <div class="tree-section delete-node-by-id">
            <small>Deletes the node <strong>incl. subtree</strong></small>
            <div>
                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    @click="deleteById"
                >
                    Delete node
                </button>
                <input
                    id="delete-node-id"
                    class="form-control"
                    type="text"
                    v-model="deleteId"
                />
            </div>
        </div>

        <div>
            <p>Random node info:</p>
            <ul v-if="randomNodeInfo !== null">
                <li><span class="dataEntry">id: </span>{{ randomNodeInfo.id }}</li>
                <li><span class="dataEntry">title: </span>{{ randomNodeInfo.title }}</li>
                <li><span class="dataEntry">parent_id: </span>{{ randomNodeInfo.parent_id }}</li>
                <li><span class="dataEntry">position: </span>{{ randomNodeInfo.position }}</li>
                <li><span class="dataEntry">real_depth: </span>{{ randomNodeInfo.real_depth }}</li>
                <li><span class="dataEntry">path: </span>{{ randomNodeInfo.path }}</li>
            </ul>
        </div>
    </div>
</template>

<style lang="scss">
    .random-section {
        display: flex;
        flex-direction: row;

        div:first-child {
            margin-right: 10px;
        }
    }

    .count-difference {
        padding-right: 10px;
    }

    .plus-sign {
        margin: 0;
        padding: 0;
    }

    .dataEntry {
        font-weight: bold;
    }

    .error {
        border-bottom: 3px solid indianred;
    }

    .tree-container {
        padding: 20px;
    }

    .tree-section {
        padding: 10px 0;
        display: flex;
        flex-direction: column;

        div {
            display: flex;
            flex-direction: row;

            button {
                margin-right: 10px;
            }

            input:last-child {
                margin-left: 10px;
            }
        }
    }

    ul {
        list-style: none;
        padding: 0;
    }
</style>

<script src="./calculus-tree.js"></script>