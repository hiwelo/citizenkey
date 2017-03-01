'use strict';

/**
 * Gulp components
 */
import gulp from 'gulp';
import gulpLoadPlugins from 'gulp-load-plugins';

/**
 * Project configuration
 * @type {Object}
 */
const project = {
  name: 'CitizenKeys',
  url: 'http://citizenkeys.dev/app_dev.php/',
  config: {
    minFileSuffix: '.min',
    globalJSFile: 'global',
    vendorJSFile: 'vendor',
    autoprefixer: ['> 1%', 'Firefox ESR', 'last 2 versions', 'not ie <= 10'],
    babel: ['es2015', 'es2016', 'es2017'],
    uglify: { preserveComments: 'license' },
    scssExternalPaths: ['node_modules/foundation/scss'],
  }
};

/**
 * Projects files paths
 * @type {Object}
 */
project.paths = {
  root: {
    src: './web-assets/',
    dist: './web/',
    vendors: './node_modules/',
  },
  styles: {
    root: 'css/',
    scss: 'css/*.scss',
    scssFiles: ['css/*.scss', 'css/**/*.scss'],
  },
  scripts: {
    root: 'js/',
    files: ['js/*.js', 'js/**/*.js'],
  },
  images: {
    root: 'img/',
    files: 'img/*.{png,jpg,jpeg,gif,svg}',
  },
  fonts: {
    root: 'fonts/',
    files: 'fonts/*.{woff,woff2,ttf}',
  },
};

/**
 * Vendors to load and compile with all custom JS scripts of this project
 * @type {Object}
 */
project.jsVendors = {
  list: [
    `${project.paths.root.vendors}jquery/dist/jquery.js`,
  ],
};

/**
 * Gulp components init
 */
const $ = gulpLoadPlugins();

/**
 * Error task
 * @type {Object}
 */
const onError = {
  errorHandler: function (err) {
    console.log(err);
    $.notify.onError({
      title: `Gulp ${project.name}`,
      subtitle: 'Compilation error',
      message: 'Error: <%= error.message %>',
    })(err);
    this.emit('end');
  },
};


/***********************************
 * Gulp tasks: css, js, img, fonts *
 ***********************************/

/**
 * CSS task: Sass compilation + autoprefixer + css optimizer + rename (.min)
 */
gulp.task('css', () => {
  gulp.src(project.paths.root.src + project.paths.styles.scss)
      .pipe($.plumber(onError))
      .pipe($.sass({ includePaths: project.config.scssExternalPaths }))
      .pipe($.autoprefixer({ browsers: project.config.autoprefixer }))
      .pipe($.myth())
      .pipe(gulp.dest(project.paths.root.dist + project.paths.styles.root)) // *.css
      .pipe($.csso())
      .pipe($.rename({ suffix: project.config.minFileSuffix }))
      .pipe(gulp.dest(project.paths.root.dist + project.paths.styles.root)); // *.min.css
});

/**
 * JS task: Babel + Concat + Uglify
 */
gulp.task('js', () => {
  gulp.src(project.paths.root.src + project.paths.scripts.files)
      .pipe($.plumber(onError))
      .pipe($.babel({ presets: project.config.babel }))
      .pipe($.concat(`${project.config.globalJSFile}${project.config.minFileSuffix}`))
      .pipe($.uglify(project.config.uglify))
      .pipe(gulp.dest(project.paths.root.dist + project.paths.scripts.root));
});

/**
 * JS vendors task: Concat + Uglify
 */
gulp.task('vendor', () => {
  gulp.src(project.jsVendors.list)
      .pipe($.plumber(onError))
      .pipe($.concat(`${project.config.vendorJSFile}${project.config.minFileSuffix}.js`))
      .pipe($.uglify(project.config.uglify))
      .pipe(gulp.dest(project.paths.root.dist + project.paths.scripts.root));
});

/**
 * Gulp watch task
 */
gulp.task('watch', () => {
  gulp.watch(project.paths.root.src + project.paths.styles.scssFiles, ['css']);
  gulp.watch(project.paths.root.src + project.paths.scripts.files, ['js']);
});

/**
 * Global tasks
 */
gulp.task('default', ['css', 'js', 'vendor']);
