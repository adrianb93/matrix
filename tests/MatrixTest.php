<?php

use AdrianBrown\Matrix\Matrix;

it('has a string representation of the matrix', function () {
    $table = Matrix::make()->headers(['A', 'B', 'C', 'D'])->rows([
        [1, 1, 1, 1],
        [0, 1, 1, 1],
        [0, 0, 1, 1],
        [0, 0, 0, 1],
    ])->toTable();

    expect($table)->toBe(collect([
        '┌───┬───┬───┬───┬───┐',
        '│   │ A │ B │ C │ D │',
        '├───┼───┼───┼───┼───┤',
        '│ A │ 1 │ 1 │ 1 │ 1 │',
        '│ B │ 0 │ 1 │ 1 │ 1 │',
        '│ C │ 0 │ 0 │ 1 │ 1 │',
        '│ D │ 0 │ 0 │ 0 │ 1 │',
        '└───┴───┴───┴───┴───┘',
        '',
    ])->implode("\n"));
});

it('throws an exception if amount of rows do not match the amount of headers', function () {
    expect(function () {
        Matrix::make()->headers(['A', 'B', 'C', 'D'])->rows([
            [1, 0, 1, 0],
            [0, 1, 0, 1],
        ]);
    })->toThrow(InvalidArgumentException::class, 'Expected 4 rows to match the amount of headers.');
});

it('throws an exception if amount of columns do not match the amount of headers', function () {
    expect(function () {
        Matrix::make()->headers(['A', 'B', 'C', 'D'])->rows([
            [1, 0, 1, 0],
            [0, 1, 0],
            [0, 1],
            [0],
        ]);
    })->toThrow(InvalidArgumentException::class, 'Expected 4 columns in each row to match the amount of headers.');
});

it('throws an exception if any row is not an array', function () {
    expect(function () {
        Matrix::make()->headers(['A', 'B', 'C', 'D'])->rows([
            'string',
            1,
            true,
            null,
        ]);
    })->toThrow(InvalidArgumentException::class, 'Each row must be an array with 4 items.');
});

it('supports associative array headers where the keys are display values', function () {
    $table = Matrix::make()->headers(['ONE' => 1, 'TWO' => 2, 'THREE' => 3, 'FOUR' => 4])->rows([
        [1, 1, 1, 1],
        [0, 1, 1, 1],
        [0, 0, 1, 1],
        [0, 0, 0, 1],
    ])->toTable();

    expect($table)->toBe(collect([
        '┌───────┬─────┬─────┬───────┬──────┐',
        '│       │ ONE │ TWO │ THREE │ FOUR │',
        '├───────┼─────┼─────┼───────┼──────┤',
        '│ ONE   │ 1   │ 1   │ 1     │ 1    │',
        '│ TWO   │ 0   │ 1   │ 1     │ 1    │',
        '│ THREE │ 0   │ 0   │ 1     │ 1    │',
        '│ FOUR  │ 0   │ 0   │ 0     │ 1    │',
        '└───────┴─────┴─────┴───────┴──────┘',
        '',
    ])->implode("\n"));
});

it('accepts headers and rows in the make method', function () {
    $table = Matrix::make(['ONE' => 1, 'TWO' => 2, 'THREE' => 3, 'FOUR' => 4], [
        [1, 1, 1, 1],
        [0, 1, 1, 1],
        [0, 0, 1, 1],
        [0, 0, 0, 1],
    ])->toTable();

    expect($table)->toBe(collect([
        '┌───────┬─────┬─────┬───────┬──────┐',
        '│       │ ONE │ TWO │ THREE │ FOUR │',
        '├───────┼─────┼─────┼───────┼──────┤',
        '│ ONE   │ 1   │ 1   │ 1     │ 1    │',
        '│ TWO   │ 0   │ 1   │ 1     │ 1    │',
        '│ THREE │ 0   │ 0   │ 1     │ 1    │',
        '│ FOUR  │ 0   │ 0   │ 0     │ 1    │',
        '└───────┴─────┴─────┴───────┴──────┘',
        '',
    ])->implode("\n"));
});

it('casts to a string of the table output', function () {
    $table = (string) Matrix::make([1, 2, 3, 4], [
        [1, 1, 1, 1],
        [0, 1, 1, 1],
        [0, 0, 1, 1],
        [0, 0, 0, 1],
    ]);

    expect($table)->toBe(collect([
        '┌───┬───┬───┬───┬───┐',
        '│   │ 1 │ 2 │ 3 │ 4 │',
        '├───┼───┼───┼───┼───┤',
        '│ 1 │ 1 │ 1 │ 1 │ 1 │',
        '│ 2 │ 0 │ 1 │ 1 │ 1 │',
        '│ 3 │ 0 │ 0 │ 1 │ 1 │',
        '│ 4 │ 0 │ 0 │ 0 │ 1 │',
        '└───┴───┴───┴───┴───┘',
        '',
    ])->implode("\n"));
});

