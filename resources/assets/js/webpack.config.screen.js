const path = require('path');
const webpack = require('webpack');

module.exports = (config, opts) => {
    const contextPath = path.resolve(config.jsSrcPath);
    const outputPath = path.resolve(config.jsTmpPath);
    const publicPath = config.httpJsPath;
    let env = opts.watch ? 'production' : (process.env.NODE_ENV || 'development');
    env = 'production';

    return {
        context: contextPath,

        entry: {
            screen: [
                './screen',
            ],
            'vendor/screen': [
                'manivelle',
            ],
        },

        output: {
            publicPath,
            path: outputPath,
            filename: '[name].js',
            chunkFilename: '[name].chunk.js',
            jsonpFunction: 'flklrJsonp',
        },

        plugins: [
            new webpack.DefinePlugin({
                'process.env.NODE_ENV': JSON.stringify(env),
                NODE_ENV: JSON.stringify(env),
                ENV: JSON.stringify(env),
            }),
            new webpack.optimize.CommonsChunkPlugin({
                name: 'vendor/screen',
                filename: 'vendor/screen.js',
                chunks: ['screen', 'vendor/screen'],
            }),
        ],

        module: {
            noParse: [

            ],
            preLoaders: [

            ],
            loaders: [
                {
                    test: /\.jsx?$/,
                    exclude: /(node_modules|bower_components|\.tmp)/,
                    loader: 'babel-loader',
                    query: {
                        presets: ['es2015', 'react', 'stage-0'],
                    },
                },
                {
                    test: /\.jsx$/,
                    include: /node_modules\/manivelle-interface/,
                    loader: 'babel-loader',
                    query: {
                        presets: ['es2015', 'react', 'stage-0'],
                    },
                },
                {
                    test: /\.html$/,
                    loader: 'html-loader',
                },
                {
                    test: /\.json$/,
                    loader: 'json-loader',
                },
                {
                    test: /\.scss$/,
                    loaders: ['raw', 'sass'],
                },
                {
                    test: /\.js$/,
                    loader: 'transform/cacheable?brfs',
                    include: /node_modules\/pixi\.js/,
                },
            ],
            postLoaders: [
                {
                    test: require.resolve('./lib/url'),
                    loader: 'expose?URL',
                },
            ],
        },

        externals: {
            'react-external': 'React',
            'react-dom-external': 'ReactDOM',
            'jquery-external': 'jQuery',
            'gsap-external': 'TweenMax',
            'pixi.js-external': 'PIXI',
            'immutable-external': 'Immutable',
            'superagent-external': 'Superagent',
            'superagent-promise-external': 'SuperagentPromise',
            'promise-external': 'Promise',
            'lodash-external': '_',
            'bootstrap-sass-external': 'jQuery.fn.bootstrap',
            panneau: 'Panneau',
            /*manivelle: 'Manivelle',*/
        },

        resolve: {
            extensions: ['', '.js', '.jsx', '.es6'],
            alias: {
                manivelle: 'manivelle-interface/dist/js/manivelle',
                image: path.resolve('./public/vendor/folklore/image/js/image'),
            },
            modulesDirectories: [
                './node_modules',
                './web_modules',
                './bower_components',
                `${contextPath}/vendors`,
            ],
        },

        stats: {
            colors: true,
            modules: true,
            reasons: true,
        },

        storeStatsTo: 'webpack',

        progress: true,

        failOnError: false,

        cache: true,
        watch: false,
        keepAlive: false,
    };
};
