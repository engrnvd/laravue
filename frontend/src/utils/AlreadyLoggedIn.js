export default (to, from, next) => {
    if (localStorage.getItem('user') != null && localStorage.getItem('user').length > 0) {
        next('/');
    } else {
        next();
    }
}
