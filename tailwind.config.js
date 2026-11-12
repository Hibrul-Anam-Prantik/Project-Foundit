/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#005A9C',
        secondary: '#D9EFFF',
        accent: '#FF6B6B',
        success: '#4CAF50',
      },
    },
  },
  plugins: [],
}
