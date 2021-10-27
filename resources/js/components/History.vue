<template>
    <div>
        <h2>History</h2>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Total</th>
                        <th>Dead</th>
                        <th>Alive</th>
                        <th>Max no</th>
                        <th>Min no</th>
                        <th>Created at</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(item, itemIndex) in items"
                        :key="itemIndex"
                    >
                        <td>{{ item.day }}</td>
                        <td>{{ item.total }}</td>
                        <td>{{ item.dead }}</td>
                        <td>{{ item.alive }}</td>
                        <td>{{ item["paddock_max"] }}</td>
                        <td>{{ item["paddock_min"] }}</td>
                        <td>{{ item["created_at"] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    name: "History",
    data() {
        return {
            items: []
        }
    },
    methods: {
        loadData() {
            axios
                .get("/api/history")
                .then(response => {
                    this.items = response.data;
                    this.loadDay();
                })
                .catch(error => {
                    console.log(error);
                });
        }
    },
    mounted() {
        this.loadData();
    }
}
</script>

<style scoped>
table, th, td {
    border: 1px solid #000;
}
table {
    width: 100%;
    border-collapse: collapse;
}
table tr td,
table tr th {
    padding: 3px 5px;
}
</style>
