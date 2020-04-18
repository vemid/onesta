<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Mailer;

/**
 * Interface ManagerInterface
 * @package Vemid\ProjectOne\Common\Mailer
 */
interface MailManagerInterface
{

    /**
     * @return Message
     */
    public function createMessage(): Message;

    /**
     * @param $view
     * @param array|null $params
     * @param null $viewsDir
     * @return Message
     */
    public function createMessageFromView($view, array $params = null, $viewsDir = null): Message;

    /**
     * @return \Swift_Mailer
     */
    public function getMailer(): \Swift_Mailer;

    /**
     * Normalize IDN domains.
     *
     * @param string $email
     * @return string
     */
    public function normalizeEmail($email): string;

}
