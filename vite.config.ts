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
            rollupOptions: {
                    output: {
                        manualChunks(id: string) {
                            if (id.includes('node_modules')) {  // split on libraries
                                if (id.includes('/node_modules/vue')) return 'vendor-vue'
                                if (id.includes('/node_modules/vuedraggable') || id.includes('/node_modules/sortablejs')) return 'vendor-draggable'
                                if (id.includes('/node_modules/@nextcloud')) return 'vendor-nextcloud'
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
    })
)
