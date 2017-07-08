<?php

namespace Wizhi\Page\Sections;

use Wizhi\Foundation\ServiceProvider;

class SectionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('sections', function () {

            $data = new SectionData();

            return new SectionBuilder($data);
        });
    }
}
