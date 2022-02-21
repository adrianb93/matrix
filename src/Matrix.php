<?php

namespace AdrianBrown\Matrix;

use Closure;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\VarDumper\VarDumper;

class Matrix
{
    private array $rows = [];
    private array $rowHeaders = [];
    private array $rowDisplayHeaders = [];
    private array $columnHeaders = [];
    private array $columnDisplayHeaders = [];

    public static function make(): self
    {
        return new self();
    }

    public function headers(array $headers): self
    {
        return $this->rowHeaders($headers)->columnHeaders($headers);
    }

    public function rowHeaders(array $headers): self
    {
        $isAssociativeArray = array_filter(
            array_keys($headers),
            fn ($value) => is_string($value),
        );

        $this->rowDisplayHeaders = $isAssociativeArray ? array_keys($headers) : $headers;
        $this->rowHeaders = array_values($headers);

        return $this;
    }

    public function columnHeaders(array $headers): self
    {
        $isAssociativeArray = array_filter(
            array_keys($headers),
            fn ($value) => is_string($value),
        );

        $this->columnDisplayHeaders = $isAssociativeArray ? array_keys($headers) : $headers;
        $this->columnHeaders = array_values($headers);

        return $this;
    }

    public function rows(array $rows): self
    {
        $expectedRowAmount = count($this->rowHeaders);
        $expectedColumnAmount = count($this->columnHeaders);

        // Check rows
        if ($expectedRowAmount && count($rows) !== $expectedRowAmount) {
            throw new InvalidArgumentException("Expected {$expectedRowAmount} rows to match the amount of headers.");
        }

        // Check columns
        foreach ($rows as $row) {
            if (is_array($row) === false) {
                throw new InvalidArgumentException("Each row must be an array of {$expectedColumnAmount} items.");
            }

            if ($expectedColumnAmount && count($row) !== $expectedColumnAmount) {
                throw new InvalidArgumentException("Expected {$expectedColumnAmount} columns in each row to match the amount of headers.");
            }
        }

        if (count(array_unique(array_map('count', $rows))) !== 1) {
            throw new InvalidArgumentException("Rows must be of equal length.");
        }

        $this->rows = $rows;

        return $this;
    }

    public function column($rowHeader, $columnHeader)
    {
        $rowIndex = array_search($rowHeader, $this->rowHeaders);

        if ($rowIndex === false) {
            $rowIndex = array_search($rowHeader, $this->rowDisplayHeaders);
        }

        if ($rowIndex === false && array_key_exists($rowHeader, $this->rowHeaders)) {
            $rowIndex = $rowHeader;
        }

        if ($rowIndex === false) {
            throw new InvalidArgumentException('Unable to find row.');
        }

        $row = $this->rows[$rowIndex];

        $columnIndex = array_search($columnHeader, $this->columnHeaders);

        if ($columnIndex === false) {
            $columnIndex = array_search($columnHeader, $this->columnDisplayHeaders);
        }

        if ($columnIndex === false && array_key_exists($columnHeader, $this->columnHeaders)) {
            $columnIndex = $columnHeader;
        }

        if ($columnIndex === false) {
            throw new InvalidArgumentException('Unable to find column.');
        }

        return $row[$columnIndex];
    }

    public function map(Closure $callback): self
    {
        foreach ($this->flatten() as $tuple) {
            [$value, $rowHeader, $columnHeader, $xIndex, $yIndex] = $tuple;
            $this->rows[$xIndex][$yIndex] = $callback($value, $rowHeader, $columnHeader);
        }

        return $this;
    }

    public function each(Closure $callback): self
    {
        $this->flatten($callback);

        return $this;
    }

    public function flatten(?Closure $callback = null): array
    {
        $rows = $this->rows;
        $columns = array_map(
            fn (...$items) => $items,
            ...array_map(fn ($items) => $items, $this->rows)
        );

        $flattened = [];
        foreach ($rows as $rowIndex => $row) { // x = ├-
            foreach ($columns as $columnIndex => $column) {  // y = ─┬─
                $flattened[] = [$column[$rowIndex], $this->rowHeaders[$rowIndex], $this->columnHeaders[$columnIndex], $rowIndex, $columnIndex];
            }
        }

        $callback ??= fn (...$attributes) => $attributes;

        return array_map(fn ($attributes) => $callback(...$attributes), $flattened);
    }

    public function transpose(): self
    {
        $this->rows = array_map(
            fn (...$items) => $items,
            ...array_map(fn ($items) => $items, $this->rows)
        );

        $swapHeaders = $this->rowHeaders;
        $swapDisplayHeaders = $this->rowDisplayHeaders;
        $this->rowHeaders = $this->columnHeaders;
        $this->rowDisplayHeaders = $this->columnDisplayHeaders;
        $this->columnHeaders = $swapHeaders;
        $this->columnDisplayHeaders = $swapDisplayHeaders;

        return $this;
    }

    public function toTable($tableStyle = 'box', array $columnStyles = []): string
    {
        $table = new Table($output = new BufferedOutput());

        if ($this->columnDisplayHeaders) {
            $table->setHeaders($this->rowDisplayHeaders ? ['', ...$this->columnDisplayHeaders] : $this->columnDisplayHeaders);
        }

        $table->setRows(
            array_map(
                fn ($row, $index) => $this->rowDisplayHeaders ? [$this->rowDisplayHeaders[$index], ...$row] : $row,
                $this->rows,
                array_keys($this->rows),
            ),
        );

        $table->setStyle($tableStyle);
        foreach ($columnStyles as $index => $style) {
            $table->setColumnStyle($index, $style);
        }

        $table->render();

        $lines = explode("\n", $output->fetch());
        $lines[0] = (string) Str::of($lines[0])
            ->substr(1)
            ->reverse()
            ->substr(1)
            ->reverse()
            ->prepend('┌')
            ->append('┐')
            ->replace('┼', '┬');

        return implode("\n", $lines);
    }

    public function dd(): void
    {
        $this->dump();

        exit(1);
    }

    public function dump(): self
    {
        VarDumper::dump($this->toTable());

        return $this;
    }

    public function __toString()
    {
        return $this->toTable();
    }
}
