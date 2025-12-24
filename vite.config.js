import { createAppConfig } from '@nextcloud/vite-config';

export default createAppConfig({
  main: 'src/main.js',

  server: {
    host: true, // listen on all interfaces
    port: 5173,
    strictPort: true,
    cors: true,
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
  },
})
