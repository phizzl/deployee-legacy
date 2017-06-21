<?php


namespace Phizzl\Deployee\Descriptor;


class MarkdownDescriptorFormatter implements DescriptorFormatterInterface
{
    public function openDocument()
    {
        return "";
    }

    public function closeDocument()
    {
        return "";
    }

    public function headline($message)
    {
        return "#" . $this->writeln($message);
    }

    public function subheadline($message)
    {
        return "##" . $this->writeln($message);
    }

    public function write($message)
    {
        return $message;
    }

    public function writeln($message)
    {
        return $this->write($message) . $this->newline();
    }

    public function newline(){
        return PHP_EOL;
    }

    public function bold($message)
    {
        return $this->write("**{$message}**");
    }

    public function line()
    {
        return $this->writeln('---');
    }

    public function quote($message)
    {
        return $this->writeln("> $message");
    }
}