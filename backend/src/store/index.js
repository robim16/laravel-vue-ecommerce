import { createStore } from "vuex";

const store = createStore({
    state: {
        user: {
            token: sessionStorage.getItem('TOKEN'),
            data: {}
        }
    },
    getters: {},
    actions,
    mutations,
})

export default store