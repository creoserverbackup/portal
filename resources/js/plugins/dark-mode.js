export const setTheme = function (status) {
    if (status) {
        document.body.classList.add('night-mode')
    } else {
        document.body.classList.remove('night-mode');
    }
};