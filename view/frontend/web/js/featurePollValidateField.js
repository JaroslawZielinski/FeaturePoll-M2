define([
    'jquery',
    'mage/translate',
    'jquery/ui',
    'jquery/validate'
], function($, $t){
    'use strict';
    return function() {
        $.validator.addMethod(
            'extended-information',
            function(value, element) {
                const name = $(element).attr('name');
                const checkerHtmlId = name.replaceAll('vote_', '');
                const isChecked = $('#' + checkerHtmlId).prop('checked');
                return !isChecked || !$.mage.isEmpty(value)
            },
            $t('This is a required extended information field.')
        );
    }
});
