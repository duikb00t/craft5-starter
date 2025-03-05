import { defineConfig } from "vite";
import legacy from "@vitejs/plugin-legacy";
import path from "path";
import compression from "vite-plugin-compression";
import vue from "@vitejs/plugin-vue";

export default defineConfig(({ command, mode }) => {
    return {
        base: mode === "development" ? "/" : "/dist/",
        build: {
            outDir: "web/dist",
            emptyOutDir: true,
            manifest: true,
            rollupOptions: {
                input: {
                    main: "assets/js/main.js",
                },
            },
            sourcemap: true,
        },
        css: {
            postcss: "config/frontend",
        },
        plugins: [
            compression({
                filter: /\.(js|mjs|json|css|map)$/i,
            }),
            legacy({
                targets: ["defaults", "not IE 11"],
            }),
            vue({
                template: {
                    compilerOptions: {},
                },
            }),
        ],
        resolve: {
            alias: {
                "@js": path.resolve("assets/js"),
                "@css": path.resolve("assets/css"),
                vue: "vue/dist/vue.esm-bundler.js",
            },
            preserveSymlinks: true,
        },
        server: {
            allowedHosts: true,
            cors: {
                origin: /https?:\/\/([A-Za-z0-9\-\.]+)?(localhost|\.local|\.test|\.site)(?::\d+)?$/
            },
            fs: {
                strict: false
            },
            headers: {
                "Access-Control-Allow-Private-Network": "true",
            },
            host: '0.0.0.0',
            port: 5173, // Use port 5173 for dev server.
            strictPort: true, // Don't try next available port if 5173 isn't available.
            origin: 'https://localhost:5173', // Rewrite asset URLs explicitly since the CMS web server is on a different host.
            watch: {
                ignored: ["storage/**", "vendor/**"],
            },
        },
    };
});