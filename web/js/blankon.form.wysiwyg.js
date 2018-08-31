var BlankonFormWysiwyg = function () {

    return {

        // =========================================================================
        // CONSTRUCTOR APP
        // =========================================================================
        init: function () {
            BlankonFormWysiwyg.bootstrapWYSIHTML5();
            BlankonFormWysiwyg.summernote();
        },

        // =========================================================================
        // BOOTSTRAP WYSIHTML5
        // =========================================================================
        bootstrapWYSIHTML5: function () {
            if($('#wysihtml5-textarea').length){
                $('#wysihtml5-textarea').wysihtml5();
            }
        },

        // =========================================================================
        // SUMMERNOTE
        // =========================================================================
        summernote: function () {
            if($('.summernote').length){
                $('.summernote').summernote({toolbar: [
        //[groupname, [button list]]

         ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'superscript', 'subscript', 'strikethrough', 'clear']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']], // Still buggy
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']],
        ['table', ['table']],
        //['insert', ['link', 'picture', 'video', 'hr']],
		['insert', ['link', 'hr', 'picture']],
        ['view', ['fullscreen', 'codeview']],
        ['help', ['help']]
    ]});
            }
        }

    };

}();

// Call main app init
BlankonFormWysiwyg.init();