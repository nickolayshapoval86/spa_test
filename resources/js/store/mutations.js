let mutations = {
    GET_POSTS(state, posts) {
        state.posts = posts;
        state.isLoading = false;
    },
    START_LOADING(state) {
        state.posts = [];
        state.isLoading = true;
    },
}
export default mutations