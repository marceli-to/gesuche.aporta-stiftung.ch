import { fileURLToPath, URL } from 'node:url';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue2';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/sass/app.scss',
        'resources/js/app.js',
        'resources/js/form.js',
        'resources/js/validation.js',
      ],
      refresh: true,
    }),
    vue(),
  ],
  resolve: {
    alias: [
      { find: '@', replacement: fileURLToPath(new URL('./resources/js', import.meta.url)) },
      // The layouts mount on in-DOM markup (<app-component />), which Vue
      // compiles at runtime, so the full build (with compiler) is needed.
      { find: /^vue$/, replacement: 'vue/dist/vue.esm.js' },
    ],
    extensions: ['.mjs', '.js', '.json', '.vue'],
  },
});
