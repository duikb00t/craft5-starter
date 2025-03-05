import "vite/modulepreload-polyfill";

/*
 * Import global styles
 */

import "@css/main.css";

console.log('test');

// Accept HMR as per: https://vitejs.dev/guide/api-hmr.html
if (import.meta.hot) {
	import.meta.hot.accept(() => {
		console.log("HMR")
	});
}
