
var TweakwiseRecommendations = Class.create();
TweakwiseRecommendations.prototype = {

    /**
     * @param ajaxUrl
     * @param wrapperId
     */
    initialize: function (ajaxUrl, wrapperId) {
        this.ajaxUrl = ajaxUrl;
        this.wrapperId = wrapperId;

        this.getRecommendationsBlock();
    },

    /**
     * Add ajax loader class
     */
    addLoader: function() {
        $(this.wrapperId).addClassName('loading');
    },

    /**
     * Remove loader
     */
    removeLoader: function() {
        $(this.wrapperId).removeClassName('loading');
    },

    /**
     * Call the controller to get the Recommendations block
     */
    getRecommendationsBlock: function () {
        this.addLoader();
        new Ajax.Request(this.ajaxUrl, {
            method: 'get',
            onComplete: this.removeLoader.bind(this),
            onSuccess: function(response) {
                this.updateRecommendationsBlock(response.responseText);
            }.bind(this)
        });
    },

    /**
     * Update the wrapper and add the returned html
     *
     * @param data
     */
    updateRecommendationsBlock: function (data) {
        $(this.wrapperId).replace(data);
    }
};