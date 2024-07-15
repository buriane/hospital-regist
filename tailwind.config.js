/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        'sans': ['Plus Jakarta Sans', 'sans-serif'],
      },
      colors: {
        'light-blue': '#E3F2FD',
        'blue': '#1976D2',
        'light-gray': '#F9FAFB',
        'green': '#0E940E',
        'dark-gray': '#1F2937',
      },
    },
  },
  plugins: [],
}