export default {
    props: {
        'children': {
            type: Array,
            default: function() { return [] }
        },
        'estimate': {
            type: Number,
            default: null
        },
        'id': {
            type: Number,
            default: 0
        },
        'isRoot': {
            type: Boolean,
            default: false
        },
        'loading': {
            type: Boolean,
            default: true
        },
        'title': {
            type: String,
            default: ""
        },
        'show': {
            type: Boolean,
            default: false
        }
    },

    name: 'tree-node',

    data() {
        return {
            calculation: 0,
            display: null,
            doCalculation: false,
            sum: 0
        }
    },

    mounted() {
        this.display = this.show;
        if (!this.isRoot) {
            this.doCalculation = true;
        }
    },

    methods: {
        recalculate() {
            if (!this.isRoot) {
                this.calculate(this.estimate, this.children);
                this.$emit('recalculate');
            } else {
                this.calculate(this.estimate, this.children);
            }
        },

        increment(value, ids) {
            if (!this.isRoot) {
                ids.push(this.id);
                this.$emit('increment', value, ids);
            } else {
                let id = ids.pop();
                this.getNode(this.children, id).then((nextNode) => {
                    this.updateEstimate(ids, nextNode, value).then(() => {
                        // this.calculate(this.estimate, this.children);
                        console.log("done increment ...");
                    });
                });
            }
        },

        updateEstimate(ids, node, value) {
            return new Promise((resolve) => {
                if (!ids.length > 0) {
                    node.estimate += value;
                    resolve();
                } else {
                    let id = ids.pop();
                    this.getNode(node.children, id).then((nextNode) => {
                        this.updateEstimate(ids, nextNode, value).then(() => {
                            // this.calculate(this.estimate, this.children);
                            resolve();
                        });
                    });
                }
            });
        },

        getNode(children, id) {
            return new Promise((resolve) => {
                children.forEach((child) => {
                    if (child.id === id) {
                        resolve(child);
                    }
                });
            });
        },

        incrementEstimate() {
            let value = 1;
            let array = [];
            array.push(this.id);
            this.$emit('increment', value, array);
        },

        calculate(estimate, children) {
            if (!this.countChildren(children)) {
                this.sum = estimate;
            } else {
                for (let i = 0; i < children.length; i++) {
                    this.sum = (estimate += this.calculate(children[i]['estimate'], children[i]['children']));
                }
            }

            return this.sum;
        },

        expand() {
            this.display = !this.display;
        },

        countChildren(children) {
            if (typeof children === "undefined") {
                return false;
            } else if (children === null) {
                return false;
            } else if (! children.length > 0) {
                return false;
            } else {
                return true;
            }
        }
    },

    computed: {
        hasChildren() {
            if (typeof this.children === "undefined") {
                return false;
            } else if (this.children === null) {
                return false;
            } else if (! this.children.length > 0) {
                return false;
            } else {
                return true;
            }
        }
    },

    watch: {
        'loading': function() {
            if (this.isRoot) {
                if (this.countChildren(this.children)) {
                    this.calculate(this.estimate, this.children);
                } else {
                    this.sum = this.estimate;
                }
            } else {
                this.sum = this.estimate;
            }
        },

        'doCalculation': function() {
            if (this.countChildren(this.children)) {
                this.calculate(this.estimate, this.children);
            } else {
                this.sum = this.estimate;
            }
        },

        'estimate': function() {
            if (this.countChildren(this.children)) {
                this.calculate(this.estimate, this.children);
            } else {
                this.sum = this.estimate;
            }
            this.$emit('recalculate');
        }
    }
}
