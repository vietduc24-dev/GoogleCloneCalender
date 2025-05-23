/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontSize: {
        'xs': '1.2rem',    // 12px
        'sm': '1.4rem',    // 14px
        'base': '1.6rem',  // 16px
        'lg': '1.8rem',    // 18px
        'xl': '2rem',      // 20px
        '2xl': '2.4rem',   // 24px
        '3xl': '3rem',     // 30px
        '4xl': '3.6rem',   // 36px
      },
      spacing: {
        'xs': '0.4rem',    // 4px
        'sm': '0.8rem',    // 8px
        'md': '1.6rem',    // 16px
        'lg': '2.4rem',    // 24px
        'xl': '3.2rem',    // 32px
        '2xl': '4.8rem',   // 48px
        '3xl': '6.4rem',   // 64px
      },
    },
  },
  plugins: [],
} 