'use strict';

var autoprefixer = require('gulp-autoprefixer');
var minifyCss = require('gulp-minify-css');
var rename = require('gulp-rename');

module.exports = function(gulp, config)
{

    return function ()
    {
        gulp.src(config.sassTmpPath+'/**/*.css')
            .pipe(autoprefixer())
            .pipe(minifyCss())
            .pipe(gulp.dest(config.sassBuildPath));
    };

};