it('can run a closure on each column', function () {
    Matrix::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], [
        [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        [2, 4, 6, 8, 10, 12, 14, 16, 18, 20],
        [3, 6, 9, 12, 15, 18, 21, 24, 27, 30],
        [4, 8, 12, 16, 20, 24, 28, 32, 36, 40],
        [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
        [6, 12, 18, 24, 30, 36, 42, 48, 54, 60],
        [7, 14, 21, 28, 35, 42, 49, 56, 63, 70],
        [8, 16, 24, 32, 40, 48, 56, 64, 72, 80],
        [9, 18, 27, 36, 45, 54, 63, 72, 81, 90],
        [10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
    ])->each(function ($value, $x, $y) {
        expect($x * $y)->toBe($value);
    });
});

it('can get the column value by x and y header values', function () {
    $value = Matrix::make([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], [
        [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        [2, 4, 6, 8, 10, 12, 14, 16, 18, 20],
        [3, 6, 9, 12, 15, 18, 21, 24, 27, 30],
        [4, 8, 12, 16, 20, 24, 28, 32, 36, 40],
        [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
        [6, 12, 18, 24, 30, 36, 42, 48, 54, 60],
        [7, 14, 21, 28, 35, 42, 49, 56, 63, 70],
        [8, 16, 24, 32, 40, 48, 56, 64, 72, 80],
        [9, 18, 27, 36, 45, 54, 63, 72, 81, 90],
        [10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
    ])->column(6, 4);

    expect($value)->toBe(24);
});

it('can get the column value by x and y display header values', function () {
    $value = Matrix::make(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5, 'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 9, 'ten' => 10], [
        [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        [2, 4, 6, 8, 10, 12, 14, 16, 18, 20],
        [3, 6, 9, 12, 15, 18, 21, 24, 27, 30],
        [4, 8, 12, 16, 20, 24, 28, 32, 36, 40],
        [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
        [6, 12, 18, 24, 30, 36, 42, 48, 54, 60],
        [7, 14, 21, 28, 35, 42, 49, 56, 63, 70],
        [8, 16, 24, 32, 40, 48, 56, 64, 72, 80],
        [9, 18, 27, 36, 45, 54, 63, 72, 81, 90],
        [10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
    ])->column('six', 'four');

    expect($value)->toBe(24);
});

it('will throw an exception when getting a column value where x heading value does not exist', function () {
    expect(function () {
        Matrix::make(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5, 'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 9, 'ten' => 10], [
            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            [2, 4, 6, 8, 10, 12, 14, 16, 18, 20],
            [3, 6, 9, 12, 15, 18, 21, 24, 27, 30],
            [4, 8, 12, 16, 20, 24, 28, 32, 36, 40],
            [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
            [6, 12, 18, 24, 30, 36, 42, 48, 54, 60],
            [7, 14, 21, 28, 35, 42, 49, 56, 63, 70],
            [8, 16, 24, 32, 40, 48, 56, 64, 72, 80],
            [9, 18, 27, 36, 45, 54, 63, 72, 81, 90],
            [10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
        ])->column('eleven', 'four');
    })->toThrow(InvalidArgumentException::class, 'Unable to find column.');
});

it('will throw an exception when getting a column value where y heading value does not exist', function () {
    expect(function () {
        Matrix::make(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5, 'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 9, 'ten' => 10], [
            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            [2, 4, 6, 8, 10, 12, 14, 16, 18, 20],
            [3, 6, 9, 12, 15, 18, 21, 24, 27, 30],
            [4, 8, 12, 16, 20, 24, 28, 32, 36, 40],
            [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
            [6, 12, 18, 24, 30, 36, 42, 48, 54, 60],
            [7, 14, 21, 28, 35, 42, 49, 56, 63, 70],
            [8, 16, 24, 32, 40, 48, 56, 64, 72, 80],
            [9, 18, 27, 36, 45, 54, 63, 72, 81, 90],
            [10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
        ])->column('six', 'eleven');
    })->toThrow(InvalidArgumentException::class, 'Unable to find row.');
});

it('can map each column value', function () {
    $table = Matrix::make(['a', 'b', 'c', 'd'], [
        [1, 1, 1, 1],
        [0, 1, 1, 1],
        [0, 0, 1, 1],
        [0, 0, 0, 1],
    ])->map(function ($value, $x, $y) {
        return $value ? 'Y' : 'N';
    })->toTable();

    expect($table)->toBe(collect([
        '┌───┬───┬───┬───┬───┐',
        '│   │ a │ b │ c │ d │',
        '├───┼───┼───┼───┼───┤',
        '│ a │ Y │ Y │ Y │ Y │',
        '│ b │ N │ Y │ Y │ Y │',
        '│ c │ N │ N │ Y │ Y │',
        '│ d │ N │ N │ N │ Y │',
        '└───┴───┴───┴───┴───┘',
        '',
    ])->implode("\n"));
});
