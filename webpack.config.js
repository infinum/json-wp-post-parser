const path = require('path');

const webpack = require('webpack');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const appPath = `${path.resolve(__dirname)}`;

// Entry
const pluginPath = '/src/json-wp-post-parser/skin';
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
      exclude: /node_modules/,
    },
    {
      test: /\.json$/,
      use: 'json-loader',
    },
    {
      test: /\.scss$/,
      use: [MiniCssExtractPlugin.loader, 'css-loader', 'sass-loader'],
    },
  ],
};

const allPlugins = [
  new webpack.optimize.ModuleConcatenationPlugin(),
  new MiniCssExtractPlugin(outputCss),
];

module.exports = [
  {
    devServer: {
      outputPath: path.join(__dirname, 'build'),
    },
    entry: {
      application: [pluginEntry],
    },
    output: {
      path: pluginPublicPath,
      publicPath: '',
      filename: outputJs,
    },

    module: allModules,

    optimization: {
      minimizer: [
        new UglifyJSPlugin({
          uglifyOptions: {
            compress: false,
            ecma: 6,
            mangle: true,
          },
          sourceMap: true,
        }),
      ],
    },

    plugins: allPlugins,
  },
];
