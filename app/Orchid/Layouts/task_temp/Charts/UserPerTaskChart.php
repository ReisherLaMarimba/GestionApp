<?php

namespace App\Orchid\Layouts\Tasks\Charts;

use Orchid\Screen\Layouts\Chart;

class UserPerTaskChart extends Chart
{
    /**
     * Available options:
     * 'bar', 'line',
     * 'pie', 'percentage'.
     *
     * @var string
     */
    protected $type = 'bar';



    /**
     * Determines whether to display the export button.
     *
     * @var bool
     */
    protected $export = true;
}
