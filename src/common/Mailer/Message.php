<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Mailer;

use League\Event\EmitterInterface;
use Vemid\ProjectOne\Common\Event\EventList;

/**
 * Class Message
 * @package Vemid\ProjectOne\Common\Mailer
 */
class Message
{

    /**
     * content type of PLAIN text.
     */
    const CONTENT_TYPE_PLAIN = 'text/plain';

    /**
     * content type HTML text.
     */
    const CONTENT_TYPE_HTML = 'text/html';

    /**
     * @var MailManager
     */
    protected $manager;

    /**
     * @var \Swift_Message
     */
    protected $swiftMessage;

    /**
     * @var EmitterInterface|null
     *
     */
    protected $emitter;

    /**
     * An array of email which failed send to recipients.
     *
     * @var array
     */
    protected $failedRecipients = [];

    /**
     * Message constructor.
     * @param MailManagerInterface $manager
     * @param EmitterInterface|null $emitter
     */
    public function __construct(MailManagerInterface $manager, EmitterInterface $emitter = null)
    {
        $this->manager = $manager;
        $this->emitter = $emitter;
    }

    /**
     * @param $email
     * @param null $name
     * @return $this
     */
    public function setFrom($email, $name = null): Message
    {
        $email = $this->normalizeEmail($email);
        $this->getSwiftMessage()->setFrom($email, $name);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->getSwiftMessage()->getFrom();
    }

    /**
     * @param $email
     * @param null $name
     * @return $this
     */
    public function setReplyTo($email, $name = null): Message
    {
        $email = $this->normalizeEmail($email);
        $this->getSwiftMessage()->setReplyTo($email, $name);

        return $this;
    }

    /**
     * @return string
     */
    public function getReplyTo(): string
    {
        return $this->getSwiftMessage()->getReplyTo();
    }

    /**
     * @param $email
     * @param null $name
     * @return $this
     */
    public function setTo($email, $name = null): Message
    {
        $email = $this->normalizeEmail($email);
        $this->getSwiftMessage()->setTo($email, $name);

        return $this;
    }

    /**
     * @return array
     */
    public function getTo(): array
    {
        return $this->getSwiftMessage()->getTo();
    }

    /**
     * @param $email
     * @param null $name
     * @return $this
     */
    public function setCc($email, $name = null): Message
    {
        $email = $this->normalizeEmail($email);
        $this->getSwiftMessage()->setCc($email, $name);

        return $this;
    }

    /**
     * @return array
     */
    public function getCc(): array
    {
        return $this->getSwiftMessage()->getCc();
    }

    /**
     * @param $email
     * @param null $name
     * @return $this
     */
    public function setBcc($email, $name = null): Message
    {
        $email = $this->normalizeEmail($email);
        $this->getSwiftMessage()->setBcc($email, $name);

        return $this;
    }

    /**
     * @return array
     */
    public function getBcc(): array
    {
        return $this->getSwiftMessage()->getBcc();
    }

    /**
     * @param $email
     * @param null $name
     * @return $this
     */
    public function setSender($email, $name = null): Message
    {
        $email = $this->normalizeEmail($email);
        $this->getSwiftMessage()->setSender($email, $name);

        return $this;
    }

    /**
     * @return string
     */
    public function getSender(): string
    {
        return $this->getSwiftMessage()->getSender();
    }

    /**
     * Set the subject of this message.
     *
     * @param string $subject
     * @return $this
     * @see \Swift_Message::setSubject()
     */
    public function setSubject($subject): Message
    {
        $this->getSwiftMessage()->setSubject($subject);

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->getSwiftMessage()->getSubject();
    }

    /**
     * @param $content
     * @param string $contentType
     * @param null $charset
     * @return $this
     */
    public function setBody($content, $contentType = self::CONTENT_TYPE_HTML, $charset = null): Message
    {
        $this->getSwiftMessage()->setBody($content, $contentType, $charset);

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->getSwiftMessage()->getBody();
    }

    /**
     * @param $contentType
     * @return $this
     */
    public function setContentType($contentType): Message
    {
        $this->getSwiftMessage()->setContentType($contentType);

        return $this;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->getSwiftMessage()->getContentType();
    }

    /**
     * @param $charset
     * @return $this
     */
    public function setCharset($charset): Message
    {
        $this->getSwiftMessage()->setCharset($charset);

        return $this;
    }

    /**
     * @return string
     */
    public function getCharset(): string
    {
        return $this->getSwiftMessage()->getCharset();
    }

    /**
     * @param $priority
     * @return $this
     */
    public function setPriority($priority): Message
    {
        $this->getSwiftMessage()->setPriority($priority);

        return $this;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->getSwiftMessage()->getPriority();
    }

