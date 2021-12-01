<?php

namespace AdrianBrown\Matrix\Commands;

use Illuminate\Console\Command;

class MatrixCommand extends Command
{
    public $signature = 'matrix';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
