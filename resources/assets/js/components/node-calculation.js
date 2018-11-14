export default {
    name: "node-calculation",

    data() {
        return {
            treeLevel1: 1,
            treeLevel2: 5,
            treeLevel3: 7,
            treeLevel4: 25,
            treeLevel5: 20,
            treeLevel6: 0,
            numberOfNodes: null
        }
    },

    mounted() {
        this.calculateNodes();
    },

    methods: {
        calculateNodes() {
            this.numberOfNodes = this.calculateTotal();
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