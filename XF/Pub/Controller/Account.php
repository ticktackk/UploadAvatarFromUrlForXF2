<?php

namespace TickTackk\UploadAvatarFromUrl\XF\Pub\Controller;

use TickTackk\UploadAvatarFromUrl\XF\Entity\User as ExtendedUserEntity;
use XF\Mvc\Reply\AbstractReply;
use XF\Mvc\Reply\View as ViewReply;
use XF\Mvc\Reply\Redirect as RedirectReply;
use XF\Mvc\Reply\Reroute as RerouteReply;
use XF\Mvc\Reply\Message as MessageReply;
use XF\Mvc\Reply\Exception as ExceptionReply;
use XF\Mvc\Reply\Error as ErrorReply;
use XF\Util\File as FileUtil;

/**
 * Class Account
 * 
 * Extends \XF\Pub\Controller\Account
 *
 * @package TickTackk\UploadAvatarFromUrl\XF\Pub\Controller
 */
class Account extends XFCP_Account
{
    /**
     * @return ErrorReply|RedirectReply|ViewReply
     *
     * @throws ExceptionReply
     */
    public function actionAvatar()
    {
        if ($this->isPost())
        {
            $input = $this->filter([
                'delete_avatar' => 'bool',
                'use_custom' => 'bool',
                'custom_type' => 'str',
                'url' => 'str'
            ]);

            if (!$input['delete_avatar'] && $input['use_custom'] && $input['custom_type'] === 'url')
            {
                /** @var ExtendedUserEntity $visitor */
                $visitor = \XF::visitor();

                if (!$visitor->canUploadAvatarFromUrl($error))
                {
                    throw $this->exception($this->noPermission($error));
                }

                /** @var \XF\Service\User\Avatar $avatarService */
                $avatarService = $this->service('XF:User\Avatar', $visitor);

                if (\strlen($input['url']))
                {
                    $validator = $this->app()->validator('Url');
                    if (!$validator->isValid($input['url']))
                    {
                        throw $this->exception($this->error(\XF::phrase('please_enter_valid_url')));
                    }

                    $tempFile = FileUtil::getTempFile();
                    $response = $this->app()->http()->reader()->getUntrusted($input['url'], [], $tempFile);
                    if (!$response || $response->getStatusCode() !== 200)
                    {
                        throw $this->exception($this->error(\XF::phrase('new_avatar_could_not_be_processed')));
                    }
                    
                    if (!$avatarService->setImage($tempFile))
                    {
                        throw $this->exception($this->error($avatarService->getError()));
                    }

                    if (!$avatarService->updateAvatar())
                    {
                        throw $this->exception($this->error(\XF::phrase('new_avatar_could_not_be_processed')));
                    }

                    if ($this->filter('_xfWithData', 'bool'))
                    {
                        return $this->view('XF:Account\AvatarUpdate', '');
                    }
                    else
                    {
                        return $this->redirect($this->buildLink('account/avatar'));
                    }
                }
                else if ($visitor->avatar_date)
                {
                    // recrop existing avatar
                    $cropX = $this->filter('avatar_crop_x', 'uint');
                    $cropY = $this->filter('avatar_crop_y', 'uint');
                    if ($cropX != $visitor->Profile->avatar_crop_x || $cropY != $visitor->Profile->avatar_crop_y)
                    {
                        $avatarService->setImageFromExisting();
                        $avatarService->setCrop($cropX, $cropY);
                        if (!$avatarService->updateAvatar())
                        {
                            throw $this->exception($this->error(\XF::phrase('new_avatar_could_not_be_processed')));
                        }
                    }
                    else
                    {
                        $avatarService->removeGravatar();
                    }
                }
            }
        }

        return parent::actionAvatar();
    }
}