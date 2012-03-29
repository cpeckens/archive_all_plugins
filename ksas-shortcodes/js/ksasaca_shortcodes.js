(function() {
    tinymce.create('tinymce.plugins.acctitle', {
        init : function(ed, url) {
            ed.addButton('acctitle', {
                title : 'Accordion Title',
                image : url+'/acctitle.png',
                onclick : function() {
                     ed.selection.setContent('[acctitle]' + ed.selection.getContent() + '[/acctitle]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('acctitle', tinymce.plugins.acctitle);
})();

(function() {
    tinymce.create('tinymce.plugins.acccontent', {
        init : function(ed, url) {
            ed.addButton('acccontent', {
                title : 'Accordion Content',
                image : url+'/acccontent.png',
                onclick : function() {
                     ed.selection.setContent('[acccontent]' + ed.selection.getContent() + '[/acccontent]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('acccontent', tinymce.plugins.acccontent);
})();

(function() {
    tinymce.create('tinymce.plugins.readmoretitle', {
        init : function(ed, url) {
            ed.addButton('readmoretitle', {
                title : 'Read More Title',
                image : url+'/readmoretitle.png',
                onclick : function() {
                     ed.selection.setContent('[readmoretitle]' + ed.selection.getContent() + '[/readmoretitle]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('readmoretitle', tinymce.plugins.readmoretitle);
})();

(function() {
    tinymce.create('tinymce.plugins.readmorecontent', {
        init : function(ed, url) {
            ed.addButton('readmorecontent', {
                title : 'Read More Content',
                image : url+'/readmorecontent.png',
                onclick : function() {
                     ed.selection.setContent('[readmorecontent]' + ed.selection.getContent() + '[/readmorecontent]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('readmorecontent', tinymce.plugins.readmorecontent);
})();