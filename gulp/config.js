'use strict';

module.exports = function(gulp, plugins)
{
    
    var host = process.env.APP_HOST || 'localhost';
    var hostEnv = process.env.APP_HOST_ENV || 'local';
    var proxyHostEnv = process.env.APP_PROXY_HOST_ENV || 'homestead';

    var assetsSrcFolder = './resources/assets';
    var assetsTmpPath = './.tmp';
    var assetsBuildFolder = './public/';
    var bowerPath = './bower_components';
    var publicPath = './public';
    
    var httpAssetsPath = '/';
    var httpJsPath = '/js/';
    var httpBowerPath = '/bower_components';
    
    var browserSyncWatchFiles = [
        './app/**/*.php',
        './config/**/*.php',
        './databases/**/*.php',
        './resources/views/**/*.php',
        './workbench/**/*.php',
        './workbench/**/*.js',
        assetsTmpPath+'/**/*.js',
        assetsTmpPath+'/**/*.css'
    ];
    
    var sassIncludePaths = [
        './node_modules'
    ];
    
    var modernizrSrcPath = [
        assetsSrcFolder+'/js/**/*.js',
        assetsSrcFolder+'/scss/**/*.scss'
    ];
    var modernizrBuildPath = assetsTmpPath+'/js/vendor';

    // Configurable paths
    return {
        
        host: host,
        serverHost: host+'.'+hostEnv+'.flklr.ca',
        proxyHost: host+'.'+proxyHostEnv+'.flklr.ca',
        
        //Interface
        interfaceCopySrc: './node_modules/manivelle-interface/dist/**/*',
        interfaceCopyDest: './public/vendor/manivelle-interface/',
        
        //Javascript (Webpack)
        jsSrcPath: assetsSrcFolder+'/js',
        jsTmpPath: assetsTmpPath+'/js',
        jsBuildPath: assetsBuildFolder+'/js',
        
        //Sass
        sassSrcPath: assetsSrcFolder+'/scss',
        sassTmpPath: assetsTmpPath+'/css',
        sassBuildPath: assetsBuildFolder+'/css',
        sassIncludePaths: sassIncludePaths,
        
        //Images
        imgSrcPath: assetsSrcFolder+'/img',
        imgBuildPath: assetsBuildFolder+'/img',
        
        //Fonts
        fontsPath: assetsBuildFolder+'/fonts',
        
        //Bower
        bowerPath: bowerPath,
        
        //Modernizr
        modernizrSrcPath: modernizrSrcPath,
        modernizrBuildPath: modernizrBuildPath,
        
        //Http
        httpAssetsPath: httpAssetsPath,
        httpJsPath: httpJsPath,
        httpBowerPath: httpBowerPath,
        
        //Public path for Browser sync
        publicPath: publicPath,
        
        //Files to watch by browser sync
        browserSyncWatchFiles: browserSyncWatchFiles,
        
        //Assets path
        assetsTmpPath: assetsTmpPath,
        assetsSrcPath: assetsSrcFolder,
        assetsBuildPath: assetsBuildFolder
    };
    
};
