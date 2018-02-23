// Project configuration
const project = 'json-wp-post-parser';

const gulp = require('gulp');
const phpunit = require('gulp-phpunit');
const gprint = require('gulp-print').default;
const del = require('del');
const vinylPaths = require('vinyl-paths');
const runSequence = require('run-sequence');
const webpack = require('webpack');
const webpackConfig = require('./webpack.config.js');

const paths = {
  src: './src/',
  build: './build/',
  entry: './src/skin/assets/application.js'
};

gulp.task('clean', () => (
  gulp.src(`${paths.build}*`)
    .pipe(gprint())
    .pipe(vinylPaths(del))
));

gulp.task('phpunit', () => (
  gulp.src('phpunit.xml')
    .pipe(phpunit())
));

gulp.task('webpack', () => (
  new Promise((resolve, reject) => {
    webpack(webpackConfig, (err, stats) => {
      if (err) {
        reject(err);
        return;
      }
      resolve(stats);
    });
  })
));

gulp.task('buildFiles', () => (
  gulp.src(`${paths.src}**/*`)
    .pipe(gulp.dest(`${paths.build}/${project}/`))
));

gulp.task('cleanSkin', () => (
  gulp.src(`${paths.build}/${project}/skin`)
    .pipe(vinylPaths(del))
));

gulp.task('copyAssets', () => (
  gulp.src(`${paths.build}/assets/**/*`)
    .pipe(gulp.dest(`${paths.build}/${project}/assets/`))
));

gulp.task('removeAssets', () => (
  gulp.src(`${paths.build}/assets/`)
    .pipe(vinylPaths(del))
));



gulp.task('build', (cb) => runSequence('clean', 'webpack', 'buildFiles', 'cleanSkin', 'copyAssets', 'removeAssets', cb));
