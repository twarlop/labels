var gulp = require('gulp');
var rename = require('gulp-rename');
var less = require('gulp-less');
var minify = require('gulp-minify-css');
var concat = require('gulp-concat');


gulp.task('styles', function()
{
    gulp.src('css/etiketten.less')
        .pipe(less())
        .pipe(minify())
        .pipe(rename(function(path){
            path.basename = 'etiketten-new';
        }))
        //no need to copy it to the distribution folder as the other watcher will take care of this.
        .pipe(gulp.dest('../../../css/views/etiketten'));
});

gulp.task('scripts', function(){
    gulp.src([
        'js/src/init.js',
        'js/src/queue.js',
        'js/src/options.js',
        'js/src/confirmation.js',
        'js/src/CategoryInspect.js',
        'js/src/suggestProduct.js',
        'js/src/suggestCategory.js',
    ])
        .pipe(concat('etiketten.js'))
        .pipe(rename(function(path){
            path.basename = 'etiketten-new';
        }))
        .pipe(gulp.dest('../../../js/views/etiketten'));
});


gulp.task('watch-styles', function(){
    gulp.watch('css/**/*.less', ['styles']);
});

gulp.task('watch-scripts', function(){
    gulp.watch('js/src*/*', ['scripts']);
});

gulp.task('default', ['watch-styles', 'watch-scripts']);