import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'; // Vue plugin


export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/add.js',
        'resources/js/country.js',
        'resources/js/state.js',
        'resources/js/city.js',
        'resources/css/detailtable.css', 

      ],
      refresh: true,
    }),
    vue(), // Add the Vue plugin for Vite
  ],
  resolve: {
    alias: {
      vue: 'vue/dist/vue.esm-bundler.js', // Alias to the full Vue build that supports templates
      '@': '/resources/js',
    },
  },
  server: {
    host: 'localhost', // Bind to localhost to avoid issues with IPv6
    port: 5174, // Make sure the port is the same as the one you're using
    cors: {
      origin: 'http://project.test', // Allow requests from this origin
      methods: ['GET', 'POST', 'PUT', 'DELETE'], // Allow these methods
    },
  },
});

