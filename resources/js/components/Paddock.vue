<template>
    <div>
        <h2 class="text-center">
            DAY: {{ day }}
        </h2>
        <b-row>
            <b-col class="paddock">
                <h4 class="text-center">
                    Загон 1
                    <small>
                        <i>
                            (count: {{ paddock1.length }})
                        </i>
                    </small>
                </h4>
                <div>
                    <p v-for="(item, itemIndex) in paddock1" :key="itemIndex">{{ item.name }}</p>
                </div>
            </b-col>
            <b-col class="paddock">
                <h4 class="text-center">
                    Загон 2
                    <small>
                        <i>
                            (count: {{ paddock2.length }})
                        </i>
                    </small>
                </h4>
                <div>
                    <p v-for="(item, itemIndex) in paddock2" :key="itemIndex">{{ item.name }}</p>
                </div>
            </b-col>
        </b-row>
        <b-row>
            <b-col class="paddock">
                <h4 class="text-center">
                    Загон 3
                    <small>
                        <i>
                            (count: {{ paddock3.length }})
                        </i>
                    </small>
                </h4>
                <div>
                    <p v-for="(item, itemIndex) in paddock3" :key="itemIndex">{{ item.name }}</p>
                </div>
            </b-col>
            <b-col class="paddock">
                <h4 class="text-center">
                    Загон 4
                    <small>
                        <i>
                            (count: {{ paddock4.length }})
                        </i>
                    </small>
                </h4>
                <div>
                    <p v-for="(item, itemIndex) in paddock4" :key="itemIndex">{{ item.name }}</p>
                </div>
            </b-col>
        </b-row>
        <div class="my-3 text-center" v-if="loading.timer">
            NEXT DAY WILL BE STARTED AFTER {{ timerSeconds }} SECONDS
        </div>
        <b-button
            :disabled="this.loading.data || this.loading.day || this.loading.timer"
            @click="nextDay"
        >
            Update
        </b-button>
    </div>
</template>

<script>
export default {
    name: "Paddock",
    data() {
        return {
            loading: {
              timer: false,
              data: false,
              day: false
            },
            timer: null,
            timerSeconds: 0,
            day: null,
            items: []
        }
    },
    computed: {
        paddock1() {
            return this.items.filter(obj => [1, "1"].includes(obj.paddock));
        },
        paddock2() {
            return this.items.filter(obj => [2, "2"].includes(obj.paddock));
        },
        paddock3() {
            return this.items.filter(obj => [3, "3"].includes(obj.paddock));
        },
        paddock4() {
            return this.items.filter(obj => [4, "4"].includes(obj.paddock));
        }
    },
    methods: {
        nextDay() {
            const ctx = this;
            ctx.loading.timer = true;
            ctx.timerSeconds = 2;
            ctx.timer = setInterval(function() {
                ctx.timerSeconds -= 1;
                if (ctx.timerSeconds === 0) {
                    ctx.loading.timer = false;
                    ctx.updatePaddock();
                }
            }, 1000);
        },
        updatePaddock() {
            this.timer = null;
            axios
                .put("/api/paddock")
                .then(response => {
                    this.loadPaddock();
                })
                .catch(error => {
                    console.log(error);
                })
        },
        loadPaddock() {
            this.loading.data = true;
            axios
                .get("/api/paddock")
                .then(response => {
                    this.items = response.data;
                    this.loading.data = false;
                    this.loadDay();
                })
                .catch(error => {
                    console.log(error);
                    this.loading.data = false;
                });
        },
        loadDay() {
            this.loading.day = true;
            axios
                .get("/api/history/day")
                .then(response => {
                    if ("day" in response.data) {
                        this.day = response.data.day;
                    }
                    this.loading.day = false;
                })
                .catch(error => {
                    console.log(error);
                    this.loading.day = false;
                });
        }
    },
    mounted() {
        this.loadPaddock();
    }
}
</script>

<style scoped>
.paddock {
    border: 1px solid darkblue;
    margin: 10px;
    padding: 10px;
    text-align: right;
}
.paddock div {
    height: 200px;
    overflow-y: scroll;
    padding-right: 10px;
}
</style>
