<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Mailer;

use League\Event\EmitterInterface;
use Mezzio\Template\TemplateRendererInterface;
use Vemid\ProjectOne\Common\Config\ConfigInterface;
use Vemid\ProjectOne\Common\Event\EventList;

/**
 * Class Manager
 * @package Vemid\ProjectOne\Mailer
 */
class MailManager implements MailManagerInterface
{

    /** @var array */
    protected $config = [];

    /** @var \Swift_Transport */
    protected $transport;

    /** @var \Swift_Mailer */
    protected $mailer;

    /** @var \Zend\Expressive\Twig\TwigRenderer */
    protected $templateRenderer;

    /** @var EmitterInterface */
    protected $emitter;

    /**
     * Manager constructor.
     * @param ConfigInterface $config
     * @param TemplateRendererInterface $templateRenderer
     * @param EmitterInterface|null $emitter
     */
    public function __construct(ConfigInterface $config, TemplateRendererInterface $templateRenderer, EmitterInterface $emitter)
    {
        $this->config = $config->get('mailer')->toArray();
        $this->templateRenderer = $templateRenderer;
        $this->emitter = $emitter;
    }

    /**
     * @return Message
     */
    public function createMessage(): Message
    {
        if ($this->emitter) {
            $this->emitter->emit(EventList::EVENT_BEFORE_EMAIL_SENT, $this);
        }

        /** @var Message $message */
        $message = new Message($this, $this->emitter);

        if (isset($this->config['from'])) {
            $message->setFrom($this->config['from']['email'], $this->config['from']['name']);
        }

        if ($this->emitter) {
            $this->emitter->emit(EventList::EVENT_AFTER_EMAIL_SENT, $this, [$message]);
        }

        return $message;
    }

    /**
     * @param $view
     * @param array|null $params
     * @param null $viewsDir
     * @return Message
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function createMessageFromView($view, array $params = null, $viewsDir = null): Message
    {
        $message = $this->createMessage();
        $body = $this->templateRenderer->render($view, $params);
        $message->setBody($body, $message::CONTENT_TYPE_HTML);

        return $message;
    }

    /**
     * @return \Swift_Mailer
     */
    public function getMailer(): \Swift_Mailer
    {
        return new \Swift_Mailer($this->registerTransport());
    }

    /**
     * @param string $email
     * @return string
     */
    public function normalizeEmail($email): string
    {
        if (preg_match('#[^(\x20-\x7F)]+#', $email)) {
            list($user, $domain) = explode('@', $email);
            $email = $user . '@' . $this->punyCode($domain);
        }

        return $email;
    }

    /**
     * @return \Swift_SendmailTransport|\Swift_SmtpTransport
     */
    protected function registerTransport()
    {
        switch ($driver = $this->config['driver']) {
            case 'smtp':
                return $this->registerSmtpTransport();
                break;

            case 'sendmail':
                return $this->registerSendmailTransport();
                break;

            default:
                throw new \InvalidArgumentException(sprintf('Driver-mail "%s" is not supported', $driver));
        }
    }

    /**
     * @return \Swift_SmtpTransport
     */
    protected function registerSmtpTransport(): \Swift_SmtpTransport
    {

        $transport = new \Swift_SmtpTransport();

        if (isset($this->config['host'])) {
            $transport->setHost($this->config['host']);
        }

        if (isset($this->config['port'])) {
            $transport->setPort($this->config['port']);
        }

        if (isset($this->config['encryption'])) {
            $transport->setEncryption($this->config['encryption']);
        }

        if (isset($this->config['username'])) {
            $transport->setUsername($this->normalizeEmail($this->config['username']));
            $transport->setPassword($this->config['password']);
        }

        return $transport;
    }

    /**
     * @return \Swift_SendmailTransport
     */
    protected function registerSendmailTransport(): \Swift_SendmailTransport
    {
        $transport = new \Swift_SendmailTransport();
        $transport->setCommand($this->config['sendmail'] ?? '/usr/sbin/sendmail -bs');

        return $transport;
    }

    /**
     * @param $str
     * @return bool|false|mixed|string
     */
    protected function punyCode($str)
    {
        if (function_exists('idn_to_ascii')) {
            return idn_to_ascii($str);
        }

        return $str;
    }
}
