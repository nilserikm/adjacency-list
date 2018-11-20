export default {
    name: "calculus-tree",

    data() {
        return {
            // modality variables
            dataFetched: false,

            // data variables
            allCount: null,
            appendId: null,
            copyNodeId: null,
            copyNodeParentId: null,
            countDifference: null,
            deleteId: null,
            duplicateId: null,
            feedback: "",
            feedbackError: false,
            interval: null,
            loading: false,
            randomNodeInfo: null,
            root: null,
            seconds: 0,
            timing: {
                backend: null,
                frontend: null,
                initStart: 0
            },
            tree: null,
            treeCount: null,
            trees: [],
        }
    },

    created() {
        this.loading = true;
        this.seconds = performance.now();
        this.startInterval();
        this.fetchData();
    },

    methods: {
        /**
         * Starts an interval which sets the component's seconds attribute every
         * 10ms, which is used in conjunction with the loader/overlay to display
         * the elapsed time while in loading
         * @returns {void}
         */
        startInterval() {
            this.interval = setInterval(this.setSeconds, 10)
        },

        /**
         * Sets the component's seconds attribute to a 2 digit float, which is
         * used in conjunction with the loader/overlay to display the elapsed
         * time while in loading
         * @returns {void}
         */
        setSeconds() {
            this.seconds = ((performance.now() - this.timing.initStart)/1000).toFixed(1);
        },

        /**
         * Copies a node with subtree and appends it to the given parent
         * @returns {void}
         */
        copyNode() {
            if (isNaN(parseInt(this.copyNodeId)) || isNaN(parseInt(this.copyNodeParentId))) {
                this.copyNodeId = null;
                this.copyNodeParentId = null;
                this.setFeedback("No node ID specified ...", 'error');
            } else {
                this.loading = true;
                this.timing.initStart = performance.now();
                let data = {
                    'nodeId': this.copyNodeId,
                    'parentId': this.copyNodeParentId
                };

                axios.post("/node/copy", data).then((response) => {
                    this.setData(response, (((performance.now() - this.timing.initStart) / 1000)));
                }).catch((error) => {
                    this.setFeedback(error.response.data.message, 'error');
                }).finally(() => {
                    clearInterval(this.interval);
                    clearInterval(this.interval);
                    this.loading = false;
                    this.copyNodeId = null;
                    this.copyNodeParentId = null;
                });
            }
        },

        /**
         * Appends a new, empty node to the given node id
         * @returns {void}
         */
        appendNode() {
            if (isNaN(parseInt(this.appendId))) {
                this.appendId = null;
                this.setFeedback("No node ID specified ...", 'error');
            } else {
                this.startInterval();
                this.loading = true;
                this.timing.initStart = performance.now();
                let data = { 'nodeId': this.appendId };

                axios.post('/node/append', data).then((response) => {
                    this.trees = response.data.trees;
                    this.setData(response, (((performance.now() - this.timing.initStart) / 1000)));
                }).catch((error) => {
                    this.setFeedback(error.response.data.message, 'error');
                }).finally(() => {
                    this.loading = false;
                    this.appendId = null;
                    clearInterval(this.interval);
                });
            }
        },

        /**
         * Sets the display variables used in frontend as process feedback to
         * the user
         * @param response
         * @param frontendTime
         * @returns {void}
         */
        setData(response, frontendTime) {
            this.countDifference = response.data.allCount - this.allCount;
            this.treeCount = response.data.treeCount;
            this.allCount = response.data.allCount;
            this.timing.backend = response.data.time.toFixed(2);
            this.timing.frontend = frontendTime.toFixed(2);
            this.setFeedback(response.data.message);
        },

        /**
         * Fetches a random node and displays a relevant set of its data
         * @returns {void}
         */
        randomNode() {
            this.startInterval();
            this.loading = true;
            this.timing.initStart = performance.now();

            axios.post("/node/random/node", {}).then((response) => {
                this.randomNodeInfo = response.data.node;
                this.setData(response, (((performance.now() - this.timing.initStart) / 1000)));
            }).catch((error) => {
                this.randomNodeInfo = null;
                this.setFeedback(error.response.data.message, 'error');
            }).finally(() => {
                this.loading = false;
                clearInterval(this.interval);
            });
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
                this.loading = true;
                this.timing.initStart = performance.now();
                let data = { 'deleteId': this.deleteId };

                axios.post('/node/delete-by-id', data).then((response) => {
                    this.trees = response.data.trees;
                    this.deleteId = "";
                    this.setData(response, ((performance.now() - this.timing.initStart) / 1000));
                    this.loading = false;
                    clearInterval(this.interval);
                }).catch((error) => {
                    this.deleteId = "";
                    this.setFeedback(error.response.data.message, 'error');
                    this.loading = false;
                    clearInterval(this.interval);
                });
            }
        },

        /**
         * Fetch the basic tree-data from the backend
         * @returns {void}
         */
        fetchData() {
            this.startInterval();
            this.timing.initStart = performance.now();
            axios.get('/tree').then((response) => {
                this.treeCount = response.data.treeCount;
                this.tree = response.data.tree;
                this.trees = response.data.trees;
                this.root = this.tree[0];
                this.loading = false;
                this.dataFetched = true;
                this.setData(response, ((performance.now() - this.timing.initStart) / 1000));
                clearInterval(this.interval);
            }).catch((error) => {
                this.setFeedback(error.response.data.message, 'error');
                console.log(error);
                clearInterval(this.interval);
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
         * Returns true if the application is currently "loading", false
         * otherwise
         * @returns {boolean}
         */
        modalityMode() {
            return !this.dataFetched || this.loading;
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