<?php

namespace AdrianBrown\Matrix;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use AdrianBrown\Matrix\Commands\MatrixCommand;

class MatrixServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('matrix')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_matrix_table')
            ->hasCommand(MatrixCommand::class);
    }
}
