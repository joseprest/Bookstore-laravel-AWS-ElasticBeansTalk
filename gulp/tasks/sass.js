'use strict';

var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');

module.exports = function(gulp, config)
{
    return function()
    {
        return gulp.src(config.sassSrcPath+'/**/*.scss')
                    .pipe(sourcemaps.init())
                    .pipe(sass({
                        includePaths: config.sassIncludePaths || [],
                        errLogToConsole: true
                    }).on('error', sass.logError))
                    .pipe(sourcemaps.write())
                    .pipe(gulp.dest(config.sassTmpPath));
    };
};
