const message = [
  'server.js dinonaktifkan.',
  'Gunakan aplikasi Laravel di folder /laravel.',
  'Jalankan: cd laravel && php artisan serve',
].join('\n');

console.error(message);
process.exit(1);
