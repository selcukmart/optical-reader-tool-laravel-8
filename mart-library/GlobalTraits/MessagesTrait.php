<?php
/**
 * @author selcukmart
 * 31.10.2021
 * 11:22
 */

namespace GlobalTraits;

trait MessagesTrait
{

    private
        $message = '';

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message .= $message . PHP_EOL;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

}
