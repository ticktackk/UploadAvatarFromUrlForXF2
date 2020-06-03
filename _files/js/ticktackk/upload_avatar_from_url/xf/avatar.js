var TickTackk = window.TickTackk || {};
TickTackk.UploadAvatarFromUrl = TickTackk.UploadAvatarFromUrl || {};

!function ($, window, document, _undefined)
{
    'use strict';

    XF.AvatarUpload = XF.extend(XF.AvatarUpload, {
        __backup: {
            'ajaxResponse': 'tckUploadAvatarFromUrl__ajaxResponse'
        },

        ajaxResponse: function(e, data)
        {
            this.tckUploadAvatarFromUrl__ajaxResponse(e, data);

            var $form = this.$target,
                $customType = $form.find('.js-tckUploadAvatarFromUrl_customType:checked'),
                $urlField = $form.find('.js-tckUploadAvatarFromUrl_urlField'),
                useCustom = ((parseInt($form.find('input[name="use_custom"]:checked').val()) || 0) === 1);

            if ($customType.length && $customType.val() === 'url' && $urlField.length && useCustom)
            {
                $urlField.val('');
            }
        }
    });
}
(jQuery, window, document);