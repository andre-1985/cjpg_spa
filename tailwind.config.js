/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./src/**/*.{html,js}",
    "./node_modules/tw-elements/dist/js/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        'bleu_france': '#0055A4',
        'rouge_france': '#EF4135',
        'bleu_europe': '#003399',
        'or_euromillions': '#eebb05',
      },
      boxShadow: {
        '3xl': '.1em .1em 1em rgba(0, 0, 0, 0.250)',
      },
      backgroundImage: {
        'white_star': 'url("/public/medias/white-star.png")',
        'gold_star': 'url("/public/medias/gold-star.png")',
      },
      fontFamily: {
        'label_numbers': ['Comic Sans MS', 'Courier New'],
        'submit_grid': ['Metropolis-Bold', 'Courier New'],
      },
    },
  },
  plugins: [
    require("tw-elements/dist/plugin.cjs"),
    require("tailwindcss"),
    require("autoprefixer"),
  ],
  darkMode: "class"
}
