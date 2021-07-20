'use strict';

var _ = require('lodash');
var clean = require('gulp-clean');
    
module.exports = function(gulp, globalConfig, config)
{
    config = _.extend({}, globalConfig, config);
    return function()
    {
        return gulp.src(config.cleanPath)
                    .pipe(clean());
    };
};
