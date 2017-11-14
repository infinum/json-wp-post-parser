const path = require('path');

const webpack = require('webpack');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

const appPath = `${path.resolve(__dirname)}`;

// Entry
const pluginPath = '/src/skin';
const pluginFullPath = `${appPath}${pluginPath}`;
const pluginEntry = `${pluginFullPath}/assets/application.js`;
const pluginPublicPath = `${appPath}/build/assets`;

// Outputs
const outputJs = 'scripts/[name].js';
const outputCss = 'styles/[name].css';

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
    },
    {
      test: /\.scss$/,
      use: ExtractTextPlugin.extract({
        fallback: 'style-loader',
        use: ['css-loader', 'sass-loader']
      })
    }
  ]
};

const allPlugins = [
  new webpack.optimize.ModuleConcatenationPlugin(),
  new ExtractTextPlugin(outputCss),
  new UglifyJSPlugin({
    comments: false,
    sourceMap: true
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
