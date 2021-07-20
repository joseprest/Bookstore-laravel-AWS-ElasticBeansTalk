var gulp = require('gulp');
var imagemin = require('gulp-imagemin');
var pngquant = require('imagemin-pngquant');

module.exports = function(gulp, config)
{
    return function()
    {
        return gulp.src(config.src, {
                base: config.base || null
            })
            .pipe(gulp.dest(config.dest));
    };
};
