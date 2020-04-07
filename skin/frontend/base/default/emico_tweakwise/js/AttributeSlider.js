/**
 * Event.simulate(@element, eventName[, options]) -> Element
 *
 * - @element: element to fire event on
 * - eventName: name of event to fire (only MouseEvents and HTMLEvents interfaces are supported)
 * - options: optional object to fine-tune event properties - pointerX, pointerY, ctrlKey, etc.
 *
 *    $('foo').simulate('click'); // => fires "click" event on an element with id=foo
 *
 **/
(function(){

    var eventMatchers = {
        'HTMLEvents': /^(?:load|unload|abort|error|select|change|submit|reset|focus|blur|resize|scroll)$/,
        'MouseEvents': /^(?:click|mouse(?:down|up|over|move|out))$/
    }
    var defaultOptions = {
        pointerX: 0,
        pointerY: 0,
        button: 0,
        ctrlKey: false,
        altKey: false,
        shiftKey: false,
        metaKey: false,
        bubbles: true,
        cancelable: true
    }

    Event.simulate = function(element, eventName) {
        var options = Object.extend(defaultOptions, arguments[2] || { });
        var oEvent, eventType = null;

        element = $(element);

        for (var name in eventMatchers) {
            if (eventMatchers[name].test(eventName)) { eventType = name; break; }
        }

        if (!eventType)
            throw new SyntaxError('Only HTMLEvents and MouseEvents interfaces are supported');

        if (document.createEvent) {
            oEvent = document.createEvent(eventType);
            if (eventType == 'HTMLEvents') {
                oEvent.initEvent(eventName, options.bubbles, options.cancelable);
            }
            else {
                oEvent.initMouseEvent(eventName, options.bubbles, options.cancelable, document.defaultView,
                    options.button, options.pointerX, options.pointerY, options.pointerX, options.pointerY,
                    options.ctrlKey, options.altKey, options.shiftKey, options.metaKey, options.button, element);
            }
            element.dispatchEvent(oEvent);
        }
        else {
            options.clientX = options.pointerX;
            options.clientY = options.pointerY;
            oEvent = Object.extend(document.createEventObject(), options);
            element.fireEvent('on' + eventName, oEvent);
        }
        return element;
    }

    Element.addMethods({ simulate: Event.simulate });
})()

