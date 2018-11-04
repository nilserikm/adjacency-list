<template>
    <div class="tree-container">
        <h5 style="text-align: center;">Tree Data</h5>

        <!-- General statistics about the tree -->
        <div class="initial-data">
            <p>Tree Stats</p>
            <ul>
                <li>Time to fetch tree: <span class="dataEntry">{{ fetchTreeTime }}</span></li>
                <li>Number of nodes: <span class="dataEntry">{{ allCount }}</span></li>
            </ul>
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

        <!-- Delete a leaf section -->
        <div class="tree-section delete-child">
            <button
                type="button"
                class="btn btn-primary btn-sm"
                @click="deleteLeaf"
            >
                Delete child
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

    .tree-container {
        padding: 20px;
    }

    .tree-section {
        padding: 10px 0;
    }

    .add-child,
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