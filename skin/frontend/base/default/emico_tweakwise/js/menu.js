document.observe('dom:loaded', function(){
    $$('#nav div.level0').each(function(columnContainer) {
        var columns = columnContainer.select('.column');

        var containerWidth = 0;
        columns.each(function(column) {
            containerWidth += jQuery(column).width();
            containerWidth += parseInt(column.getStyle('margin-left'));
            containerWidth += parseInt(column.getStyle('margin-right'));
            containerWidth += parseInt(column.getStyle('padding-right'));
            containerWidth += parseInt(column.getStyle('padding-left'));
            containerWidth += parseInt(column.getStyle('border-left-width'));
            containerWidth += parseInt(column.getStyle('border-right-width'));
        });

        columnContainer.setStyle({width: containerWidth + "px"});
    });
});