var TweakwiseAttributeSlider = Class.create();
TweakwiseAttributeSlider.prototype = {
    options: {
        sliderElement: null,
        minInput: null,
        maxInput: null,
        updateLink: null,
        minValue: null,
        maxValue: null,
        slider: null,
        sliderMinHandle: null,
        sliderMaxHandle: null,
        urlKey: null,
        displayLowerFilter: null,
        displayUpperFilter: null,
        assignFilter: null
    },
    slider: null,
    lowerValue: null,
    upperValue: null,
    enterUpperValue: null,
    enterLowerValue: null,
    initialized: false,

    /** Constructor **/
    initialize: function(options) {
        this.options = options || { };
        this.getSlider();
        this.initInputField(this.getMinInput(), this.setLowerValue.bind(this));
        this.initInputField(this.getMaxInput(), this.setUpperValue.bind(this));

        this.setValue(this.getMinValue(), this.getMaxValue());
        this.updateLinkValue();
        this.updateSliderValue();
        this.updateInputValue();
    },

    /** Options getters **/
    getSliderId: function()
    {
        return this.options.sliderId;
    },
    getMinValue: function()
    {
        return this.options.minValue;
    },
    getMaxValue: function()
    {
        return this.options.maxValue;
    },
    getUrlKey: function()
    {
        return this.options.urlKey;
    },
    getSliderContainer: function()
    {
        if(typeof(this.options.sliderContainer) == 'string')
        {
            this.options.sliderContainer = $(this.options.sliderContainer);
        }
        return this.options.sliderContainer;
    },
    getMinInput: function()
    {
        return this.getSliderContainer().down('.input-min');
    },
    getMaxInput: function()
    {
        return this.getSliderContainer().down('.input-max');
    },
    getUpdateLink: function()
    {
        return this.getSliderContainer().down('.update-link');
    },
    getSliderElement: function()
    {
        return this.getSliderContainer().down('.slider');
    },
    getSliderMinHandle: function()
    {
        return this.getSliderContainer().down('.handle-min');
    },
    getSliderMaxHandle: function()
    {
        return this.getSliderContainer().down('.handle-max');
    },
    getSlider: function()
    {
        if(this.slider == null)
        {
            this.slider = new Control.Slider(
                [
                    this.getSliderMinHandle(),
                    this.getSliderMaxHandle()
                ],
                this.getSliderElement(),
                {
                    range: $R(this.getMinValue(), this.getMaxValue(), false),
                    step: 1,
                    sliderValue: [this.getMinValue(), this.getMaxValue()],
                    restricted: true,
                    onSlide: function(value)
                    {
                        this.updateSlider(value);
                    }.bind(this),
                    onChange: function()
                    {
                        if(this.initialized)
                        {
                            this.getUpdateLink().simulate('click');
                        }
                    }.bind(this)
                }
            );
        }
        return this.slider;
    },

    updateSlider: function(value)
    {
        this.setValue(
            this.filterAssign(this.filterDisplayLower(value[0])),
            this.filterAssign(this.filterDisplayUpper(value[1]))
        );
        this.updateInputValue();
        this.updateLinkValue();
        this.setValue.bind(this);
    },

    /** object methods **/
    intValue: function(value, boundValue)
    {
        if(typeof(value) != 'number')
        {
            value = value.replace(',', '.');
            value = parseFloat(value);
            if(isNaN(value))
            {
                value = boundValue;
            }
        }
        return value;
    },
    getAllowedValue: function(value, boundValue, check)
    {
        value = this.intValue(value, boundValue);
        value = check(value, boundValue) ? value : boundValue;
        return value;
    },
    getAllowedMinValue: function(value)
    {
        return this.getAllowedValue(value, this.getMinValue(), function(a, b) { return a > b; });
    },
    getAllowedMaxValue: function(value)
    {
        return this.getAllowedValue(value, this.getMaxValue(), function(a, b) { return a < b; });
    },
    updateSliderValue: function()
    {
        this.getSlider().setValue(this.getLowerValue(), 0);
        this.getSlider().setValue(this.getUpperValue(), 1);
    },
    updateLinkValue: function()
    {
        var regexp = new RegExp(this.getUrlKey() + '=([0-9]+|from)-([0-9]+|to)', 'g');
        var updateLink = this.getUpdateLink();
        updateLink.href = updateLink.href.replace(regexp, this.getUrlKey() + '=' + this.getLowerValue() + '-' + this.getUpperValue());
    },
    filterDisplay: function(value, filter)
    {
        return filter != null ? filter(value) : value;
    },
    filterDisplayLower: function(value)
    {
        return this.filterDisplay(value,  this.options.displayLowerFilter);
    },
    filterDisplayUpper: function(value)
    {
        return this.filterDisplay(value,  this.options.displayUpperFilter);
    },
    filterAssign: function(value)
    {
        if(this.options.assignFilter != null)
        {
            value = this.options.assignFilter(value);
        }
        return value;
    },
    updateInputValue: function()
    {
        this.getMinInput().setValue(this.filterDisplayLower(this.getLowerValue()));
        this.getMaxInput().setValue(this.filterDisplayUpper(this.getUpperValue()));
    },
    setValue: function(minValue, maxValue)
    {
        this.setLowerValue(minValue);
        this.setUpperValue(maxValue);
    },
    setLowerValue: function(value)
    {
        this.lowerValue = this.getAllowedMinValue(value);
        return this;
    },
    getLowerValue: function(){
        return this.lowerValue;
    },
    setUpperValue: function(value)
    {
        this.upperValue = this.getAllowedMaxValue(value);
        return this;
    },
    getUpperValue: function(){
        return this.upperValue;
    },
    initInputField: function(field, setAction)
    {
        field.observe('change', function(){
            var value = this.intValue($F(field), NaN);
            if(!isNaN(value))
            {
                value = this.filterAssign(value);
            }
            setAction(value);
            this.updateLinkValue();
            this.updateSliderValue();
        }.bind(this));
    },
    setInitialized: function()
    {
        this.initialized = true;
    }
};

function initTweakwiseSlider(element)
{
    var slider = new TweakwiseAttributeSlider({
        sliderContainer: element,
        urlKey: element.readAttribute('data-url-key'),
        minValue: Math.floor(parseFloat(element.readAttribute('data-min-value'))),
        maxValue: Math.ceil(parseFloat(element.readAttribute('data-max-value'))),
        displayLowerFilter: function(value){ return value; },
        displayUpperFilter: function(value){ return value; },
        assignFilter: function(value){ return Math.round(value); }
    });
    slider.setValue(
        Math.floor(parseFloat(element.readAttribute('data-lower-value'))),
        Math.ceil(parseFloat(element.readAttribute('data-upper-value')))
    );

    slider.updateLinkValue();
    slider.updateSliderValue();
    slider.updateInputValue();
    slider.setInitialized();

    element.slider = slider;
}

function initTweakwiseSliders()
{
    $$('.slider-container').each(initTweakwiseSlider);
}

document.observe('dom:loaded', initTweakwiseSliders);
document.observe('list:loaded', initTweakwiseSliders);

// Required for RWD theme when sliders are not visible from start
if(typeof $j == 'function')
{
    $j(document).ready(function () {
        // Added a small delay so the event handler ends up after the RWD event handler
        setTimeout(function(){
            if ($j(document).width() > 769) {
                return;
            }

            $j('.toggle-content').each(function () {
                var wrapper = $j(this);
                wrapper.sliderClickInitialized = false;

                wrapper.children('dl:first')
                    .children('dt')
                    .on('click', function (e) {
                        if(wrapper.sliderClickInitialized)
                        {
                            return;
                        }
                        wrapper.find('.slider-container').each(function() { initTweakwiseSlider(this); });
                        wrapper.sliderClickInitialized = true;
                    });
            });
        }, 10);
    });
}
