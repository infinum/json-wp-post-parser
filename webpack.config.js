const path = require('path');

const webpack = require('webpack');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');

const appPath = `${path.resolve(__dirname)}`;

// Entry
const pluginPath = `/src/skin`;
const pluginFullPath = `${appPath}${pluginPath}`;
const pluginPublicPath = `${pluginPath}/public/`;
const pluginEntry = `${pluginFullPath}/assets/application.js`;
const pluginAdminEntry = `${pluginFullPath}/assets/scripts/scriptsAdmin.js`;
const output = `${pluginFullPath}/public`;

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
  // new CleanWebpackPlugin([output]),
  new webpack.optimize.ModuleConcatenationPlugin(),
  new UglifyJSPlugin({
    comments: false,
    sourceMap: true
  }),
  new CopyWebpackPlugin([
    {
      from: '/src/*',
      to: '/build/json-wp-post-parser',
    }
  ], {
    ignore: [
      '/src/skin/assets/*'
    ]
  })
];

module.exports = [
  {
    context: path.join(__dirname),
    devServer: {
      outputPath: path.join(__dirname, 'build')
    },
    entry: {
      application: [pluginEntry],
    },
    output: {
      path: output,
      publicPath: pluginPublicPath,
      filename: outputJs
    },

    module: allModules,

    plugins: allPlugins
  }
];
