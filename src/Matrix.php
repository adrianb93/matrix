<?php

namespace AdrianBrown\Matrix;

use Closure;
use InvalidArgumentException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;

class Matrix
{
    private array $rows = [];
    private array $headers = [];
    private array $displayHeaders = [];

    public static function make(array $headers = [], array $rows = []): self
    {
        return (new self)->headers($headers)->rows($rows);
    }

    public function headers(array $headers): self
    {
        $isAssociativeArray = array_filter(
            array_keys($headers),
            fn ($value) => is_string($value),
        );

        $this->displayHeaders = $isAssociativeArray ? array_keys($headers) : $headers;
        $this->headers = array_values($headers);

        return $this;
    }

    public function rows(array $rows): self
    {
        $expectedAmount = count($this->headers);

        // Check rows
        if (count($rows) !== $expectedAmount) {
            throw new InvalidArgumentException("Expected {$expectedAmount} rows to match the amount of headers.");
        }

        // Check columns
        foreach ($rows as $row) {
            if (is_array($row) === false) {
                throw new InvalidArgumentException("Each row must be an array with {$expectedAmount} items.");
            }

            if (count($row) !== $expectedAmount) {
                throw new InvalidArgumentException("Expected {$expectedAmount} columns in each row to match the amount of headers.");
            }
        }

        $this->rows = $rows;

        return $this;
    }

    public function column($x, $y)
    {
        $rowIndex = array_search($y, $this->headers);

        if ($rowIndex === false) {
            $rowIndex = array_search($y, $this->displayHeaders);
        }

        if ($rowIndex === false) {
            throw new InvalidArgumentException('Unable to find row.');
        }

        $row = $this->rows[$rowIndex];

        $columnIndex = array_search($x, $this->headers);

        if ($columnIndex === false) {
            $columnIndex = array_search($x, $this->displayHeaders);
        }

        if ($columnIndex === false) {
            throw new InvalidArgumentException('Unable to find column.');
        }

        return $row[$columnIndex];
    }

    public function map(Closure $callback): self
    {
        foreach ($this->headers as $yIndex => $y) {
            foreach ($this->rows[$yIndex] as $xIndex => $value) {
                $x = $this->headers[$xIndex];
                $this->rows[$yIndex][$xIndex] = $callback($value, $x, $y);
            }
        }

        return $this;
    }

    public function each(Closure $callback): self
    {
        foreach ($this->headers as $yIndex => $y) {
            foreach ($this->rows[$yIndex] as $xIndex => $value) {
                $x = $this->headers[$xIndex];
                $callback($value, $x, $y);
            }
        }

        return $this;
    }

    public function toTable($tableStyle = 'box', array $columnStyles = []): string
    {
        $table = new Table($output = new BufferedOutput);

        $table->setHeaders(
            ['', ...$this->displayHeaders],
        )->setRows(
            array_map(
                fn ($row, $index) => [$this->displayHeaders[$index], ...$row],
                $this->rows,
                array_keys($this->rows),
            ),
        )->setStyle(
            $tableStyle
        );

        foreach ($columnStyles as $index => $style) {
            $table->setColumnStyle($index, $style);
        }

        $table->render();

        return $output->fetch();
    }

    public function dd(): void
    {
        dd($this->toTable());
    }

    public function dump(): self
    {
        dump($this->toTable());

        return $this;
    }

    public function __toString()
    {
        return $this->toTable();
    }
}
