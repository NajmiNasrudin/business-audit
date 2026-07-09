/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./index.html"],
  theme: {
    extend: {
      colors: {
        forest:       '#14392F',
        'forest-soft':'#1F4D40',
        cream:        '#F4EDD8',
        'cream-soft': '#EFE6D2',
        sage:         '#15B956',
        'sage-dark':  '#0F8C42',
        coal:         '#1A1A1A',
        slate:        '#4A4A4A',
        bone:         '#FAFAF7',
        sand:         '#E8DFC6',
        rust:         '#B85C38',
        moss:         '#6B8E5A',
        gold:         '#C9A961',
      },
      fontFamily: {
        display: ['Fraunces', 'Georgia', 'serif'],
        body:    ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
        mono:    ['JetBrains Mono', 'Menlo', 'monospace'],
      },
      letterSpacing: {
        eyebrow: '0.18em',
      },
    },
  },
  plugins: [],
}
