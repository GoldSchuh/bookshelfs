import { createAppConfig } from '@nextcloud/vite-config'
import { defineConfig } from 'vite'
import stylelint from "vite-plugin-stylelint";

export default defineConfig(
    createAppConfig({
      main: 'src/main.ts',
    }, {
        config: {
            build: {
                sourcemap: false,
                cssCodeSplit: true,
                cssMinify: true,
                minify: 'terser',
                terserOptions: {
                    format: {comments: false},
                    compress: {
                        drop_console: true,
                        drop_debugger: true,
                    },
                },
            rollupOptions: {
                    output: {
                        manualChunks(id: string) {
                            if (id.includes('node_modules')) {
                                // put Vue runtime into its own chunk
                                if (id.includes('/node_modules/vue')) return 'vendor-vue'
                                // separate draggable/sortable libs
                                if (id.includes('/node_modules/vuedraggable') || id.includes('/node_modules/sortablejs')) return 'vendor-draggable'
                                // nextcloud/router or other large vendor libs separately
                                if (id.includes('/node_modules/@nextcloud')) return 'vendor-nextcloud'
                                // everything else vendor
                                // console.log(id)
                                return 'vendor'
                            }
                        }
                    }
                }
            },
            css: {
                modules: {
                    localsConvention: "camelCase",
                },
            },
            plugins: [stylelint()],
        },
        inlineCSS: {relativeCSSInjection: true},
        minify: true,
    })
)
