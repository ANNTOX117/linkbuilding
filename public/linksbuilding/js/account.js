function dialogAlert(message, cancel) {
    if(!$('.lnv-dialog-alert').length) {
        lnv.alert({
            content: message,
            alertBtnText: cancel
        });
    }
}

function dialogConfirm(message, confirm, cancel, redirect) {
    if(!$('.lnv-dialog-confirm').length) {
        lnv.confirm({
            content: message,
            confirmBtnText: confirm,
            cancelBtnText: cancel,
            confirmHandler: function(){
                window.location.href = redirect;
            }
        });
    }
}

function dialogRemove(message, confirm, cancel) {
    if(!$('.lnv-dialog-confirm').length) {
        lnv.confirm({
            content: message,
            confirmBtnText: confirm,
            cancelBtnText: cancel,
            confirmHandler: function(){
                doRemove();
            }
        });
    }
}
