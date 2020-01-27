let actions = {
    DELETE_POST({commit}, id) {
        commit('START_LOADING');
        axios.delete('/api/posts/' + id)
            .then(res => {
                commit('GET_POSTS', res.data.data);
            }).catch(err => {
            console.log(err)
        })
    },
    GET_POSTS({commit}) {
        commit('START_LOADING');
        axios.get('/api/posts')
            .then(res => {
                commit('GET_POSTS', res.data.data);
            }).catch(err => {
            console.log(err)
        })
    }
}
export default actions