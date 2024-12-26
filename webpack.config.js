const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/frontend/')
    .setPublicPath('/build/frontend')
    .addEntry('app', './assets/frontend/app.js')
    .addEntry('cta', './assets/frontend/cta.js')
    .addEntry('tech', './assets/frontend/js/tech.js')
    .addEntry('about', './assets/frontend/js/aboutUs.js')
    .addEntry('lobby', './assets/frontend/js/reviewLobby.js')
    .enableSassLoader()
    .autoProvidejQuery()
    // .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // .enableVersioning(Encore.isProduction())
    .cleanupOutputBeforeBuild()
;

// build the first configuration
const frontend = Encore.getWebpackConfig();

// Set a unique name for the config (needed later!)
frontend.name = 'frontend';

// reset Encore to build the second config
Encore.reset();

// define the second configuration
Encore
    .setOutputPath('public/build/admin/')
    .setPublicPath('/build/admin')
    .addEntry('admin', './assets/admin/app.js')
    .addEntry('pagebuilder', './assets/admin/pagebuilder.js')
    // .autoProvidejQuery()
    .enableSassLoader()
    // .splitEntryChunks()
    .disableSingleRuntimeChunk()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .cleanupOutputBeforeBuild()
;

// build the second configuration
const admin = Encore.getWebpackConfig();

// Set a unique name for the config (needed later!)
admin.name = 'admin';

Encore.reset();

// export the final configuration as an array of multiple configurations
module.exports = [frontend, admin];

