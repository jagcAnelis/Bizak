<?php

namespace DHLParcel\Shipping\Model\Service;

use DHLParcel\Shipping\Model\Core\SingletonAbstract;

class PanelNotificationService extends SingletonAbstract
{
    const GROUP_DEFAULT = 'default';

    protected $messages = [];

    public function addNotice($message, $group = null)
    {
        $group = $group ?: self::GROUP_DEFAULT;
        $this->addMessage($message, 'notice', $group);
    }

    public function addError($message, $group = null)
    {
        $group = $group ?: self::GROUP_DEFAULT;
        $this->addMessage($message, 'error', $group);
    }

    public function addWarning($message, $group = null)
    {
        $group = $group ?: self::GROUP_DEFAULT;
        $this->addMessage($message, 'warning', $group);
    }

    protected function addMessage($message, $type, $group)
    {
        $this->messages = array_merge_recursive($this->messages, [
            $group => [
                $type => [
                    $message,
                ],
            ],
        ]);
    }

    public function getNotices($group = null)
    {
        $group = $group ?: self::GROUP_DEFAULT;
        return $this->getMessages('notice', $group);
    }

    public function getErrors($group = null)
    {
        $group = $group ?: self::GROUP_DEFAULT;
        return $this->getMessages('error', $group);
    }

    public function getWarnings($group = null)
    {
        $group = $group ?: self::GROUP_DEFAULT;
        return $this->getMessages('warning', $group);
    }

    protected function getMessages($type, $group)
    {
        if (!isset($this->messages[$group])) {
            return null;
        } else {
            if (!isset($this->messages[$group][$type])) {
                return null;
            } else {
                if (empty($this->messages[$group][$type])) {
                    return null;
                }
            }
        }

        return $this->messages[$group][$type];
    }
}
