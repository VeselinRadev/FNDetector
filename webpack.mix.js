let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
  module: {
    rules: [
      {
        test: /\.less/,
        loader: 'import-glob-loader'
      },
      {
        test: /\.js?$/,
        exclude: /(node_modules\/(?!(dom7|swiper)\/).*|bower_components)/,
        use: [
          {
            loader: 'babel-loader',
            options: Config.babel()
          }
        ]
      }
    ]
  }
});

mix.js('resources/assets/js/app.js', 'public/js')
   .js('resources/assets/js/vendor.js', 'public/js')
   .less('resources/assets/less/app.less', 'public/css')
   .version()
   .sourceMaps();

   
