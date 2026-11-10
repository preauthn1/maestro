/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: false, // or'media' or 'class'
  content: ["./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}"],
  theme: {
    extend: {
      colors: {
        "goid_txt": "#f7f3b2",
        "link-text": "#F6CF12",
        "introduce_text":"#959595",
        "amount_color" :"#15CF00",
        "title_color":"#1555FA",
      },
    },
  },
  plugins: [],
};
