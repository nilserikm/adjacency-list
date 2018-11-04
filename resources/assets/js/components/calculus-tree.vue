<template>
    <div class="tree-container">
        <h1>Adjacency List Operations</h1>

        <!-- General statistics about the tree -->
        <div class="initial-data">
            <h2>Tree Stats</h2>
            <ul>
                <li>Time to fetch entire tree: <span class="dataEntry">{{ fetchTreeTime }}</span></li>
                <li>Number of nodes in tree: <span class="dataEntry">{{ allCount }}</span></li>
                <li>Feedback: <span class="dataEntry" v-bind:class="{ error : feedbackError }">{{ feedback }}</span></li>
            </ul>
        </div>

        <!-- Duplicate node section -->
        <div class="tree-section duplicate-node-by-id">
            <div>
                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    @click="duplicateById"
                >
                    Duplicate node by id
                </button>
                <input
                    id="duplicate-node-id"
                    class="form-control"
                    type="text"
                    v-model="duplicateId"
                />
            </div>
            <div>
                <p>Time to duplicate node by id: <span class="dataEntry">{{ duplicateByIdTime === null ? "No process ..." : duplicateByIdTime }}</span></p>
            </div>
        </div>

        <!-- Add root child section -->
        <div class="tree-section add-root-child">
            <button
                type="button"
                class="btn btn-primary btn-sm"
                @click="addRootChild"
            >
                Add root child
            </button>
            <p>Time to add root child: <span class="dataEntry">{{ addRootChildTime === null ? "No process ..." : addRootChildTime }}</span></p>
        </div>

        <!-- Add a leaf section -->
        <div class="tree-section add-child">
            <button
                type="button"
                class="btn btn-primary btn-sm"
                @click="addLeaf"
            >
                Add child
            </button>
            <p>Time to add leaf: <span class="dataEntry">{{ addLeafTime === null ? "No process ..." : addLeafTime }}</span></p>
        </div>

        <!-- Delete a node by id section -->
        <div class="tree-section delete-node-by-id">
            <div>
                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    @click="deleteById"
                >
                    Delete node by id
                </button>
                <input
                    id="delete-node-id"
                    class="form-control"
                    type="text"
                    v-model="deleteId"
                />
            </div>
            <div>
                <p>Time to delete node by id: <span class="dataEntry">{{ deleteByIdTime === null ? "No process ..." : deleteByIdTime }}</span></p>
            </div>
        </div>

        <!-- Delete a leaf section -->
        <div class="tree-section delete-child">
            <button
                type="button"
                class="btn btn-primary btn-sm"
                @click="deleteLeaf"
            >
                Delete leaf
            </button>
            <p>Time to delete leaf: <span class="dataEntry">{{ deleteLeafTime === null ? "No process ..." : deleteLeafTime }}</span></p>
        </div>

        <!-- Delete a node with its children section -->
        <div class="tree-section delete-child">
            <button
                type="button"
                class="btn btn-primary btn-sm"
                @click="deleteNodeWithChildren"
            >
                Delete child
            </button>
            <p>Time to delete node with children: <span class="dataEntry">{{ deleteNodeWithChildrenTime === null ? "No process ..." : deleteNodeWithChildrenTime }}</span></p>
        </div>
    </div>
</template>

<style lang="scss">
    .dataEntry {
        font-weight: bold;
    }

    .error {
        /*background-color: indianred;*/
        /*color: white;*/
        border-bottom: 3px solid indianred;
    }

    .tree-container {
        padding: 20px;
    }

    .tree-section {
        padding: 10px 0;
    }

    .delete-node-by-id,
    .duplicate-node-by-id {
        display: flex;
        flex-direction: column;
    }

    .delete-node-by-id div,
    .duplicate-node-by-id div {
        margin-bottom: 10px;
    }

    .delete-node-by-id div:first-child,
    .duplicate-node-by-id div:first-child {
        display: flex;
        flex-direction: row;
    }

    .delete-node-by-id div:first-child button,
    .duplicate-node-by-id div:first-child button {
        margin-right: 20px;
    }

    .add-child,
    .add-root-child,
    .delete-child {
        display: flex;
        flex-direction: row;
        border-top: 1px solid lightgrey;
        border-bottom: 1px solid lightgrey;

        button {
            margin-right: 20px;
        }

        p {
            margin-top: 10px;
        }
    }
</style>

<script src="./calculus-tree.js"></script>