<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Session;

use Mezzio\Session\SessionInterface;

/**
 * Class FlashSession
 * @package Vemid\ProjectOne\Common\Session
 */
class FlashSession {

    const INFO    = 'i';
    const SUCCESS = 's';
    const WARNING = 'w';
    const ERROR   = 'e';

    private $defaultType = self::INFO;

    protected $msgTypes = [
        self::ERROR   => 'error',
        self::WARNING => 'warning',
        self::SUCCESS => 'success',
        self::INFO    => 'info',
    ];

    protected $msgWrapper;
    protected $msgBefore;
    protected $msgAfter;
    protected $closeBtn;
    protected $stickyCssClass = 'sticky';
    protected $msgCssClass = 'alert dismissable';
    protected $cssClassMap = [
        self::INFO    => 'alert-info',
        self::SUCCESS => 'alert-success',
        self::WARNING => 'alert-warning',
        self::ERROR   => 'alert-danger',
    ];

    /** @var string */
    protected $redirectUrl;

    /** @var string */
    protected $msgId;

    /** @var SessionInterface */
    protected $session;

    /**
     * FlashSession constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->msgId = sha1(uniqid());
        $this->session = $session;

        if (!$this->session->has('flashMessages')) {
            $this->session->set('flashMessages', []);
        }
    }

    /**
     * @param $message
     * @param null $redirectUrl
     * @param bool $sticky
     * @return $this|bool
     */
    public function info($message, $redirectUrl = null, $sticky = false)
    {
        return $this->add($message, self::INFO, $redirectUrl, $sticky);
    }

    /**
     * @param $message
     * @param null $redirectUrl
     * @param bool $sticky
     * @return $this|bool
     */
    public function success($message, $redirectUrl = null, $sticky = false)
    {
        return $this->add($message, self::SUCCESS, $redirectUrl, $sticky);
    }

    /**
     * @param $message
     * @param null $redirectUrl
     * @param bool $sticky
     * @return $this|bool
     */
    public function warning($message, $redirectUrl = null, $sticky = false)
    {
        return $this->add($message, self::WARNING, $redirectUrl, $sticky);
    }

    /**
     * @param $message
     * @param null $redirectUrl
     * @param bool $sticky
     * @return $this|bool
     */
    public function error($message, $redirectUrl = null, $sticky = false)
    {
        return $this->add($message, self::ERROR, $redirectUrl, $sticky);
    }

    /**
     * @param bool $message
     * @param null $redirectUrl
     * @param string $type
     * @return object
     */
    public function sticky($message = true, $redirectUrl = null, $type = '')
    {
        return $this->add($message, $type ?: $this->defaultType, $redirectUrl, true);
    }

    /**
     * @param $message
     * @param string $type
     * @param null $redirectUrl
     * @param bool $sticky
     * @return $this|bool
     */
    public function add($message, $type = '', $redirectUrl = null, $sticky = false)
    {
        if (!$type || !array_key_exists($type, $this->msgTypes)) {
            $type = $this->defaultType;
        }

        if (!isset($message[0])) {
            return false;
        }

        if (strlen(trim($type)) > 1) {
            $type = strtolower($type[0]);
        }

        if (!array_key_exists($type, $this->session->get('flashMessages'))) {
            $this->session->set('flashMessages', [$type => []]);
        }

        $this->session->set('flashMessages', [$type => [
            ['sticky' => $sticky, 'message' => $message]
        ]]);

        if ($redirectUrl === null) {
            $this->redirectUrl = $redirectUrl;
        }

        $this->doRedirect();

        return $this;
    }

    /**
     * @param null $types
     * @param bool $print
     * @return bool|string
     */
    public function display($types = null, $print = true)
    {
        if (!$this->session->has('flashMessages')) {
            return false;
        }

        $output = '';

        if (empty($types)) {
            $types = array_keys($this->msgTypes);
        } elseif (is_array($types) && !empty($types)) {
            $theTypes = $types;
            $types = [];
            foreach($theTypes as $type) {
                $types[] = strtolower($type[0]);
            }
        } else {
            $types = [strtolower($types[0])];
        }

        $flashSession = $this->session->get('flashMessages');
        foreach ($types as $type) {
            if (!isset($flashSession[$type])) {
                continue;
            }

            if (empty($flashSession[$type]) ) {
                continue;
            }

            foreach($flashSession[$type] as $msgData ) {
                $output .= $this->formatMessage($msgData, $type);
            }

            $this->clear($type);
        }

        if (!$print) {
            return $output;
        }

        echo $output;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return empty($this->session->get('flashMessages')[self::ERROR]) ? false : true;
    }