    /**
     * @param $email
     * @return $this
     */
    public function setReadReceiptTo($email): Message
    {
        $email = $this->normalizeEmail($email);
        $this->getSwiftMessage()->setReadReceiptTo($email);

        return $this;
    }

    /**
     * @return array
     */
    public function getFailedRecipients(): array
    {
        return $this->failedRecipients;
    }

    /**
     * @return string
     */
    public function getReadReceiptTo(): string
    {
        return $this->getSwiftMessage()->getReadReceiptTo();
    }

    /**
     * @param $email
     * @return $this
     */
    public function setReturnPath($email): Message
    {
        $this->getSwiftMessage()->setReturnPath($email);

        return $this;
    }

    /**
     * @return string
     */
    public function getReturnPath(): string
    {
        return $this->getSwiftMessage()->getReturnPath();
    }

    /**
     * @param $format
     * @return $this
     */
    public function setFormat($format): Message
    {
        $this->getSwiftMessage()->setFormat($format);

        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->getSwiftMessage()->getFormat();
    }

    /**
     * @param $file
     * @param array $options
     * @return $this
     */
    public function setAttachment($file, $options = []): Message
    {
        $attachment = $this->createAttachmentFromPath($file);

        return $this->prepareAttachment($attachment, $options);
    }

    /**
     * @param $data
     * @param $name
     * @param array $options
     * @return $this
     */
    public function setAttachmentData($data, $name, $options = []): Message
    {
        $attachment = $this->createAttachmentFromData($data, $name);

        return $this->prepareAttachment($attachment, $options);
    }

    /**
     * @param $file
     * @return string
     */
    public function setEmbedFile($file)
    {
        $embed = $this->createEmbedFromPath($file);

        return $this->getSwiftMessage()->embed($embed);
    }

    /**
     * @param $data
     * @param $name
     * @return string
     */
    public function setEmbedData($data, $name): string
    {
        $embed = $this->createEmbedFromData($data, $name);

        return $this->getSwiftMessage()->embed($embed);
    }

    /**
     * @return object|\Swift_Message
     */
    public function getSwiftMessage()
    {
        if (!$this->swiftMessage) {
            $this->swiftMessage = $this->manager->getMailer()->createMessage();
        }

        return $this->swiftMessage;
    }

    /**
     * @return bool|int
     */
    public function send()
    {
        if ($this->emitter) {
            $result = $this->emitter->emit(EventList::EVENT_BEFORE_EMAIL_SENT, $this);
        } else {
            $result = true;
        }

        if ($result !== false) {
            $this->failedRecipients = [];
            $mailer = $this->manager->getMailer();

            $count = $mailer->send($this->getSwiftMessage(), $this->failedRecipients);

            if ($this->emitter) {
                $this->emitter->emit(EventList::EVENT_AFTER_EMAIL_SENT, $this, [$count, $this->failedRecipients]);
            }

            return $count;
        }

        return false;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->swiftMessage->toString();
    }

    /**
     * @param \Swift_Mime_Attachment $attachment
     * @param array $options
     * @return $this
     */
    protected function prepareAttachment(\Swift_Mime_Attachment $attachment, array $options = array())
    {
        if (isset($options['mime'])) {
            $attachment->setContentType($options['mime']);
        }

        if (isset($options['as'])) {
            $attachment->setFilename($options['as']);
        }

        $this->getSwiftMessage()->attach($attachment);

        return $this;
    }

    /**
     * @param $file
     * @return \Swift_Attachment
     */
    protected function createAttachmentFromPath($file): \Swift_Attachment
    {
        return \Swift_Attachment::fromPath($file);
    }

    /**
     * @param $data
     * @param $name
     * @return \Swift_Attachment
     */
    protected function createAttachmentFromData($data, $name): \Swift_Attachment
    {
        return new \Swift_Attachment($data, $name);
    }

    /**
     * @param $file
     * @return \Swift_Image
     */
    protected function createEmbedFromPath($file): \Swift_Image
    {
        return \Swift_Image::fromPath($file);
    }

    /**
     * @param $data
     * @param null $name
     * @return \Swift_Image
     */
    protected function createEmbedFromData($data, $name = null): \Swift_Image
    {
        return new \Swift_Image($data, $name);
    }

    /**
     * @param $email
     * @return array|string
     */
    protected function normalizeEmail($email)
    {
        if (is_array($email)) {
            $normalizedEmails = [];
            foreach ($email as $k => $v) {
                if (is_int($k)) {
                    $normalizedEmails[$k] = $this->manager->normalizeEmail($v);
                } else {
                    $k = $this->manager->normalizeEmail($k);
                    $normalizedEmails[$k] = $v;
                }
            }

            return $normalizedEmails;
        }

        return $this->manager->normalizeEmail($email);
    }
}
