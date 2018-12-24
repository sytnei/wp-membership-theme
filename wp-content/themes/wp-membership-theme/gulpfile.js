/*global require*/
"use strict";

const gulp = require("gulp"),
    path = require("path"),
    plumber = require('gulp-plumber'),
    data = require("gulp-data"),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    uglify = require('gulp-uglify'),
    prefix = require("gulp-autoprefixer"),
    cssmin = require('gulp-cssnano'),
    sass = require("gulp-sass"),
    browserSync = require("browser-sync"),
    mergeJson = require("merge-json"),
    clean = require("gulp-clean"),
    runSequence = require("run-sequence");

/*
 * Directories here
 */
const paths = {
    scss: "./src/scss/",
    srcJs: "./src/js/",
    css: "./css/",
    js: "./js/",
    nodeModules: "./node_modules/"
};

/**
 * Wait scss tasks, then launch the browser-sync Server
 */
gulp.task("browser-sync", ["scss"], () => {
    var files = [
        paths.scss + '/*',
        paths.srcJs + '/*'
    ];
    browserSync.init(files, {
        open: true,
        server: false,
        proxy: {
            target: "http://localhost/wp-membership-theme"
        }
    });
    /*browserSync({
         server: {
             baseDir: paths.public
         },
         notify: false
     });*/
});

/**
 * Compile .scss files into public css directory With autoprefixer no
 * need for vendor prefixes then live reload the browser.
 */
gulp.task("scss", () => {
    return gulp.src(paths.scss + "**/*.scss")
        .pipe(plumber({
            errorHandler: function(err) {
                console.log(err);
                this.emit('end');
            }
        })).pipe(sass({
            includePaths: [paths.scss]
        }))

    .pipe(prefix(["last 15 versions", "> 1%", "ie 8", "ie 7"], {
            cascade: true
        }))
        .pipe(rename('wp-membership-theme.css'))
        .pipe(gulp.dest(paths.css))
        .pipe(cssmin())
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest(paths.css))
        .pipe(browserSync.reload({
            stream: true
        }));
});

/**
 * Watch scss files for changes & recompile
 * Watch .pug files run pug-rebuild then reload BrowserSync
 */
gulp.task("watch", () => {
    gulp.watch(paths.scss + "**/*.scss", ["scss"]);
    gulp.watch(paths.srcJs + "/*.js", ["scripts"]);
});


/**
 * Move js files
 */
gulp.task("scripts", () => {

    return gulp.src([
    	    paths.srcJs + "jquery-3.3.1.min.js", 
    	    paths.srcJs + "popper.min.js", 
    	    paths.nodeModules + 'bootstrap/dist/js/bootstrap.min.js',
            paths.srcJs + "scripts.js"
        ])
        .pipe(concat('wp-membership-theme.js'))
        .pipe(gulp.dest(paths.js))
        .pipe(rename('wp-membership-theme.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(paths.js))
        .pipe(browserSync.reload({
            stream: true
        }));
});


// Build task compile scss and pug.
gulp.task("build", (callback) => {
    runSequence(
        "scripts",
        "scss",
        callback
    );
});

/**
 * Default task, running just `gulp` will compile the scss, launch BrowserSync then watch files for changes
 */
gulp.task("default", (callback) => {
    runSequence(
        "scss",
        "scripts",
        "browser-sync",
        "watch"
    )
});