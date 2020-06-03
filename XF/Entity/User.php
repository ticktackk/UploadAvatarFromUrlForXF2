<?php

namespace TickTackk\UploadAvatarFromUrl\XF\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure as EntityStructure;
use XF\Phrase;

/**
 * Class User
 * 
 * Extends \XF\Entity\User
 *
 * @package TickTackk\UploadAvatarFromUrl\XF\Entity
 */
class User extends XFCP_User
{
    /**
     * @param Phrase|null $error
     *
     * @return bool
     */
    public function canUploadAvatarFromUrl(Phrase &$error = null) : bool
    {
        if (!$this->user_id)
        {
            return false;
        }

        return $this->hasPermission('avatar', 'tckAllowedUrl');
    }
}