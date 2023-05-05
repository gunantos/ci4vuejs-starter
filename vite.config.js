import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import { resolve } from 'node:path'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue(), vueJsx()],
  root: './front',
  server: {
    watch: {
      
    }
  },
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./front/src', import.meta.url))
    }
  },
  build: {
    outDir: './front/build/'
  }
})
