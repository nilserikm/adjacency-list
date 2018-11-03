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
            fetchTreeTime: null
        }
    },

    created() {
        this.fetchData();
    },

    methods: {
        /**
         *
         */
        deleteLeaf() {
            axios.post('/tree/delete-leaf').then((response) => {
                console.log(response.data.child);
                this.allCount = response.data.allCount;
                this.deleteLeafTime = response.data.deleteLeafTime;
            }).catch((error) => {
                console.log("something went wrong when deleting child ...");
                console.log(error);
            });
        },

        addLeaf() {
            axios.post('/tree/add-leaf').then((response) => {
                console.log(response);
                this.allCount = response.data.allCount;
                this.addLeafTime = response.data.addLeafTime;
            }).catch((error) => {
                console.log("something went wrong when adding child ...");
                console.log(error);
            });
        },

        fetchData() {
            axios.get('/tree').then((response) => {
                this.dataFetched = true;
                this.allCount = response.data.allCount;
                this.fetchTreeTime = response.data.fetchTreeTime;
                console.log(response);
            }).catch((error) => {
                console.log("something went wrong when fetching data ...");
                console.log(error);
            });
        }
    },

    computed: {
        loading() {
            return !this.dataFetched;
        }
    }
}