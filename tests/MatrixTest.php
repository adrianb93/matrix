<?php

use AdrianBrown\Matrix\Matrix;

it('has a string representation of a square matrix with row and column headers', function () {
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

it('throws an exception if amount of rows do not match the amount of row headers', function () {
    expect(function () {
        Matrix::make()->headers(['A', 'B', 'C', 'D'])->rows([
            [1, 0, 1, 0],
            [0, 1, 0, 1],
        ]);
    })->toThrow(InvalidArgumentException::class, 'Expected 4 rows to match the amount of headers.');
});

it('throws an exception if amount of columns do not match the amount of column headers', function () {
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
    })->toThrow(InvalidArgumentException::class, 'Each row must be an array of 4 items.');
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

it('supports associative array row headers where the keys are display values', function () {
    $table = Matrix::make()->rowHeaders(['ONE' => 1, 'TWO' => 2, 'THREE' => 3, 'FOUR' => 4])->rows([
        [1, 1, 1, 1],
        [0, 1, 1, 1],
        [0, 0, 1, 1],
        [0, 0, 0, 1],
    ])->toTable();

    expect($table)->toBe(collect([
        '┌───────┬───┬───┬───┬───┐',
        '│ ONE   │ 1 │ 1 │ 1 │ 1 │',
        '│ TWO   │ 0 │ 1 │ 1 │ 1 │',
        '│ THREE │ 0 │ 0 │ 1 │ 1 │',
        '│ FOUR  │ 0 │ 0 │ 0 │ 1 │',
        '└───────┴───┴───┴───┴───┘',
        '',
    ])->implode("\n"));
});

it('supports associative array column headers where the keys are display values', function () {
    $table = Matrix::make()->columnHeaders(['ONE' => 1, 'TWO' => 2, 'THREE' => 3, 'FOUR' => 4])->rows([
        [1, 1, 1, 1],
        [0, 1, 1, 1],
        [0, 0, 1, 1],
        [0, 0, 0, 1],
    ])->toTable();

    expect($table)->toBe(collect([
        '┌─────┬─────┬───────┬──────┐',
        '│ ONE │ TWO │ THREE │ FOUR │',
        '├─────┼─────┼───────┼──────┤',
        '│ 1   │ 1   │ 1     │ 1    │',
        '│ 0   │ 1   │ 1     │ 1    │',
        '│ 0   │ 0   │ 1     │ 1    │',
        '│ 0   │ 0   │ 0     │ 1    │',
        '└─────┴─────┴───────┴──────┘',
        '',
    ])->implode("\n"));
});

it('casts to a string of the table output with row and column headers', function () {
    $table = (string) Matrix::make()->headers([1, 2, 3, 4])->rows([
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

it('casts to a string of the table output with row headers', function () {
    $table = (string) Matrix::make()->rowHeaders(['A', 'B', 'C', 'D'])->rows([
        [1, 1, 1, 1],
        [0, 1, 1, 1],
        [0, 0, 1, 1],
        [0, 0, 0, 1],
    ]);

    expect($table)->toBe(collect([
        '┌───┬───┬───┬───┬───┐',
        '│ A │ 1 │ 1 │ 1 │ 1 │',
        '│ B │ 0 │ 1 │ 1 │ 1 │',
        '│ C │ 0 │ 0 │ 1 │ 1 │',
        '│ D │ 0 │ 0 │ 0 │ 1 │',
        '└───┴───┴───┴───┴───┘',
        '',
    ])->implode("\n"));
});

it('casts to a string of the table output with column headers', function () {
    $table = (string) Matrix::make()->columnHeaders(['A', 'B', 'C', 'D'])->rows([
        [1, 1, 1, 1],
        [0, 1, 1, 1],
        [0, 0, 1, 1],
        [0, 0, 0, 1],
    ]);

    expect($table)->toBe(collect([
        '┌───┬───┬───┬───┐',
        '│ A │ B │ C │ D │',
        '├───┼───┼───┼───┤',
        '│ 1 │ 1 │ 1 │ 1 │',
        '│ 0 │ 1 │ 1 │ 1 │',
        '│ 0 │ 0 │ 1 │ 1 │',
        '│ 0 │ 0 │ 0 │ 1 │',
        '└───┴───┴───┴───┘',
        '',
    ])->implode("\n"));
});

it('can run a closure on each column for square matrices', function () {
    Matrix::make()->headers([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->rows([
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
    ])->each(function ($value, $a, $b) {
        expect($a * $b)->toBe($value);
    });
});

it('can run a closure on each column for wide matrices', function () {
    Matrix::make()->rowHeaders([1, 2, 3, 4, 5])->columnHeaders([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->rows([
        [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        [2, 4, 6, 8, 10, 12, 14, 16, 18, 20],
        [3, 6, 9, 12, 15, 18, 21, 24, 27, 30],
        [4, 8, 12, 16, 20, 24, 28, 32, 36, 40],
        [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
    ])->each(function ($value, $a, $b) {
        expect($a * $b)->toBe($value);
    });
});

it('can run a closure on each column for tall matrices', function () {
    Matrix::make()->rowHeaders([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->columnHeaders([1, 2, 3, 4, 5])->rows([
        [1, 2, 3, 4, 5],
        [2, 4, 6, 8, 10],
        [3, 6, 9, 12, 15],
        [4, 8, 12, 16, 20],
        [5, 10, 15, 20, 25],
        [6, 12, 18, 24, 30],
        [7, 14, 21, 28, 35],
        [8, 16, 24, 32, 40],
        [9, 18, 27, 36, 45],
        [10, 20, 30, 40, 50],
    ])->each(function ($value, $a, $b) {
        expect($a * $b)->toBe($value);
    });
});

it('can get the column value by row and column header values for square matrices', function () {
    $value = Matrix::make()->headers([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->rows([
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

it('can get the column value by row and column header values for wide matrices', function () {
    $value = Matrix::make()->rowHeaders([1, 2, 3, 4, 5])->columnHeaders([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->rows([
        [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        [2, 4, 6, 8, 10, 12, 14, 16, 18, 20],
        [3, 6, 9, 12, 15, 18, 21, 24, 27, 30],
        [4, 8, 12, 16, 20, 24, 28, 32, 36, 40],
        [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
    ])->column(5, 4);

    expect($value)->toBe(20);
});

it('can get the column value by row and column header values for tall matrices', function () {
    $value = Matrix::make()->rowHeaders([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->columnHeaders([1, 2, 3, 4, 5])->rows([
        [1, 2, 3, 4, 5],
        [2, 4, 6, 8, 10],
        [3, 6, 9, 12, 15],
        [4, 8, 12, 16, 20],
        [5, 10, 15, 20, 25],
        [6, 12, 18, 24, 30],
        [7, 14, 21, 28, 35],
        [8, 16, 24, 32, 40],
        [9, 18, 27, 36, 45],
        [10, 20, 30, 40, 50],
    ])->column(6, 4);

    expect($value)->toBe(24);
});

it('can get the column value on square matrices by row and column display header values', function () {
    $value = Matrix::make()->headers(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5, 'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 9, 'ten' => 10])->rows([
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

it('can get the column value on wide matrices by row and column display header values', function () {
    $value = Matrix::make()
        ->rowHeaders(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5, 'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 9, 'ten' => 10])
        ->columnHeaders(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5])
        ->rows([
            [1, 2, 3, 4, 5],
            [2, 4, 6, 8, 10],
            [3, 6, 9, 12, 15],
            [4, 8, 12, 16, 20],
            [5, 10, 15, 20, 25],
            [6, 12, 18, 24, 30],
            [7, 14, 21, 28, 35],
            [8, 16, 24, 32, 40],
            [9, 18, 27, 36, 45],
            [10, 20, 30, 40, 50],
        ])
        ->column('eight', 'two');

    expect($value)->toBe(16);
});

it('can get the column value on tall matrices by row and column display header values', function () {
    $value = Matrix::make()
        ->rowHeaders(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5])
        ->columnHeaders(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5, 'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 9, 'ten' => 10])
        ->rows([
            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            [2, 4, 6, 8, 10, 12, 14, 16, 18, 20],
            [3, 6, 9, 12, 15, 18, 21, 24, 27, 30],
            [4, 8, 12, 16, 20, 24, 28, 32, 36, 40],
            [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
        ])
        ->column('two', 'eight');

    expect($value)->toBe(16);
});

it('can get the column value on square matrices by row and column index values when no headers are present', function () {
    $value = Matrix::make()->headers(['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten'])->rows([
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
    ])->column(5, 3); // 'six', 'four'

    expect($value)->toBe(24);
});

it('can get the column value on wide matrices by row and column index values when no headers are present', function () {
    $value = Matrix::make()
        ->rowHeaders(['one', 'two', 'three', 'four', 'five'])
        ->columnHeaders(['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten'])
        ->rows([
            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            [2, 4, 6, 8, 10, 12, 14, 16, 18, 20],
            [3, 6, 9, 12, 15, 18, 21, 24, 27, 30],
            [4, 8, 12, 16, 20, 24, 28, 32, 36, 40],
            [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
        ])
        ->column(3, 5); // 'four', 'six'

    expect($value)->toBe(24);
});

it('can get the column value on tall matrices by row and column index values when no headers are present', function () {
    $value = Matrix::make()
        ->rowHeaders(['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten'])
        ->columnHeaders(['one', 'two', 'three', 'four', 'five'])
        ->rows([
            [1, 2, 3, 4, 5],
            [2, 4, 6, 8, 10],
            [3, 6, 9, 12, 15],
            [4, 8, 12, 16, 20],
            [5, 10, 15, 20, 25],
            [6, 12, 18, 24, 30],
            [7, 14, 21, 28, 35],
            [8, 16, 24, 32, 40],
            [9, 18, 27, 36, 45],
            [10, 20, 30, 40, 50],
        ])
        ->column(5, 3); // 'six', 'four'

    expect($value)->toBe(24);
});

it('will throw an exception when getting a column value where row header value does not exist', function () {
    expect(function () {
        Matrix::make()->headers(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5, 'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 9, 'ten' => 10])->rows([
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
    })->toThrow(InvalidArgumentException::class, 'Unable to find row.');
});

it('will throw an exception when getting a column value where column header value does not exist', function () {
    expect(function () {
        Matrix::make()->headers(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5, 'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 9, 'ten' => 10])->rows([
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
    })->toThrow(InvalidArgumentException::class, 'Unable to find column.');
});

it('can map each column value for square matrices', function () {
    $headers = [];
    $table = Matrix::make()->headers(['a', 'b', 'c', 'd'])->rows([
        [1, 1, 1, 1],
        [0, 1, 1, 1],
        [0, 0, 1, 1],
        [0, 0, 0, 1],
    ])->map(function ($value, $a, $b) use (&$headers) {
        $headers[] = $a . $b;

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
    expect($headers)->toBe(['aa', 'ab', 'ac', 'ad', 'ba', 'bb', 'bc', 'bd', 'ca', 'cb', 'cc', 'cd', 'da', 'db', 'dc', 'dd']);
});

it('can map each column value for wide matrices', function () {
    $headers = [];
    $table = Matrix::make()->rowHeaders(['a', 'b'])->columnHeaders(['a', 'b', 'c', 'd'])->rows([
        [1, 1, 1, 1],
        [0, 1, 1, 1],
    ])->map(function ($value, $a, $b) use (&$headers) {
        $headers[] = $a . $b;

        return $value ? 'Y' : 'N';
    })->toTable();

    expect($table)->toBe(collect([
        '┌───┬───┬───┬───┬───┐',
        '│   │ a │ b │ c │ d │',
        '├───┼───┼───┼───┼───┤',
        '│ a │ Y │ Y │ Y │ Y │',
        '│ b │ N │ Y │ Y │ Y │',
        '└───┴───┴───┴───┴───┘',
        '',
    ])->implode("\n"));
    expect($headers)->toBe(['aa', 'ab', 'ac', 'ad', 'ba', 'bb', 'bc', 'bd']);
});

it('can map each column value for tall matrices', function () {
    $headers = [];
    $table = Matrix::make()->rowHeaders(['a', 'b', 'c', 'd'])->columnHeaders(['a', 'b'])->rows([
        [1, 1],
        [0, 1],
        [0, 0],
        [0, 0],
    ])->map(function ($value, $a, $b) use (&$headers) {
        $headers[] = $a . $b;

        return $value ? 'Y' : 'N';
    })->toTable();

    expect($table)->toBe(collect([
        '┌───┬───┬───┐',
        '│   │ a │ b │',
        '├───┼───┼───┤',
        '│ a │ Y │ Y │',
        '│ b │ N │ Y │',
        '│ c │ N │ N │',
        '│ d │ N │ N │',
        '└───┴───┴───┘',
        '',
    ])->implode("\n"));
    expect($headers)->toBe(['aa', 'ab', 'ba', 'bb', 'ca', 'cb', 'da', 'db']);
});

it('can transpose a square matrix', function () {
    $table = Matrix::make()->headers(['A', 'B', 'C', 'D'])->rows([
        [1, 1, 1, 1],
        [0, 1, 1, 1],
        [0, 0, 1, 1],
        [0, 0, 0, 1],
    ])->transpose()->toTable();

    expect($table)->toBe(collect([
        '┌───┬───┬───┬───┬───┐',
        '│   │ A │ B │ C │ D │',
        '├───┼───┼───┼───┼───┤',
        '│ A │ 1 │ 0 │ 0 │ 0 │',
        '│ B │ 1 │ 1 │ 0 │ 0 │',
        '│ C │ 1 │ 1 │ 1 │ 0 │',
        '│ D │ 1 │ 1 │ 1 │ 1 │',
        '└───┴───┴───┴───┴───┘',
        '',
    ])->implode("\n"));
});

it('can transpose a wide matrix', function () {
    $table = Matrix::make()->rowHeaders(['A', 'B'])->columnHeaders(['A', 'B', 'C', 'D'])->rows([
        [1, 1, 1, 1],
        [0, 1, 1, 1],
    ])->transpose()->toTable();

    expect($table)->toBe(collect([
        '┌───┬───┬───┐',
        '│   │ A │ B │',
        '├───┼───┼───┤',
        '│ A │ 1 │ 0 │',
        '│ B │ 1 │ 1 │',
        '│ C │ 1 │ 1 │',
        '│ D │ 1 │ 1 │',
        '└───┴───┴───┘',
        '',
    ])->implode("\n"));
});

it('can transpose a tall matrix', function () {
    $table = Matrix::make()->rowHeaders(['A', 'B', 'C', 'D'])->columnHeaders(['A', 'B'])->rows([
        [1, 1],
        [0, 1],
        [0, 0],
        [0, 0],
    ])->transpose()->toTable();

    expect($table)->toBe(collect([
        '┌───┬───┬───┬───┬───┐',
        '│   │ A │ B │ C │ D │',
        '├───┼───┼───┼───┼───┤',
        '│ A │ 1 │ 0 │ 0 │ 0 │',
        '│ B │ 1 │ 1 │ 0 │ 0 │',
        '└───┴───┴───┴───┴───┘',
        '',
    ])->implode("\n"));
});

it('can flatten a square matrix', function () {
    $flattened = Matrix::make()->headers(['A', 'B', 'C', 'D'])->rows([
        [1, 1, 1, 1],
        [0, 1, 1, 1],
        [0, 0, 1, 1],
        [0, 0, 0, 1],
    ])->flatten();

    expect($flattened)->toBe([
        [1, 'A', 'A', 0, 0],
        [1, 'A', 'B', 0, 1],
        [1, 'A', 'C', 0, 2],
        [1, 'A', 'D', 0, 3],
        [0, 'B', 'A', 1, 0],
        [1, 'B', 'B', 1, 1],
        [1, 'B', 'C', 1, 2],
        [1, 'B', 'D', 1, 3],
        [0, 'C', 'A', 2, 0],
        [0, 'C', 'B', 2, 1],
        [1, 'C', 'C', 2, 2],
        [1, 'C', 'D', 2, 3],
        [0, 'D', 'A', 3, 0],
        [0, 'D', 'B', 3, 1],
        [0, 'D', 'C', 3, 2],
        [1, 'D', 'D', 3, 3],
    ]);
});

it('can flatten a wide matrix', function () {
    $flattened = Matrix::make()->rowHeaders(['A', 'B'])->columnHeaders(['A', 'B', 'C', 'D'])->rows([
        [1, 1, 1, 1],
        [0, 1, 1, 1],
    ])->flatten();

    expect($flattened)->toBe([
        [1, 'A', 'A', 0, 0],
        [1, 'A', 'B', 0, 1],
        [1, 'A', 'C', 0, 2],
        [1, 'A', 'D', 0, 3],
        [0, 'B', 'A', 1, 0],
        [1, 'B', 'B', 1, 1],
        [1, 'B', 'C', 1, 2],
        [1, 'B', 'D', 1, 3],
    ]);
});

it('can flatten a tall matrix', function () {
    $flattened = Matrix::make()->rowHeaders(['A', 'B', 'C', 'D'])->columnHeaders(['A', 'B'])->rows([
        [1, 1],
        [0, 1],
        [0, 0],
        [0, 0],
    ])->flatten();

    expect($flattened)->toBe([
        [1, 'A', 'A', 0, 0],
        [1, 'A', 'B', 0, 1],
        [0, 'B', 'A', 1, 0],
        [1, 'B', 'B', 1, 1],
        [0, 'C', 'A', 2, 0],
        [0, 'C', 'B', 2, 1],
        [0, 'D', 'A', 3, 0],
        [0, 'D', 'B', 3, 1],
    ]);
});

it('can be flattened with a callback to map each value', function () {
    $flattened = Matrix::make()->headers(['A', 'B', 'C', 'D'])->rows([
        [1, 1, 1, 1],
        [0, 1, 1, 1],
        [0, 0, 1, 1],
        [0, 0, 0, 1],
    ])->flatten(
        fn ($value, $rowHeader, $columnHeader) => [$value, $rowHeader, $columnHeader]
    );

    expect($flattened)->toBe([
        [1, 'A', 'A'],
        [1, 'A', 'B'],
        [1, 'A', 'C'],
        [1, 'A', 'D'],
        [0, 'B', 'A'],
        [1, 'B', 'B'],
        [1, 'B', 'C'],
        [1, 'B', 'D'],
        [0, 'C', 'A'],
        [0, 'C', 'B'],
        [1, 'C', 'C'],
        [1, 'C', 'D'],
        [0, 'D', 'A'],
        [0, 'D', 'B'],
        [0, 'D', 'C'],
        [1, 'D', 'D'],
    ]);
});
