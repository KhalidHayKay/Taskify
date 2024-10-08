const Encore = require("@symfony/webpack-encore");

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}

Encore
  // directory where compiled assets will be stored
  .setOutputPath("public/build/")
  // public path used by the web server to access the output path
  .setPublicPath("/build")
  // only needed for CDN's or sub-directory deploy
  //.setManifestKeyPrefix('build/')

  /*
   * ENTRY CONFIG
   *
   * Each entry will result in one JavaScript file (e.g. app.js)
   * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
   */
  .addEntry("app", "./resources/typescript/app.ts")
  .addEntry("layout", "./resources/typescript/layout.ts")
  .addEntry("dashboard", "./resources/typescript/dashboard.ts")
  .addEntry("category", "./resources/typescript/category/category.ts")
  .addEntry("task", "./resources/typescript/task/task.ts")
  .addEntry("contactPerson", "./resources/typescript/contactPerson.ts")

  // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
  .splitEntryChunks()

  // will require an extra script tag for runtime.js
  // but, you probably want this, unless you're building a single-page app
  .enableSingleRuntimeChunk()

  /*
   * FEATURE CONFIG
   *
   * Enable & configure other features below. For a full
   * list of features, see:
   * https://symfony.com/doc/current/frontend.html#adding-more-features
   */
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())

  // enables hashed filenames (e.g. app.abc123.css)
  .enableVersioning(Encore.isProduction())
  // .enableVersioning()

  .configureBabel((config) => {
    config.plugins.push("@babel/plugin-proposal-class-properties");
  })

  // enables @babel/preset-env polyfills
  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = "usage";
    config.corejs = 3;
  })

  .copyFiles({
    from: "./resources/images",
    to: "images/[path][name].[hash:8].[ext]",
    pattern: /\.(png|jpg|jpeg|gif)$/,
  })

  // enables Sass/SCSS support
  .enableSassLoader()

  // enables postCss loader (for tailwind).
  .enablePostCssLoader((options) => {
    options.postcssOptions = {
      plugins: [require("tailwindcss"), require("autoprefixer")],
    };
  })

  // uncomment if you use TypeScript
  .enableTypeScriptLoader();

// uncomment to get integrity="..." attributes on your script & link tags
// requires WebpackEncoreBundle 1.4 or higher
//.enableIntegrityHashes(Encore.isProduction())

module.exports = Encore.getWebpackConfig();
