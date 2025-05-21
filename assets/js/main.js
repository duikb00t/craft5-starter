import "vite/modulepreload-polyfill";

/*
 * Import global styles
 */

import "@css/main.css";

import Glider from 'glider-js';
import 'glider-js/glider.min.css'; // Import styles

import 'swiper/css';
import 'swiper/css/thumbs';
import 'swiper/css/navigation';


document.addEventListener("DOMContentLoaded", function () {
	const slider = document.querySelector(".glider");

	if (slider) {
		new Glider(slider, {
			slidesToShow: 1,
			slidesToScroll: 1,
			draggable: true,
			// arrows: {
			// 	prev: '.glider-prev',
			// 	next: '.glider-next'
			// },
			dots: '.glider-dots'

		});
	}
});

// Accept HMR as per: https://vitejs.dev/guide/api-hmr.html
if (import.meta.hot) {
	import.meta.hot.accept(() => {
		console.log("HMR")
	});
}
