<?php


namespace Deployee\Descriptions;


class Markdown
{
    /**
     * @var string
     */
    private $text;

    /**
     * Markdown constructor.
     */
    public function __construct(){
        $this->text = "";
    }

    /**
     * @return $this
     */
    public function newLine(){
        $this->text .= PHP_EOL;
        return $this;
    }

    /**
     * @param $text
     * @return $this
     */
    public function write($text){
        $this->text .= $text;
        return $this;
    }

    /**
     * @param $text
     * @return $this
     */
    public function writeln($text){
        return $this
            ->write($text)
            ->newLine();
    }

    /**
     * @param $text
     * @return $this
     */
    public function headline($text){
        return $this
            ->writeln($text)
            ->writeln(str_pad('', strlen($text), '='));
    }

    /**
     * @param string $text
     * @return $this
     */
    public function subHeadline($text){
        return $this
            ->writeln($text)
            ->writeln(str_pad('', strlen($text), '-'));
    }

    public function lineSeparator(){
        return $this
                ->writeln('* * *');
    }

    /**
     * @param $text
     * @return Markdown
     */
    public function dotList($text){
        return $this->writeln("* {$text}");
    }

    /**
     * @param $link
     * @param null $label
     * @return Markdown
     */
    public function link($link, $label = null){
        $link = $label === null
            ? "<$link>"
            : "[$label]($link)";
        return $this->write($link);
    }

    /**
     * @return string
     */
    public function getContent(){
        return $this->text;
    }
}