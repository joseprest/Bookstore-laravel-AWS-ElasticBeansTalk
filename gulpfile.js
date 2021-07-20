'use strict';

var path = require('path');
var fs = require('fs');
var env = require('node-env-file');

//Load configuration from environment file
try {
    var envFile = __dirname + '/.env';
    var stats = fs.lstatSync(envFile);
    if(stats.isFile())
    {
        env(envFile);
    }
} catch(e){}

/**
 * Gulp
 */
var gulpPath = path.resolve('./gulp');
var gulp = require('gulp');
var watch = require('gulp-watch');
var config = require(path.join(gulpPath,'config'))(gulp);

/**
 * Browsersync
 */
gulp.browserSync = require('browser-sync').create();
gulp.task('browsersync', ['sass'], require(path.join(gulpPath,'tasks/browsersync'))(gulp, config, gulp.browserSync));
gulp.task('browsersync-reload', function()
{
    return gulp.browserSync.reload();
});

/**
 * Webpack poll value, uses environment variable WEBPACK_POLLING
 */
var webpackPoll = false;

if (process.env.WEBPACK_POLLING && process.env.WEBPACK_POLLING !== 'false') {
    if (process.env.WEBPACK_POLLING == 'true') {
        webpackPoll = true;
    } else {
        var pollValue = parseInt(process.env.WEBPACK_POLLING);

        if (!isNaN(pollValue)) {
            webpackPoll = pollValue;
        }
    }
}

/**
 * Webpack
 */
gulp.task('webpack', require(path.join(gulpPath,'tasks/webpack'))(gulp, config));
gulp.task('webpack-watch', require(path.join(gulpPath,'tasks/webpack'))(gulp, config, {
    watch: true,
    poll: webpackPoll
}));
gulp.task('webpack-screen', require(path.join(gulpPath,'tasks/webpack'))(gulp, config, {
    configPath: path.join(config.jsSrcPath, 'webpack.config.screen'),
    entry: 'screen.js'
}));
gulp.task('webpack-screen-watch', ['webpack-screen'], require(path.join(gulpPath,'tasks/webpack'))(gulp, config, {
    watch: true,
    poll: webpackPoll,
    configPath: path.join(config.jsSrcPath, 'webpack.config.screen'),
    entry: 'screen.js'
}));
gulp.task('scripts', ['modernizr', 'webpack', 'webpack-screen'], require(path.join(gulpPath,'tasks/scripts'))(gulp, config));

/**
 * Sass
 */
gulp.task('sass', require(path.join(gulpPath,'tasks/sass'))(gulp, config));
gulp.task('styles', ['sass'], require(path.join(gulpPath,'tasks/styles'))(gulp, config));

/**
 * Images
 */
gulp.task('imagemin', require(path.join(gulpPath,'tasks/imagemin'))(gulp, config));

/**
 * Modernizr
 */
gulp.task('modernizr', ['webpack'], require(path.join(gulpPath,'tasks/modernizr'))(gulp, config));

/**
 * Clean
 */
gulp.task('clean-tmp', require(path.join(gulpPath,'tasks/clean'))(gulp, config, {
    cleanPath: ['./.tmp']
}));


/**
 * Copy
 */
gulp.task('copy-interface', require(path.join(gulpPath,'tasks/copy'))(gulp, {
    src: config.interfaceCopySrc,
    dest: config.interfaceCopyDest
}, config));

/**
 * Watch
 */
gulp.task('watch', ['sass', 'modernizr'], function ()
{
    watch(config.sassSrcPath+'/**/*.scss', function()
    {
        return gulp.start('sass');
    });
    watch(config.interfaceCopySrc, function()
    {
        return gulp.start('copy-interface');
    });
});

/**
 * Other
 */
 
gulp.task('_server', [
    'copy-interface',
    'webpack-watch',
    'webpack-screen-watch',
    'sass',
    'modernizr',
    'browsersync',
    'watch'
]);

gulp.task('server', [
    'clean-tmp'
], function()
{
    return gulp.start('_server');
});

gulp.task('_build', [
    'copy-interface',
    'modernizr',
    'styles',
    'scripts',
    'imagemin'
]);

gulp.task('build', [
    'clean-tmp'
], function()
{
    return gulp.start('_build');
});

gulp.task('default', ['build']);
