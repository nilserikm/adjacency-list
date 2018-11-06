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
            rootChildNodes: null
        }
    },

    mounted() {
        this.calculateNodes();
    },

    methods: {
        calculateNodes() {
            let total = 0;
            let l1 = this.treeLevel1;
            let l2 = l1 * this.treeLevel2;
            let l3 = l2 * this.treeLevel3;
            let l4 = l3 * this.treeLevel4;
            let l5 = l4 * this.treeLevel5;
            let l6 = l5 * this.treeLevel6;

            total = parseInt(l1) + parseInt(l2) + parseInt(l3) + parseInt(l4) + parseInt(l5) + parseInt(l6);
            this.numberOfNodes = total;

            l2 = 1;
            l3 = l2 * this.treeLevel3;
            l4 = l3 * this.treeLevel4;
            l5 = l4 * this.treeLevel5;
            l6 = l5 * this.treeLevel6;
            this.rootChildNodes = parseInt(l2) + parseInt(l3) + parseInt(l4) + parseInt(l5) + parseInt(l6);
        }
    }
}