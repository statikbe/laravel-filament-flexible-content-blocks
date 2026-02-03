export function debounce(func, timeoutInMillis = 100) {
    let timer: number;

    return (...args) => {
        clearTimeout(timer);

        timer = setTimeout(
            () => {
                func.apply(this, args);
            },
            timeoutInMillis,
        );
    };
}
