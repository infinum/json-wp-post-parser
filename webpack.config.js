const DEV = process.env.NODE_ENV !== 'production';

const path = require('path');

const webpack = require('webpack');
const CleanWebpackPlugin = require('clean-webpack-plugin');
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
  new CleanWebpackPlugin([output]),
  new webpack.optimize.ModuleConcatenationPlugin(),
  new webpack.DefinePlugin({
    'process.env': {
      NODE_ENV: JSON.stringify(process.env.NODE_ENV || 'development')
    }
  })
];

// Use only for production build
if (!DEV) {
  allPlugins.push(
    new UglifyJSPlugin({
      comments: false,
      sourceMap: true
    })
  );
}

module.exports = [
  {
    context: path.join(__dirname),
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
