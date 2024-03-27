const{src,dest, watch, parallel} = require("gulp");
//css
const sass = require("gulp-sass")(require('sass'));
const plumber = require('gulp-plumber');

// imagenes
// la implementacion de gulp-web no me fue posible, por lo que deje todo comentariado
// const webp = require('gulp-webp');
const cache = require('gulp-cache');
const imagemin = require('gulp-imagemin');
const avif = require('gulp-avif');

function css(done){
    src("src/scss/**/*.scss")
        .pipe(plumber())
        .pipe(sass())
        .pipe(dest("build/css"))

    done();
}

function imagenes(done){

    const opciones = {
        optimizationLevel : 3
    }

    src('src/img/**/*.{png,jpg}')
        .pipe( cache( imagemin(opciones) ) )
        .pipe( dest('build/img') )

    done();
}

function versionAvif(done){
    const opciones ={
        quality: 50 //valores de 0 a 100
    };

    src('src/img/**/*.{png,jpg}')
        .pipe( avif(opciones))
        .pipe( dest('build/img'))

    done();

}

// function versionWebp(done){
//     const opciones ={
//         quality: 50 //valores de 0 a 100
//     };

//     src('src/img/**/*.{png,jpg}')
//         .pipe( webp(opciones))
//         .pipe( dest('build/img'))

//     done();

// }

function javascript(done){
    src('src/js/**/*.js')
        .pipe(dest('build/js'));

    done();
}

function dev(done){
    watch("src/scss/**/*.scss",css)
    watch("src/js/**/*.js",javascript)
    done();
}

exports.css = css;
exports.js = javascript;
exports.imagenes = imagenes;
exports.versionAvif = versionAvif;
// exports.versionWebp = versionWebp;

exports.dev = parallel(imagenes,versionAvif,javascript,dev);
// exports.dev = dev;
