<template>
    <div class="node-calculation-container">
        <h1>Node Calculations</h1>

        <div class="node-input">
            <label>Level 1: "Prosjekt"</label>
            <input
                ref="treeLevel1"
                class="form-control"
                type="number"
                v-model="treeLevel1"
                @change="calculateNodes"
            />
        </div>

        <div class="node-input">
            <label>Level 2: "Bygninger"</label>
            <input
                ref="treeLevel2"
                class="form-control"
                type="number"
                v-model="treeLevel2"
                @change="calculateNodes"
            />
        </div>

        <div class="node-input">
            <label>Level 3: "Faser"</label>
            <input
                ref="treeLevel3"
                class="form-control"
                type="number"
                v-model="treeLevel3"
                @change="calculateNodes"
            />
        </div>

        <div class="node-input">
            <label>Level 4: "Leiligheter"</label>
            <input
                ref="treeLevel4"
                class="form-control"
                type="number"
                v-model="treeLevel4"
                @change="calculateNodes"
            />
        </div>

        <div class="node-input">
            <label>Level 5: "Aktiviteter"</label>
            <input
                ref="treeLevel5"
                class="form-control"
                type="number"
                v-model="treeLevel5"
                @change="calculateNodes"
            />
        </div>

        <div class="node-input">
            <label>Level 6: "Eventualiteter"</label>
            <input
                ref="treeLevel6"
                class="form-control"
                type="number"
                v-model="treeLevel6"
                @change="calculateNodes"
            />
        </div>

        <div class="node-result">
            <label>Number of Nodes: Tree</label>
            <input
                ref="numberOfNodes"
                class="form-control"
                type="number"
                v-model="numberOfNodes"
                disabled
            >
        </div>

    </div>
</template>

<style lang="scss">
    .number-root-child {
        margin-bottom: 10px;
    }

    .node-calculation-container {
        padding: 20px;
    }

    .node-input {
        display: block;
        margin-bottom: 20px;
    }
</style>

<script src="./node-calculation.js"></script>