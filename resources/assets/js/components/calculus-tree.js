export default {
    name: "calculus-tree",

    data() {
        return {
            // modality variables
            dataFetched: false,

            // data variables
            addLeafTime: null,
            allCount: null,
            deleteLeafTime: null,
            deleteNodeWithChildrenTime: null,
            fetchTreeTime: null
        }
    },

    created() {
        this.fetchData();
    },

    methods: {
        /**
         * Deletes a node along with its children
         * @returns {void}
         */
        deleteNodeWithChildren() {
            axios.post('/tree/delete-node-with-children').then((response) => {
                console.log("deleted node id: ", response.data.deletedNodeId);

                this.allCount = response.data.allCount;
                this.deleteNodeWithChildrenTime = response.data.time;
            }).catch((error) => {
                console.log("something went wrong when deleting node with children ...");
                console.log(error);
            });
        },

        /**
         * Delete the first found left-hand leaf in the tree
         * @returns {void}
         */
        deleteLeaf() {
            axios.post('/tree/delete-leaf').then((response) => {
                this.allCount = response.data.allCount;
                this.deleteLeafTime = response.data.time;
            }).catch((error) => {
                console.log("something went wrong when deleting child ...");
                console.log(error);
            });
        },

        /**
         * Adds a new leaf under the first-found already existing leaf on the
         * left-hand side of the tree
         * @returns {void}
         */
        addLeaf() {
            axios.post('/tree/add-leaf').then((response) => {
                this.allCount = response.data.allCount;
                this.addLeafTime = response.data.time;
            }).catch((error) => {
                console.log("something went wrong when adding child ...");
                console.log(error);
            });
        },

        /**
         * Fetch the basic tree-data from the backend
         * @returns {void}
         */
        fetchData() {
            axios.get('/tree').then((response) => {
                this.dataFetched = true;
                this.allCount = response.data.allCount;
                this.fetchTreeTime = response.data.time;
            }).catch((error) => {
                console.log("something went wrong when fetching data ...");
                console.log(error);
            });
        }
    },

    computed: {
        /**
         * Returns true if the application is loading, false otherwise
         * @returns {boolean}
         */
        loading() {
            return !this.dataFetched;
        }
    }
}