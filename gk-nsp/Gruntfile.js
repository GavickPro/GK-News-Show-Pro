//
module.exports = function(grunt) {
  // Project configuration.
  grunt.initConfig({
    jsbeautifier: {
        files : [
          'gk-nsp.js', 
          'gk-nsp-admin.js',
          'article_wrappers/*/*.js'
        ],
        options : {
        }
    },
    cssbeautifier : {
      files : [
        '*.css',
        'article_wrappers/*/*.css'
      ]
    },
    jshint: {
      allFiles: [
        'gk-nsp.js',
        'gk-nsp-admin.js',
        'article_wrappers/*/*.js'
      ],
      options: {
        jshintrc: '.jshintrc'
      }
    }
  });

  grunt.loadNpmTasks('grunt-jsbeautifier');
  grunt.loadNpmTasks('grunt-cssbeautifier');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.registerTask('default', ['jshint', 'cssbeautifier', 'jsbeautifier']);
};