module.exports = {
  purge: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {
    zIndex: ['responsive', 'hover'],
  },
  plugins: [],
  prefix: 'tw__',
  important: true,
  corePlugins: {
    // preflight: false,
  }
}
