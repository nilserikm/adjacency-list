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
            countDifference: null,
            deleteLeafTime: null,
            deleteByIdTime: null,
            deleteId: null,
            deleteNodeWithChildrenTime: null,
            duplicateByIdTime: null,
            duplicateId: null,
            feedback: "",
            feedbackError: false,
            fetchTreeTime: null,
        }
    },

    created() {
        this.fetchData();
    },

    methods: {
        /**
         * Duplicates the root's last child to the right of that child
         * @returns {void}
         */
        duplicateById() {
            if (isNaN(parseInt(this.duplicateId))) {
                this.duplicateId = "";
                this.setFeedback("No node ID specified ...", 'error');
            } else {
                let data = { 'duplicateId': this.duplicateId };

                axios.post('/tree/duplicate-by-id', data).then((response) => {
                    this.countDifference = response.data.allCount - this.allCount;
                    this.allCount = response.data.allCount;
                    this.duplicateByIdTime = response.data.time;
                    this.duplicateId = "";
                    this.setFeedback(response.data.message);
                    console.log(response.data.treeDesc);
                }).catch((error) => {
                    this.duplicateId = "";
                    this.setFeedback(error.response.data.message, 'error');
                });
            }
        },

        /**
         * Deletes a node and its children/descendants by it's id
         * @returns {void}
         */
        deleteById() {
            if (isNaN(parseInt(this.deleteId))) {
                this.deleteId = "";
                this.setFeedback("No node ID specified ...", 'error');
            } else {
                let data = { 'deleteId': this.deleteId };

                axios.post('/tree/delete-by-id', data).then((response) => {
                    this.countDifference = response.data.allCount - this.allCount;
                    this.deleteByIdTime = response.data.time;
                    this.deleteId = "";
                    this.allCount = response.data.allCount;

                    let message = response.data.message + " (id: " + response.data.node.id + ")";
                    this.setFeedback(message);
                }).catch((error) => {
                    this.deleteId = "";
                    this.setFeedback(error.response.data.message, 'error');
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

                let message = response.data.message + " (id: " + response.data.node.id + ")";
                this.setFeedback(message);
            }).catch((error) => {
                this.setFeedback(error.response.data.message, 'error');
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

                let message = response.data.message + " (id: " + response.data.node.id + ")";
                this.setFeedback(message);
            }).catch((error) => {
                this.setFeedback(error.response.data.message, 'error');
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

                let message = response.data.message + " (id: " + response.data.node.id + ")";
                this.setFeedback(message);
            }).catch((error) => {
                this.setFeedback(error.response.data.message, 'error');
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

                let message = response.data.message + " (id: " + response.data.node.id + ")";
                this.setFeedback(message);
            }).catch((error) => {
                this.setFeedback(error.response.data.message, 'error');
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
                this.setFeedback(error.response.data.message, 'error');
                console.log(error);
            });
        },

        /**
         * Sets the error message
         * @param message
         * @param type - string
         * @returns {void}
         */
        setFeedback(message, type="response") {
            if (type === "error") {
                this.feedbackError = true;
            } else {
                this.feedbackError = false;
            }

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
        },

        /**
         * Returns true if the plus-sign before countDifference should be
         * displayed
         * @returns {boolean}
         */
        showPlus() {
            if (this.countDifference !== null) {
                if (Math.sign(this.countDifference) === 1) {
                    return true;
                }
            }

            return false;
        }
    }
}