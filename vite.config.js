import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
  root: 'resources', // this points to your React source folder
  base: './',        // so assets resolve correctly
  build: {
    outDir: '../dist', // build output folder
  },
  server: {
    port: 5173,
    open: true, // auto open browser
  },
});
