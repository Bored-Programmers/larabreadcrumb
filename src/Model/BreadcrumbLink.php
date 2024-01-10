<?php

namespace BoredProgrammers\LaraBreadcrumb\Model;

class BreadcrumbLink
{

    public string $title;

    public ?string $url;

    public bool $translate;

    public function __construct(string $title, ?string $url, bool $translate)
    {
        $this->title = $title;
        $this->url = $url;
        $this->translate = $translate;
    }

}