import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('dropzone:connect', this._onConnect);
        this.element.addEventListener('dropzone:change', this._onChange);
        this.element.addEventListener('dropzone:clear', this._onClear);
    }

    disconnect() {
        this.element.removeEventListener('dropzone:connect', this._onConnect);
        this.element.removeEventListener('dropzone:change', this._onChange);
        this.element.removeEventListener('dropzone:clear', this._onClear);
    }

    _onConnect(event) {
        var form = $(event.target).parents('form');

        var sip = form.find('.saved_icon_preview');
        var dc = form.find('.dropzone-container');
        var dp = form.find('.dropzone-placeholder');

        if (sip.length > 0) {
            sip.appendTo(dc);
    
            sip.css({"width": "160px"});
            dc.css({
                "display": "flex",
                "flex-direction": "column", 
                "align-items": "center"
            });
            dp.css({"flex-grow": "0"});
        }
    }
    
    _onChange(event) {
        var form = $(event.target).parents('form');

        var sip = form.find('.saved_icon_preview');
        var dc = form.find('.dropzone-container');

        if (sip.length > 0) {
            dc.css({
                "flex-direction": "row", 
            });
            
            sip.hide();
        }
    }
    
    _onClear(event) {
        var form = $(event.target).parents('form');
        
        var sip = form.find('.saved_icon_preview');
        var dc = form.find('.dropzone-container');
        
        if (sip.length > 0) {
            sip.appendTo(dc);
            dc.css({
                "flex-direction": "column", 
            });

            sip.show();
        }
    }
}