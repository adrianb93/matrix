<?php

namespace AdrianBrown\Matrix;

use AdrianBrown\Matrix\Commands\MatrixCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
