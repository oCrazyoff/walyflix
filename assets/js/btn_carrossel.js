document.querySelectorAll(".filmes-container").forEach(container => {
    const scrollContainer = container.querySelector(".container-filmes");
    const btnLeft = container.querySelector(".scroll-left");
    const btnRight = container.querySelector(".scroll-right");

    if (btnLeft && btnRight) {
        btnLeft.addEventListener("click", () => {
            scrollContainer.scrollBy({left: -500, behavior: "smooth"});
        });
        btnRight.addEventListener("click", () => {
            scrollContainer.scrollBy({left: 500, behavior: "smooth"});
        });
    }
});