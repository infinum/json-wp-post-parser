const path = require('path');

const webpack = require('webpack');
const FileManagerPlugin = require('filemanager-webpack-plugin');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');

const appPath = `${path.resolve(__dirname)}`;

// Entry
const pluginPath = '/src/skin';
const pluginFullPath = `${appPath}${pluginPath}`;
const pluginEntry = `${pluginFullPath}/assets/application.js`;
const pluginPublicPath = `${appPath}/build/`;

// Outputs
const outputJs = 'scripts/[name].js';

const allModules = {
  rules: [
    {
      test: /\.(js|jsx)$/,
      use: 'babel-loader',
      exclude: /node_modules/
    },
    {
      test: /\.json$/,
      use: 'json-loader'
    }
  ]
};

const allPlugins = [
  new webpack.optimize.ModuleConcatenationPlugin(),
  new UglifyJSPlugin({
    comments: false,
    sourceMap: true
  }),
  new FileManagerPlugin({
    onStart: [
      {
        delete: [
          './build'
        ]
      },
      {
        copy: [
          {source: './src', destination: './build/json-wp-post-parser/'}
        ]
      }
    ],
    onEnd: [
      {
        copy: [
          {source: './build/scripts/application.js', destination: './build/json-wp-post-parser/assets/scripts/application.js'}
        ]
      },
      {
        delete: [
          './build/json-wp-post-parser/skin'
        ]
      }
    ]
  })
];

module.exports = [
  {
    devServer: {
      outputPath: path.join(__dirname, 'build')
    },
    entry: {
      application: [pluginEntry]
    },
    output: {
      path: pluginPublicPath,
      publicPath: '',
      filename: outputJs
    },

    module: allModules,

    plugins: allPlugins
  }
];