    /**
     * @param null $type
     * @return bool
     */
    public function hasMessages($type = null): bool
    {
        if ($type === null) {
            if (!empty($this->session->get('flashMessages')[$type])) {
                return $this->session->get('flashMessages')[$type];
            }
        } else {
            foreach (array_keys($this->msgTypes) as $type) {
                if (isset($this->session->get('flashMessages')[$type]) && !empty($this->session->get('flashMessages')[$type])) {
                    return $this->session->get('flashMessages')[$type];
                }
            }
        }

        return false;
    }

    /**
     * @param $msgDataArray
     * @param $type
     * @return string
     */
    protected function formatMessage($msgDataArray, $type): string
    {
        $cssClass = sprintf('%s %s',$this->msgCssClass,$this->cssClassMap[$type]);
        $msgBefore = $this->msgBefore;

        if ($msgDataArray['sticky']) {
            $cssClass .= ' ' . $this->stickyCssClass;
        } else {
            $msgBefore = ($this->closeBtn ?? $this->getDefaultCloseButton()) . $msgBefore;
        }

        $formattedMessage = $msgBefore . $msgDataArray['message'] . $this->msgAfter;

        return sprintf(
            $this->msgWrapper ?? $this->getDefaultMessageWrapper(),
            $cssClass,
            $formattedMessage
        );
    }

    /**
     * @return $this
     */
    protected function doRedirect(): self
    {
        if ($this->redirectUrl) {
            header('Location: ' . $this->redirectUrl);
            exit();
        }

        return $this;
    }

    /**
     * @param array $types
     * @return $this
     */
    protected function clear($types = []): self
    {
        if ((is_array($types) && empty($types)) || $types === null || !$types) {
            $this->session->unset('flashMessages');
        } elseif (!is_array($types)) {
            $types = [$types];
        }

        $flashSession = $this->session->get('flashMessages');
        $this->session->unset('flashMessages');

        foreach ($types as $type) {
            unset($flashSession[$type]);
        }

        $this->session->set('flashMessages', $flashSession);

        return $this;
    }


    /**
     * @param string $msgWrapper
     * @return $this
     */
    public function setMessageWrapper($msgWrapper = ''): self
    {
        $this->msgWrapper = $msgWrapper;

        return $this;
    }

    /**
     * @param string $msgBefore
     * @return $this
     */
    public function setMsgBefore($msgBefore = ''): self
    {
        $this->msgBefore = $msgBefore;

        return $this;
    }

    /**
     * @param string $msgAfter
     * @return $this
     */
    public function setMessageAfter($msgAfter = ''): self
    {
        $this->msgAfter = $msgAfter;

        return $this;
    }

    /**
     * @param string $closeBtn
     * @return $this
     */
    public function setCloseBtn($closeBtn = ''): self
    {
        $this->closeBtn = $closeBtn;

        return $this;
    }

    /**
     * @param string $stickyCssClass
     * @return $this
     */
    public function setStickyCssClass($stickyCssClass = ''): self
    {
        $this->stickyCssClass = $stickyCssClass;

        return $this;
    }

    /**
     * @param string $msgCssClass
     * @return $this
     */
    public function setMsgCssClass($msgCssClass = ''): self
    {
        $this->msgCssClass = $msgCssClass;

        return $this;
    }

    /**
     * @param $msgType
     * @param null $cssClass
     * @return $this
     */
    public function setCssClassMap($msgType, $cssClass): self
    {
        if (!is_array($msgType)) {
            $msgType = [$msgType => $cssClass];
        }

        foreach ($msgType as $type) {
            $this->cssClassMap[$type] = $cssClass;
        }

        return $this;
    }

    /**
     * @return string
     */
    private function getDefaultMessageWrapper(): string
    {
        return "<div class='%s'>%s</div>\n";
    }

    /**
     * @return string
     */
    private function getDefaultCloseButton(): string
    {
        return <<<STR
<button type="button" class="close" 
    data-dismiss="alert" 
    aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
STR;
    }
}
