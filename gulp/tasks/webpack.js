'use strict';

var path = require('path');
var _ = require('lodash');
var webpack = require('webpack-stream');
var plumber = require('gulp-plumber');
var sourcemaps = require('gulp-sourcemaps');

module.exports = function(gulp, config, opts)
{
    
    opts = _.extend({
        watch: false,
        configPath: path.join(config.jsSrcPath, 'webpack.config'),
        entry: 'main.js',
        poll: false
    }, opts);
    
    config.watch = opts.watch;
    
    var configPath = path.resolve(opts.configPath);
    var defaultConfig = require(configPath)(config, opts);
    if(process.env.NODE_ENV !== 'production')
    {
        defaultConfig.devtool = 'source-map';
        defaultConfig.debug = true;
    }
    if(opts.watch)
    {
        defaultConfig.watch = true;

        if(opts.poll && (opts.poll === true || _.isInteger(opts.poll)))
        {
            defaultConfig.watchOptions = {
                poll: opts.poll
            };
        }
    }
    
    return function()
    {
        return gulp.src(config.jsSrcPath+'/'+opts.entry)
            .pipe(plumber())
            .pipe(sourcemaps.init({loadMaps: true}))
            .pipe(webpack(defaultConfig))
            .pipe(sourcemaps.write('./'))
            .pipe(gulp.dest(config.jsTmpPath));
    };
};
