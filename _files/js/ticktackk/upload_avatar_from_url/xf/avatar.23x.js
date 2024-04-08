var TickTackk = window.TickTackk || {};
TickTackk.UploadAvatarFromUrl = TickTackk.UploadAvatarFromUrl || {};

;((window, document) =>
{
    'use strict'

    XF.AvatarUpload = XF.extend(XF.AvatarUpload, {
        __backup: {
            'ajaxResponse': 'tckUploadAvatarFromUrl__ajaxResponse'
        },

        ajaxResponse: function(e, data)
        {
            this.tckUploadAvatarFromUrl__ajaxResponse(e, data);

            const form = this.target
            const customType = form.querySelector('.js-tckUploadAvatarFromUrl_customType:checked')
            const urlField = form.querySelector('.js-tckUploadAvatarFromUrl_urlField')
            const useCustom = (form.querySelector('input[name="use_custom"]:checked').value == 1)

            if ((customType !== undefined) && (customType.value === 'url') && (urlField !== undefined) && useCustom)
            {
                urlField.value = '';
            }
        }
    })
})(window, document)