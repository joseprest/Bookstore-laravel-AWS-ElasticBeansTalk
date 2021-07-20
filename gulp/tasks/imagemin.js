var gulp = require('gulp');
var imagemin = require('gulp-imagemin');
var pngquant = require('imagemin-pngquant');

module.exports = function(gulp, config)
{
    return function()
    {
        return gulp.src(config.imgSrcPath+'/**/*.{gif,jpg,jpeg,png}')
            .pipe(imagemin({
                progressive: true,
                svgoPlugins: [{removeViewBox: false}],
                use: [pngquant()]
            }))
            .pipe(gulp.dest(config.imgBuildPath));
    };
};
