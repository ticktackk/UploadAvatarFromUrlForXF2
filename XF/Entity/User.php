<?php

namespace TickTackk\UploadAvatarFromUrl\XF\Entity;

use XF\Phrase;

class User extends XFCP_User
{
    /**
     * @param Phrase|null $error
     *
     * @return bool
     */
    public function canUploadAvatarFromUrl(/** @noinspection PhpUnusedParameterInspection */ Phrase &$error = null) : bool
    {
        if (!$this->user_id)
        {
            return false;
        }

        return $this->hasPermission('avatar', 'tckAllowedUrl');
    }
}