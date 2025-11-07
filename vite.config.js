import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [react()],
  root: 'resources',
  base: './',
  build: {
    outDir: '../dist',
  },
  server: {
    port: 5173,
    open: true,
  },
import tailwindcss from "@tailwindcss/vite";
import react from "@vitejs/plugin-react";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.jsx"],
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    optimizeDeps: {
        include: ["react", "react-dom"],
    },
});
