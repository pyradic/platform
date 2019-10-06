
const NAME               = 'modal'
const VERSION            = '4.3.1'
const DATA_KEY           = 'bs.modal'
const EVENT_KEY          = `.${DATA_KEY}`
const DATA_API_KEY       = '.data-api'
const JQUERY_NO_CONFLICT = $.fn[NAME]
const ESCAPE_KEYCODE     = 27 // KeyboardEvent.which value for Escape (Esc) key

const Default = {
    backdrop : true,
    keyboard : true,
    focus    : true,
    show     : true
}

const DefaultType = {
    backdrop : '(boolean|string)',
    keyboard : 'boolean',
    focus    : 'boolean',
    show     : 'boolean'
}

const EV = {
    HIDE              : `hide${EVENT_KEY}`,
    HIDDEN            : `hidden${EVENT_KEY}`,
    SHOW              : `show${EVENT_KEY}`,
    SHOWN             : `shown${EVENT_KEY}`,
    FOCUSIN           : `focusin${EVENT_KEY}`,
    RESIZE            : `resize${EVENT_KEY}`,
    CLICK_DISMISS     : `click.dismiss${EVENT_KEY}`,
    KEYDOWN_DISMISS   : `keydown.dismiss${EVENT_KEY}`,
    MOUSEUP_DISMISS   : `mouseup.dismiss${EVENT_KEY}`,
    MOUSEDOWN_DISMISS : `mousedown.dismiss${EVENT_KEY}`,
    CLICK_DATA_API    : `click${EVENT_KEY}${DATA_API_KEY}`
}

const Util =  {
    getSelectorFromElement(element) {
        let selector = element.getAttribute('data-target')

        if (!selector || selector === '#') {
            const hrefAttr = element.getAttribute('href')
            selector = hrefAttr && hrefAttr !== '#' ? hrefAttr.trim() : ''
        }

        try {
            return document.querySelector(selector) ? selector : null
        } catch (err) {
            return null
        }
    }
}

function  _jQueryInterface(config, relatedTarget) {
    return this.each(function () {
        let data = $(this).data(DATA_KEY)
        const _config = {
            ...Default,
            ...$(this).data(),
            ...typeof config === 'object' && config ? config : {}
        }

        if (!data) {
            data = new $.fn.modal['Constructor'](this, _config)
            $(this).data(DATA_KEY, data)
        }

        if (typeof config === 'string') {
            if (typeof data[config] === 'undefined') {
                throw new TypeError(`No method named "${config}"`)
            }
            data[config](relatedTarget)
        } else if (_config.show) {
            data.show(relatedTarget)
        }
    })
}

export function initModal() {
    $(document).on('click.bs.modal.data-api', '[data-toggle="modal"]', function (event) {
        let target
        const selector = Util.getSelectorFromElement(this)

        if ( selector ) {
            target = document.querySelector(selector)
        }

        const config = $(target).data(DATA_KEY)
                       ? 'toggle' : {
                ...$(target).data(),
                ...$(this).data()
            }

        if ( this.tagName === 'A' || this.tagName === 'AREA' ) {
            event.preventDefault()
        }

        const $target = $(target).one(EV.SHOW, (showEvent) => {
            if ( showEvent.isDefaultPrevented() ) {
                // Only register focus restorer if modal will actually get shown
                return
            }

            $target.one(EV.HIDDEN, () => {
                if ( $(this).is(':visible') ) {
                    this.focus()
                }
            })
        })

        $.fn.modal['Constructor']._jQueryInterface.call($(target), config, this)
    })
}
