//
module.exports = function(grunt) {
  // Project configuration.
  grunt.initConfig({
    csscss: {
      options: {
        colorize: true
      },
      dist: {
        src: [
          '*.css',
          'article_wrappers/*/*.css'
        ]
      }
    },
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
    },
    // app configuration file
    appConfig: grunt.file.readJSON( 'app-config.json' ) || {},
    banner: '/* ' + "\n" + 
            '* <%= appConfig.info.name %>' + "\n" +
            '*' + "\n" +
            '* @version: <%= appConfig.info.version %>' + "\n" +
            '* @date: <%= grunt.template.today("dd-mm-yyyy") %>' + "\n" +
            '* @desc: <%= appConfig.info.description %>' + "\n" + 
            '* @author: <%= appConfig.info.author.name %> ' + "\n" +
            '* @email: <%= appConfig.info.author.email %>' + "\n" +
            '*' + "\n" +
            '*/',
    usebanner: {
      dist: {
        options: {
          position: 'top',
          banner: '<%= banner %>'
        },
        files: {
          src: [ 
            'gk-nsp.js',
            'gk-nsp-admin.js',
            'article_wrappers/*/*.js',
            '*.css',
            'article_wrappers/*/*.css',
            '*.less'
          ]
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-csscss');
  grunt.loadNpmTasks('grunt-jsbeautifier');
  grunt.loadNpmTasks('grunt-cssbeautifier');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-banner');

  grunt.registerTask('default', ['csscss', 'jshint', 'cssbeautifier', 'jsbeautifier', 'usebanner']);
};