<?php


namespace Phizzl\Deployee\Filesystem;


class Finder extends \Symfony\Component\Finder\Finder
{
    /**
     * @return null|\SplFileInfo
     */
    public function first(){
        $return = null;
        foreach($this as $fileInfo){
            $return = $fileInfo;
            break;
        }

        return $return;
    }
}