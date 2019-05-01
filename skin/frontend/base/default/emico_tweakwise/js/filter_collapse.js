function initTweakwiseCollapseLinks(){
    $$('#narrow-by-list .more-link').each(function(showLink) {
        var hideLink = showLink.up().select('.less-link')[0];
        var hiddenElements = showLink.up('li').up().select('li.hidden');
        var list = showLink.up('ol');
        var elements = showLink.up('ol').select('li');
        var hasAlternateSort = showLink.up('ol').readAttribute('data-has-alternate-sort');

        showLink.observe('click', function(event){
            event.stop();

            showLink.addClassName('hidden');
            hideLink.removeClassName('hidden');
            hiddenElements.each(function(element){ element.removeClassName('hidden'); });
            if (hasAlternateSort) {
                sortFilterItems('data-alternate-sort', elements, list);
            }
        });

        hideLink.observe('click', function(event){
            event.stop();

            hideLink.addClassName('hidden');
            showLink.removeClassName('hidden');
            hiddenElements.each(function(element){ element.addClassName('hidden'); });
            if (hasAlternateSort) {
                sortFilterItems('data-original-sort', elements, list);
            }
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

function sortFilterItems(type, elements, list) {
    elements.sort(function (a, b) {
        return a.readAttribute(type) - b.readAttribute(type);
    });
    jQuery(list).append(elements);
    //list.appendChild(elements);
}

document.observe('dom:loaded', initTweakwiseCollapseLinks);
document.observe('list:loaded', initTweakwiseCollapseLinks);