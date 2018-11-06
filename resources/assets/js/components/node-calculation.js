export default {
    name: "node-calculation",

    data() {
        return {
            treeLevel1: 1,
            treeLevel2: 5,
            treeLevel3: 5,
            treeLevel4: 20,
            treeLevel5: 20,
            treeLevel6: 0,
            numberOfNodes: null,
            rootChildNodes: null,
            level3Nodes: null,
            level4Nodes: null
        }
    },

    mounted() {
        this.calculateNodes();
    },

    methods: {
        calculateNodes() {
            this.numberOfNodes = this.calculateTotal();
            this.rootChildNodes = this.calculateRootChild();
            this.level3Nodes = this.calculateLevel3Nodes();
            this.level4Nodes = this.calculateLevel4Nodes();
        },

        calculateLevel4Nodes() {
            let l4 = 1;
            let l5 = l4 * this.treeLevel5;
            let l6 = l5 * this.treeLevel6;
            return parseInt(l4) + parseInt(l5) + parseInt(l6);
        },

        calculateLevel3Nodes() {
            let l3 = 1;
            let l4 = l3 * this.treeLevel4;
            let l5 = l4 * this.treeLevel5;
            let l6 = l5 * this.treeLevel6;
            return parseInt(l3) + parseInt(l4) + parseInt(l5) + parseInt(l6);
        },

        calculateRootChild() {
            let l2 = 1;
            let l3 = l2 * this.treeLevel3;
            let l4 = l3 * this.treeLevel4;
            let l5 = l4 * this.treeLevel5;
            let l6 = l5 * this.treeLevel6;
            return parseInt(l2) + parseInt(l3) + parseInt(l4) + parseInt(l5) + parseInt(l6);
        },

        calculateTotal() {
            let l1 = this.treeLevel1;
            let l2 = l1 * this.treeLevel2;
            let l3 = l2 * this.treeLevel3;
            let l4 = l3 * this.treeLevel4;
            let l5 = l4 * this.treeLevel5;
            let l6 = l5 * this.treeLevel6;

            return parseInt(l1) + parseInt(l2) + parseInt(l3) + parseInt(l4) + parseInt(l5) + parseInt(l6);
        }
    }
}