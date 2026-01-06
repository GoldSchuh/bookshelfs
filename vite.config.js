import { createAppConfig } from '@nextcloud/vite-config'
import { defineConfig } from 'vite'
import stylelint from "vite-plugin-stylelint";

const isProduction = process.env.NODE_ENV === "production";

export default defineConfig(
    createAppConfig({
      main: 'src/main.ts',
    }, {
      config: {
        build: {
          cssCodeSplit: false,
        }, css: {
          modules: {
            localsConvention: "camelCase",
          },
        },
        plugins: [stylelint()],
      },
      inlineCSS: { relativeCSSInjection: true },
      minify: isProduction,
      createEmptyCSSEntryPoints: true,
      extractLicenseInformation: true,
      thirdPartyLicense: false,
      server: {
        watch: {
          ignored: [
            "**/vendor/**",
            "**/vendor-bin/**", // This doesn't work somehow FIXME
          ]
        },
        host: true, // listen on all interfaces
        port: 5173,
        strictPort: true,
        // cors: true,
        // changeOrigin: true,
        // secure: false,
        hmr: {
          protocol: 'ws',
          host: 'localhost', // plain hostname (or 'localhost')
          port: 5173,
        },
        proxy: {
          '/apps': {
            target: 'http://nextcloud.local',
            changeOrigin: true,
            secure: false,
          },
          '/core': {
            target: 'http://nextcloud.local',
            changeOrigin: true,
            secure: false,
          },
          '/index.php': {
            target: 'http://nextcloud.local',
            changeOrigin: true,
            secure: false,
          },
          '/status.php': {
            target: 'http://nextcloud.local',
            changeOrigin: true,
            secure: false,
          },
        },
      }

    })
)
