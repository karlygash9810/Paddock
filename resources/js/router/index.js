import { createRouter, createWebHistory } from "vue-router";

import MainPage from "../components/MainPage.vue";
import Paddock from "../components/Paddock.vue";
import History from "../components/History.vue";


const routes = [
    {
        path: "/",
        name: "MainPage",
        component: MainPage,
        children: [
            {
                path: "/paddock",
                name: "Paddock",
                component: Paddock
            },
            {
                path: "/history",
                name: "History",
                component: History
            }
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});
export default router;
