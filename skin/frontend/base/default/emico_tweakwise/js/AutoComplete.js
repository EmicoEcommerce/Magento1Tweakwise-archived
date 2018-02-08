Varien.searchForm.prototype = Object.extend(Varien.searchForm.prototype, {

    initAutocomplete : function(url, destinationElement, minSearchChars) {
        this.url = url;
        this.destinationElement = destinationElement;
        this.minSearchChars = minSearchChars || 2;
    },

    setMinSearchChars : function(chars) {
        this.minSearchChars = chars;
    },

    getRequestUrl : function() {
        var url = this.url;
        var categoryId = this.getCategory();
        if (categoryId) {
            url += '?categoryid=' + categoryId;
        }
        return url;
    },

    initAutocompleteDelayed : function(){
        url = this.url;
        destinationElement = this.destinationElement;

        var searchForm = this;
        var autocompleter = new Ajax.Autocompleter(
            this.field,
            destinationElement,
            this.getRequestUrl(),
            {
                paramName: this.field.name,
                method: 'get',
                minChars: this.minSearchChars || 2,
                updateElement: this._selectAutocompleteItem.bind(this),
                onShow : function(element, update) {
                    if(!update.style.position || update.style.position=='absolute') {
                        update.style.position = 'absolute';
                        Position.clone(element, update, {
                            setHeight: false,
                            offsetTop: element.offsetHeight
                        });
                    }
                    Effect.Appear(update,{duration:0});
                }

            }
        );
        autocompleter.renderOriginal = autocompleter.render;
        autocompleter.render = function()
        {
            autocompleter.renderOriginal();
            if(!this.active || !this.index)
            {
                return;
            }

            var element = this.getEntry(this.index);
            if(!element)
            {
                return;
            }

            searchForm.updateFieldValue(element);
        }
    },

    _selectAutocompleteItem : function(element){
        this.updateFieldValue(element);
        if(element.hasClassName('product'))
        {
            window.location.href = element.down().href;
        }
        else if(this.field.value)
        {
            this.form.submit();
        }
    },

    updateFieldValue: function(element) {
        if(element.hasClassName('suggestion'))
        {
            this.field.value = element.innerHTML;
        }
        else if(element.hasClassName('product'))
        {
            this.field.value = element.down().title;
        }
        else if(element.title)
        {
            this.field.value = element.title;
        }
    },

    setCategory : function (categoryId) {
        this.categoryId = categoryId;
    },

    getCategory : function () {
        return this.categoryId;
    }
});