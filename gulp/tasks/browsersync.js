'use strict';

var url = require('url');
var path = require('path');
var servestatic = require('serve-static');
var proxy = require('proxy-middleware');
var fs = require('fs');

module.exports = function(gulp, config, browserSync) {


    //Proxy the server
    var proxyOptions = url.parse('http://'+config.proxyHost);
    proxyOptions.preserveHost = true;
    proxyOptions.via = 'browserSync';

    //Static
    var serveStatic = {
        'build': servestatic(config.assetsBuildPath),
        'assets': servestatic(config.assetsSrcPath),
        'tmp': servestatic(config.assetsTmpPath),
        'bower': servestatic(config.bowerPath)
    };

    var absAssetsSrcPath = path.resolve(__dirname+'/../../', config.assetsSrcPath);
    var absAssetsBuildPath = path.resolve(__dirname+'/../../', config.assetsBuildPath);
    var absTmpPath = path.resolve(__dirname+'/../../', config.assetsTmpPath);

    var browserSyncConfig = {

        files: config.browserSyncWatchFiles || [],

        watchOptions: {},

        host: config.serverHost,
        open: 'external',

        scrollProportionally: false,
        ghostMode: false,
        notify: false,
        ui: false,

        server: {
            baseDir: config.publicPath,
            middleware: [
                function(req,res,next) {

                    var requestUrl = url.parse(req.url);
                    var path = requestUrl.pathname;

                    if(req.url.match(/^\/bower_components/))
                    {
                        req.url = req.url.replace(/^\/bower_components\//,'');

                        return serveStatic.bower(req,res,next);
                    }
                    else
                    {
                        var stats;

                        //Check tmp
                        try {
                            stats = fs.lstatSync(absTmpPath+path);
                            if(stats.isFile())
                            {
                                return serveStatic.tmp(req,res,next);
                            }
                        } catch(e) {}

                        //Check source
                        try {
                            stats = fs.lstatSync(absAssetsSrcPath+path);
                            if(stats.isFile())
                            {
                                return serveStatic.assets(req,res,next);
                            }
                        } catch(e) {}

                        //Check build
                        try {
                            stats = fs.lstatSync(absAssetsBuildPath+path);
                            if(stats.isFile())
                            {
                                return serveStatic.build(req,res,next);
                            }
                        } catch(e) {}
                    }

                    return next();
                },
                proxy(proxyOptions)
            ]
        }
    };

    // Static Server + watching scss/html files
    return function() {

        browserSync.init(browserSyncConfig);

    };

};
