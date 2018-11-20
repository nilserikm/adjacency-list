<template>
    <div class="outer-container ">
        <div class="data-container col-lg-6">
            <!-- General statistics about the tree -->
            <div class="initial-data">
                <h2>Tree Stats</h2>
                <ul>
                    <li>Feedback: <span class="dataEntry" v-bind:class="{ error : feedbackError }">{{ feedback }}</span></li>
                    <li>Timing: <span class="dataEntry">{{ timing.backend }} s / {{ timing.frontend }} s</span></li>
                    <li>Number of nodes in tree:
                        <span class="dataEntry">{{ treeCount }}</span>
                    </li>
                    <li>Number of nodes in table:
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

            <div v-if="modalityMode" id="overlay">
                <div>
                    <vue-simple-spinner size="large"></vue-simple-spinner>
                    <h4 class="load-inter-message">Loading data ... <span class="seconds">{{ seconds }}</span> s</h4>
                </div>
            </div>

            <div v-else>
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

                <div class="tree-section copy-node-chained">
                    <small>Copies the first id, incl. subtree, and appends it to the second id</small>
                    <small>"Chained-inserts"</small>
                    <div>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm"
                            @click="copyNode"
                        >
                            Copy node chained
                        </button>
                        <input
                            class="form-control"
                            type="text"
                            v-model="copyNodeId"
                        />
                        <input
                            class="form-control"
                            type="text"
                            v-model="copyNodeParentId"
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
                        <li><span class="dataEntry">path: </span><span class="node-path">{{ randomNodeInfo.path }}</span></li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="outer-tree-container col-lg-6">
            <h1>Tree Structure</h1>
            <div
                v-if="dataFetched"
                class="tree-container"
                v-for="(tree, index) in trees"
                :key="'tree' + index"
            >
                <node
                    :nodes="tree[index].children"
                    :title="tree[index].title"
                    :estimate="tree[index].estimate"
                    :show="false"
                >
                </node>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
    .node-path {
        font-size: 18px;
        border-bottom: 3px solid indianred;
    }

    #overlay {
        position: fixed; /* Sit on top of the page content */
        display: block; /* Hidden by default */
        width: 100%; /* Full width (cover the whole page) */
        height: 100%; /* Full height (cover the whole page) */
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.5); /* Black background with opacity */
        z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
        cursor: pointer; /* Add a pointer on hover */

        h4 {
            text-align: center;
            color: white;
        }

        div {
            margin-top: 400px;
        }
    }

    .load-message {
        text-align: center;
    }
    
    .outer-tree-container {
        display: flex;
        flex-direction: column;
    }

    .tree-container {
        .node-entry {
            border-top: 1px solid lightgray;
            border-bottom: 1px solid lightgray;
        }
    }

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

    .outer-tree-container,
    .data-container {
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