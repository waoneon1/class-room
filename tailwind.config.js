/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#00796B',
          dark: '#005F56',
          light: '#E0F2F1',
        },
      },
    },
  },
  plugins: [],
}
