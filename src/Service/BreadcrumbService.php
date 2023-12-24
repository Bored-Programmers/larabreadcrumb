<?php

namespace BoredProgrammers\LaraBreadcrumb\Service;

use BoredProgrammers\LaraBreadcrumb\Exception\BreadcrumbException;
use BoredProgrammers\LaraBreadcrumb\Model\BreadcrumbLink;
use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class BreadcrumbService
{

    protected string $route;

    protected array $accessors;

    protected array $hiddenSegments = [];

    public function __construct(string $route, array $accesors = [])
    {
        $this->route = $route;
        $this->accessors = $accesors;
    }

    public static function createFromRequest(array $accesors = [])
    {
        return new static(request()->path(), $accesors);
    }

    public function generate()
    {
        $route = request()->route();
        $segments = explode('/', trim($route->uri(), '/'));
        $parameters = $route->parameters();

        $breadcrumbs = [];
        $accumulatedUrl = '';

        foreach ($segments as $index => $segment) {
            $breadcrumb = $this->generateBreadcrumb($segment, $index, $parameters, $accumulatedUrl);

            if ($breadcrumb) {
                $breadcrumbs[] = $breadcrumb;
            }
        }

        return $breadcrumbs;
    }

    public function hide(string|array $segments)
    {
        if (is_array($segments)) {
            foreach ($segments as $segment) {
                $this->hide($segment);
            }
        } else {
            $this->hiddenSegments[] = $segments;
        }

        return $this;
    }

    private function generateBreadcrumb($segment, $index, $parameters, &$accumulatedUrl)
    {
        if (in_array($segment, $this->hiddenSegments)) {
            return null;
        }

        if (Str::contains($segment, '{')) {
            $parameterName = Str::between($segment, '{', '}');
            $parameterValue = $parameters[$parameterName];

            $accumulatedUrl .= '/' . request()->segment($index + 1);

            $accessor = $this->accessors[$parameterName] ?? null;

            if ($accessor) {
                try {
                    if ($accessor instanceof Closure) {
                        $segment = $accessor($parameterValue);
                    } else {
                        $segment = $parameterValue->{$accessor};
                    }
                } catch (\Throwable) {
                    throw new BreadcrumbException("Parameter '$parameterName' does not have accessor you defined.");
                }
            } else {
                $segment = $parameterValue;
            }
        } else {
            $accumulatedUrl .= '/' . $segment;
        }

        return new BreadcrumbLink($segment, $accumulatedUrl);
    }

}