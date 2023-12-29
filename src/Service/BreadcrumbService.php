<?php

namespace BoredProgrammers\LaraBreadcrumb\Service;

use BoredProgrammers\LaraBreadcrumb\Exception\BreadcrumbException;
use BoredProgrammers\LaraBreadcrumb\Model\BreadcrumbLink;
use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class BreadcrumbService
{

    private array $accessors;

    private ?string $prefix = null;

    private array $hiddenSegments = [];

    public function __construct()
    {
    }

    public static function generate()
    {
        $instance = app(self::class);

        return $instance->generateInstance();
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

    /****************************************** GETTERS && SETTERS ***********************************************/

    public function setPrefix(string $prefix): static
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function getAccessors(): array
    {
        return $this->accessors;
    }

    public function setAccessors(array $accessors): static
    {
        $this->accessors = $accessors;

        return $this;
    }

    /************************************************ PRIVATE ************************************************/

    private function generateBreadcrumb($segment, $index, $parameters, &$accumulatedUrl)
    {
        if (in_array($segment, $this->hiddenSegments)) {
            return null;
        }

        if (Str::contains($segment, '{')) {
            $parameterName = Str::between($segment, '{', '}');
            $parameterValue = $parameters[$parameterName];

            $accumulatedUrl .= '/' . request()->segment($index + 1);

            $accessor = $this->getAccessors()[$parameterName] ?? null;

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
                $segment = $this->getPrefix() ? ($this->getPrefix() . '.' . $parameterValue) : $parameterValue;
            }
        } else {
            $accumulatedUrl .= '/' . $segment;
        }

        return new BreadcrumbLink($segment, $accumulatedUrl);
    }

    private function generateInstance()
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

}