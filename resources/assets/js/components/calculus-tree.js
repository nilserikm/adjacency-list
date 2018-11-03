export default {
    name: "calculus-tree",

    data() {
        return {
            //
        }
    },

    created() {
        console.log("created ...");
    },

    mounted() {
        console.log("mounted ...");
        this.testMethod();
    },

    methods: {
        testMethod() {
            console.log("test method ...");
        }
    }
}