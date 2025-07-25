define([
    'jquery'
], function($) {
    'use strict';

    return {
        ajaxGetItJson: function (ajaxUrl, data, callBack, isLoader) {
            let self = this;
            const showLoader = undefined !== isLoader ? isLoader : false;
            $.ajax({
                showLoader: showLoader,
                url: ajaxUrl,
                data: data,
                type: 'GET',
                dataType: 'json'
            }).done(function (data) {
                callBack(data);
                self.ajaxResults = data;
            });
            return self.ajaxResults;
        }
    };
});
