export default {
    props: {
        'children': {
            type: Array,
            default: function() { return [] }
        },
        'efficiencyHours': {
            type: Number,
            default: 0
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
        'registeredHours': {
            type: String,
            default: "0"
        },
        'show': {
            type: Boolean,
            default: false
        },
        'title': {
            type: String,
            default: ""
        }
    },

    name: 'tree-node',

    data() {
        return {
            calculation: 0,
            display: null,
            doCalculation: false,
            sum: {
                efficiency: 0,
                estimate: 0,
                registered: 0
            },
            flashBackground: {
                estimate: false,
                sum: false
            }
        }
    },

    mounted() {
        this.display = this.show;
        if (!this.isRoot) {
            this.doCalculation = true;
        }
    },

    methods: {
        recalculateEstimate() {
            if (!this.isRoot) {
                this.calculateEstimate(this.estimate, this.children);
                this.$emit('recalculateEstimate');
            } else {
                this.calculateEstimate(this.estimate, this.children);
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
                        // this.calculateEstimate(this.estimate, this.children);
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
                            // this.calculateEstimate(this.estimate, this.children);
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

        calculateEfficiency(efficiencyHours, children) {
            efficiencyHours = parseInt(efficiencyHours);
            if (!this.countChildren(children)) {
                this.sum.efficiency = efficiencyHours;
            } else {
                for (let i = 0; i < children.length; i++) {
                    this.sum.efficiency = (efficiencyHours += this.calculateEfficiency(children[i]['efficiencyHours'], children[i]['children']));
                }
            }

            return this.sum.efficiency;
        },

        calculateRegistered(registeredHours, children) {
            registeredHours = parseInt(registeredHours);
            if (!this.countChildren(children)) {
                this.sum.registered = registeredHours;
            } else {
                for (let i = 0; i < children.length; i++) {
                    this.sum.registered = (registeredHours += this.calculateRegistered(children[i]['registeredHours'], children[i]['children']));
                }
            }

            return this.sum.registered;
        },

        calculateEstimate(estimate, children) {
            if (!this.countChildren(children)) {
                this.sum.estimate = estimate;
            } else {
                for (let i = 0; i < children.length; i++) {
                    this.sum.estimate = (estimate += this.calculateEstimate(children[i]['estimate'], children[i]['children']));
                }
            }

            return this.sum.estimate;
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
        },

        flash(type) {
            return new Promise((resolve) => {
                this.flashBackground[type] = true;
                setTimeout(() => {
                    resolve("nalle");
                }, 500);
            });
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
                    this.calculateEstimate(this.estimate, this.children);
                    this.calculateRegistered(this.registeredHours, this.children);
                    this.calculateEfficiency(this.registeredHours, this.children);
                } else {
                    this.sum.estimate = this.estimate;
                    this.sum.registered = this.registeredHours;
                    this.sum.efficiency = this.efficiencyHours;
                }
            } else {
                this.sum.estimate = this.estimate;
                this.sum.registered = this.registeredHours;
                this.sum.efficiency = this.efficiencyHours;
            }
        },

        'doCalculation': function() {
            if (this.countChildren(this.children)) {
                this.calculateEstimate(this.estimate, this.children);
                this.calculateRegistered(this.registeredHours, this.children);
                this.calculateEfficiency(this.registeredHours, this.children);
            } else {
                this.sum.estimate = this.estimate;
                this.sum.registered = this.registeredHours;
                this.sum.efficiency = this.efficiencyHours;
            }
        },

        'estimate': function() {
            this.flash('estimate').then((response) => {
                // do nothing
                console.log("done flashing estimate");
            }).catch((error) => {
                console.log("something went wrong");
                console.log(error);
            }).finally(() => {
                this.flashBackground.estimate = false;
            });

            if (this.countChildren(this.children)) {
                this.calculateEstimate(this.estimate, this.children);
            } else {
                this.sum.estimate = this.estimate;
            }
            this.$emit('recalculateEstimate');
        },

        'sum.estimate': function() {
            if (this.display) {
                this.flash('sum').then((response) => {
                    // do nothing
                }).catch((error) => {
                    console.log("something went wrong");
                    console.log(error);
                }).finally(() => {
                    this.flashBackground.sum = false;
                });
            }
        }
    }
}
