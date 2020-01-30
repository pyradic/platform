
import $ from 'cash-dom'

$.fn.ensureClass = function (className, remove) {
    remove = remove || false;
    return this.each(function () {
        let $this = $(this);
        if ( remove ) {
            $this.removeClass(className);
        } else {
            if ( !$this.hasClass(className) ) {
                $this.addClass(className);
            }
        }
    });
};
