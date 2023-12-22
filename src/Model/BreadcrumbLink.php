<?php

namespace BoredProgrammers\LaraBreadcrumb\Model;

class BreadcrumbLink
{

    public string $title;

    public string $url;

    public function __construct(string $title, string $url)
    {
        $this->title = $title;
        $this->url = $url;
    }

}