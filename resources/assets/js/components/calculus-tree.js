export default {
    name: "calculus-tree",

    data() {
        return {
            // modality variables
            dataFetched: false,

            // data variables
            addLeafTime: null,
            addRootChildTime: null,
            allCount: null,
            deleteLeafTime: null,
            deleteByIdTime: null,
            deleteNodeWithChildrenTime: null,
            feedback: "",
            fetchTreeTime: null,
            nodeId: null
        }
    },

    created() {
        this.fetchData();
    },

    methods: {
        /**
         * Deletes a node and its children/descendants by it's id
         * @returns {void}
         */
        deleteById() {
            if (isNaN(parseInt(this.nodeId))) {
                this.setFeedback("No node ID specified ...");
            } else {
                let data = { 'nodeId': this.nodeId };

                axios.post('/tree/delete-by-id', data).then((response) => {
                    this.deleteByIdTime = response.data.time;
                    this.nodeId = "";
                    this.allCount = response.data.allCount;
                    this.setFeedback(response.data.message);
                }).catch((error) => {
                    this.setFeedback(error.response.data.message);
                });
            }
        },

        /**
         * Adds another child to the root node
         * @returns {void}
         */
        addRootChild() {
            axios.post('/tree/add-root-child').then((response) => {
                console.log(response);
                this.allCount = response.data.allCount;
                this.addRootChildTime = response.data.time;
                this.setFeedback(response.data.message);
            }).catch((error) => {
                this.setFeedback(error.response.data.message);
                console.log(error);
            });
        },

        /**
         * Deletes a node along with its children
         * @returns {void}
         */
        deleteNodeWithChildren() {
            axios.post('/tree/delete-node-with-children').then((response) => {
                this.allCount = response.data.allCount;
                this.deleteNodeWithChildrenTime = response.data.time;
                this.setFeedback(response.data.message);
            }).catch((error) => {
                this.setFeedback(error.response.data.message);
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
                this.setFeedback(response.data.message);
            }).catch((error) => {
                this.setFeedback(error.response.data.message);
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
                this.setFeedback(response.data.message);
            }).catch((error) => {
                this.setFeedback(error.response.data.message);
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
                this.setFeedback(response.data.message);
            }).catch((error) => {
                this.setFeedback(error.response.data.message);
                console.log(error);
            });
        },

        /**
         * Sets the error message
         * @param message
         * @returns {void}
         */
        setFeedback(message) {
            this.feedback = message;
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