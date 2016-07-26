(function($) {
    $.fn.tzCheckbox = function(options) {
        options = $.extend({
            labels: ['ON', 'OFF']
        }, options);
        return this.each(function() {
            var originalCheckBox = $(this),
                    labels = [];
            if (originalCheckBox.data('on')) {
                labels[0] = originalCheckBox.data('on');
                labels[1] = originalCheckBox.data('off');
            }
            else
                labels = options.labels;
            var checkBox = $('<span>', {
                class: 'tzCheckBox ' + (this.checked ? 'checked' : ''),
                html: '<span class="tzCBContent">' + labels[this.checked ? 0 : 1] +
                        '</span><span class="tzCBPart"></span>'
            });

            // Inserting the new checkbox, and hiding the original:
            checkBox.insertAfter(originalCheckBox.hide());

            checkBox.click(function() {
                checkBox.toggleClass('checked');

                var isChecked = checkBox.hasClass('checked');

                // Synchronizing the original checkbox:
                originalCheckBox.attr('checked', isChecked);
                checkBox.find('.tzCBContent').html(labels[isChecked ? 0 : 1]);
            });

            // Listening for changes on the original and affecting the new one:
            originalCheckBox.bind('change', function() {
                checkBox.click();
            });
        });
    };
})(jQuery);
jQuery(function($) {
    $.datepicker.regional['zh-CN'] = {
        closeText: '关闭',
        prevText: '<上月',
        nextText: '下月>',
        currentText: '今天',
        monthNames: ['一月', '二月', '三月', '四月', '五月', '六月',
            '七月', '八月', '九月', '十月', '十一月', '十二月'],
        monthNamesShort: ['一月', '二月', '三月', '四月', '五月', '六月',
            '七月', '八月', '九月', '十月', '十一月', '十二月'],
        dayNames: ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
        dayNamesShort: ['周日', '周一', '周二', '周三', '周四', '周五', '周六'],
        dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
        weekHeader: '周',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: true};
    $.datepicker.setDefaults($.datepicker.regional['zh-CN']);
});
$(document).ready(function() {
    $(".input_date").each(function() {
        $(this).datepicker({
            changeMonth: true, dateFormat: "yy-mm-dd", changeYear: true}
        );
    });
    $('input.cbox').tzCheckbox({labels:['开启','关闭']});
});