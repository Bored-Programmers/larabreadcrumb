<?php

namespace BoredProgrammers\LaraBreadcrumb\Service;

use BoredProgrammers\LaraBreadcrumb\Exception\BreadcrumbException;
use BoredProgrammers\LaraBreadcrumb\Model\BreadcrumbLink;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class BreadcrumbService
{

    private array $accessors = [];

    private ?string $prefix = null;

    private array $hiddenSegments = [];

    private array $disabledSegments = [];

    public static function create(): static
    {
        return new static();
    }

    public static function update(): static
    {
        return app(self::class);
    }

    /** @return BreadcrumbLink[] */
    public static function generate(): array
    {
        return app(self::class)->generateInstance();
    }

    public function setPrefix(string $prefix): static
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function setAccessors(array $accessors): static
    {
        $this->accessors = $accessors;

        return $this;
    }

    public function addAccessor($accessor): static
    {
        $this->accessors[] = $accessor;

        return $this;
    }

    public function hide(string|array $segments)
    {
        $this->addSegments($segments, $this->hiddenSegments);

        return $this;
    }

    public function disable(string|array $segments)
    {
        $this->addSegments($segments, $this->disabledSegments);

        return $this;
    }

    /************************************************ PRIVATE ************************************************/

    private function addSegments(string|array $segments, array &$targetArray): void
    {
        if (is_array($segments)) {
            foreach ($segments as $segment) {
                $this->addSegments($segment, $targetArray);
            }
        } else {
            $targetArray[] = $segments;
        }
    }

    private function generateBreadcrumbLink($segment, $index, $parameters, &$accumulatedUrl): ?BreadcrumbLink
    {
        if (in_array($segment, $this->hiddenSegments)) {
            return null;
        }

        $disableSegment = in_array($segment, $this->disabledSegments);
        $segment = $this->processSegment($segment, $index, $parameters, $accumulatedUrl);

        return new BreadcrumbLink($segment, $disableSegment ? null : $accumulatedUrl);
    }

    private function processSegment($segment, $index, $parameters, &$accumulatedUrl)
    {
        if (Str::contains($segment, '{')) {
            return $this->processParameterSegment($segment, $index, $parameters, $accumulatedUrl);
        }

        $accumulatedUrl .= '/' . $segment;

        return $this->getPrefix() ? ($this->getPrefix() . '.' . $segment) : $segment;
    }

    /**
     * @throws BreadcrumbException
     */
    private function processParameterSegment($segment, $index, $parameters, &$accumulatedUrl)
    {
        $parameterName = Str::between($segment, '{', '}');
        $parameterValue = $parameters[$parameterName];

        $accumulatedUrl .= '/' . request()->segment($index + 1);
        $accessor = $this->accessors[$parameterName] ?? null;

        if ($accessor) {
            return $this->processAccessor($accessor, $parameterValue, $parameterName);
        }

        return $parameterValue instanceof Model ? $parameterValue->getKey()
            : $this->getPrefix() . '.' . $parameterValue;
    }

    private function processAccessor($accessor, $parameterValue, $parameterName)
    {
        try {
            return $accessor instanceof Closure ? $accessor($parameterValue) : $parameterValue->{$accessor};
        } catch (\Throwable $e) {
            throw new BreadcrumbException(
                "Parameter '$parameterName' does not have accessor you defined. {$e->getMessage()}",
            );
        }
    }

    /**
     * @return BreadcrumbLink[]
     */
    private function generateInstance(): array
    {
        $route = request()->route();
        $segments = explode('/', trim($route->uri(), '/'));
        $parameters = $route->parameters();

        $breadcrumbs = [];
        $accumulatedUrl = '';

        foreach ($segments as $index => $segment) {
            $breadcrumb = $this->generateBreadcrumbLink($segment, $index, $parameters, $accumulatedUrl);

            if ($breadcrumb) {
                $breadcrumbs[] = $breadcrumb;
            }
        }

        return $breadcrumbs;
    }

}