import { defineConfig } from 'vite'
import symfonyPlugin from "vite-plugin-symfony";
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'
import path from 'path'

export default defineConfig({
  plugins: [vue(), symfonyPlugin(), tailwindcss()],
  server: {
    host: '0.0.0.0', // Доступно для всех интерфейсов
    port: 5173,
    cors: true,
    origin: 'http://localhost', // Указываем, кто стучится
    strictPort: true,
    hmr: {
      host: 'localhost', // важно для HMR через Docker
    }
  },
  root: './',
  base: '/build/',  // публичный путь для Nginx
  build: {
    outDir: '../public/build', // СЮДА будет билдиться всё
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: './index.html', // точка входа — index.html
    }
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
    }
  }
})