'use strict';

var gulpUtil = require('gulp-util');
// var uglify = require('gulp-uglify');
const terser = require('gulp-terser');
var rename = require('gulp-rename');
var sourcemaps = require('gulp-sourcemaps');

module.exports = function(gulp, config)
{
    return function() {
        return gulp.src(config.jsTmpPath+'/**/*.js')
                .pipe(terser())
                .pipe(gulp.dest(config.jsBuildPath));
    };
};
