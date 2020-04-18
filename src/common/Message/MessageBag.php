<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Message;

use Nette\Forms\Form;
use Vemid\ProjectOne\Common\Session\FlashSession;

class MessageBag
{
    /** @var MessageInterface */
    private $message;

    /** @var FlashSession */
    private $flashSession;

    /**
     * MessageBag constructor.
     * @param MessageInterface $message
     * @param FlashSession $flashSession
     */
    public function __construct(MessageInterface $message, FlashSession $flashSession)
    {
        $this->message = $message;
        $this->flashSession = $flashSession;
    }

    /**
     * @param string $message
     * @param null $field
     * @param null $type
     */
    public function pushFlashMessage(string $message, $field = null, $type = Builder::DANGER): void
    {
        $this->message->pushMessage(
            new Builder($message, $field, $type)
        );
    }

    /**
     * @param Form $form
     */
    public function pushFormValidationMessages(Form $form): void
    {
        foreach ($form->getErrors() as $message) {
            $this->pushFlashMessage($message, null, Builder::DANGER);
        }
    }

    /**
     * @param string $context
     * @param string|array $body
     */
    final public function emptyBag(string $context, &$body)
    {
        if ($context === 'json') {
            if ($this->message->hasMessages(Builder::DANGER)) {
                $body['error'] = true;
                $body['messages'] = $this->message->toArray(Builder::DANGER);
            } else if ($this->message->hasMessages(Builder::WARNING)) {
                $body['error'] = true;
                $body['messages'] = $this->message->toArray(Builder::WARNING);
            } else if ($this->message->hasMessages(Builder::INFO)) {
                $body['error'] = false;
                $body['messages'] = $this->message->toArray(Builder::INFO);
            } else if ($this->message->hasMessages(Builder::SUCCESS)) {
                $body['error'] = false;
                $body['messages'] = $this->message->toArray(Builder::SUCCESS);
            }

            return;
        }

        foreach ($this->message->getMessages() as $message) {
            switch ($message->getType()) {
                case Builder::SUCCESS:
                    $this->flashSession->success($message->getMessage());
                    break;
                case Builder::DANGER:
                    $this->flashSession->error($message->getMessage());
                    break;
                case Builder::WARNING:
                    $this->flashSession->warning($message->getMessage());
                    break;
                default:
                    $this->flashSession->warning($message->getMessage());
            }
        }
    }
}
