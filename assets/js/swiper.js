// swiper
const swiper = new Swiper('.swiper', {
    // Melhora a UX ao mostrar um cursor de "agarrar"
    grabCursor: true,

    // botões de navegação
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },

    // espaço entre slides
    spaceBetween: 10,

    // responsivo
    slidesPerView: "auto",
    freeMode: true
});