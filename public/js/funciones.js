var AlliotecCattle = function() {
    return {
        notification: function(message, title, type) {
            toastr.options = {
                closeButton: true,
                newestOnTop: true,
                positionClass: 'toast-top-right',
                preventDuplicates: true,
                timeOut: '5000'
            };
            if (type == 'error') {
                toastr.error(message, title);
            } else if (type == 'success') {
                toastr.success(message, title);
            } else if (type == 'info') {
                toastr.info(message, title);
            } else if (type == 'warning') {
                toastr.warning(message, title);
            }
        },
    }
}();