/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/views/layouts/front.blade.php',
    './resources/views/events/index.blade.php',
    './resources/views/events/show.blade.php',
    './resources/views/registrations/index.blade.php',
    './resources/views/auth/**/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}