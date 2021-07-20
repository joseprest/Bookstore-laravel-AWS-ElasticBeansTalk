'use strict';

var modernizr = require('gulp-modernizr');

module.exports = function(gulp, config)
{
    return function()
    {
        return gulp.src(config.modernizrSrcPath)
                    .pipe(modernizr('modernizr.js'))
                    .pipe(gulp.dest(config.modernizrBuildPath));
    };
};
