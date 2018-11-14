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
            appendId: null,
            copyNodeId: null,
            copyNodeParentId: null,
            countDifference: null,
            deleteLeafTime: null,
            deleteByIdTime: null,
            deleteId: null,
            deleteNodeWithChildrenTime: null,
            duplicateByIdTime: null,
            duplicateId: null,
            feedback: "",
            feedbackError: false,
            timing: {
                backend: null,
                frontend: null
            },
            randomNodeInfo: null
        }
    },

    created() {
        this.fetchData();
    },

    methods: {
        copyNode() {
            if (isNaN(parseInt(this.copyNodeId)) || isNaN(parseInt(this.copyNodeParentId))) {
                this.copyNodeId = null;
                this.copyNodeParentId = null;
                this.setFeedback("No node ID specified ...", 'error');
            } else {
                let t0 = performance.now();
                let url = "/node/copy";
                let data = {
                    'nodeId': this.copyNodeId,
                    'parentId': this.copyNodeParentId
                };

                axios.post(url, data).then((response) => {
                    this.copyNodeId = null;
                    this.copyNodeParentId = null;
                    this.setData(response, (((performance.now() - t0) / 1000)));
                }).catch((error) => {
                    this.copyNodeId = null;
                    this.copyNodeParentId = null;
                    this.setFeedback(error.response.data.message, 'error');
                });
            }
        },

        appendNode() {
            if (isNaN(parseInt(this.appendId))) {
                this.appendId = null;
                this.setFeedback("No node ID specified ...", 'error');
            } else {
                let t0 = performance.now();
                let url = "/node/append";
                let data = { 'nodeId': this.appendId };

                axios.post(url, data).then((response) => {
                    this.appendId = null;
                    this.setData(response, (((performance.now() - t0) / 1000)));
                }).catch((error) => {
                    this.appendId = null;
                    this.setFeedback(error.response.data.message, 'error');
                });
            }
        },

        setData(response, frontendTime) {
            this.countDifference = response.data.allCount - this.allCount;
            this.allCount = response.data.allCount;
            this.timing.backend = response.data.time;
            this.timing.frontend = frontendTime;
            this.setFeedback(response.data.message);
        },

        randomNode() {
            let t0 = performance.now();
            let url = "/node/random/node";
            let data = {};

            axios.post(url, data).then((response) => {
                this.randomNodeInfo = response.data.node;
                this.setData(response, (((performance.now() - t0) / 1000)));
            }).catch((error) => {
                this.setFeedback(error.response.data.message, 'error');
            });
        },

        randomLeaf() {
            let t0 = performance.now();
            let url = "/node/random/leaf";
            let data = {};

            axios.post(url, data).then((response) => {
                this.randomNodeInfo = response.data.node;
                this.setData(response, ((performance.now() - t0) / 1000));
            }).catch((error) => {
                this.setFeedback(error.response.data.message, 'error');
            });
        },

        /**
         * Duplicates the root's last child to the right of that child
         * @returns {void}
         */
        duplicateById() {
            let t0 = performance.now();
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
                    this.setData(response, ((performance.now() - t0) / 1000));
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
            let t0 = performance.now();
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

                    this.setData(response, ((performance.now() - t0) / 1000));
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
            let t0 = performance.now();
            axios.get('/tree').then((response) => {
                this.dataFetched = true;
                this.setData(response, ((performance.now() - t0) / 1000));
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
            if (type.localeCompare("error") === 0) {
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