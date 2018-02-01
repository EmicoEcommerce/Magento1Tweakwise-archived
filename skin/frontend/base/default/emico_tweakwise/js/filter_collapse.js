function initTweakwiseCollapseLinks(){
    $$('#narrow-by-list .more-link').each(function(showLink) {
        var hideLink = showLink.up().select('.less-link')[0];
        var hiddenElements = showLink.up('li').up().select('li.hidden');

        showLink.observe('click', function(event){
            event.stop();

            showLink.addClassName('hidden');
            hideLink.removeClassName('hidden');
            hiddenElements.each(function(element){ element.removeClassName('hidden'); });
        });

        hideLink.observe('click', function(event){
            event.stop();

            hideLink.addClassName('hidden');
            showLink.removeClassName('hidden');
            hiddenElements.each(function(element){ element.addClassName('hidden'); });
        });
    });

    $$('#narrow-by-list .collapsible').each(function(header) {
        var items = header.next('dd');
        header.observe('click', function(event){
            if(items.hasClassName('hidden'))
            {
                items.removeClassName('hidden');
                header.removeClassName('collapsed');
            }
            else
            {
                items.addClassName('hidden');
                header.addClassName('collapsed');
            }
        });
    });
}

document.observe('dom:loaded', initTweakwiseCollapseLinks);
document.observe('list:loaded', initTweakwiseCollapseLinks);