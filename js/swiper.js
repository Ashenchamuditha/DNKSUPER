// product swiper
console.log("hello");

var swiper = new Swiper(".categories-slider", {
  loop:true,
  spaceBetween:20,
  autoplay:{
      delay:7500,
      disableOnInteraction:false,
  },
  centeredSlides:true,
  breakpoints: {
    0: {
      slidesPerView: 1,
    },
    768: {
      slidesPerView: 2,
    },
    1020: {
      slidesPerView: 3,
    },
  },
});

